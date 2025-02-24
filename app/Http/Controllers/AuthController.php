<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:8|max:20|unique:users,username',
            'contact' => ['required', 'regex:#^(\+62[0-9]{8,15}|[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,})$#'],
            'password' => 'required|string|min:8',
        ], [
            'contact.regex' => 'Harap masukkan email yang valid atau nomor telepon yang valid dengan format +62 (nomor).',
            'username.unique' => 'Username sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check apakah yang dimasukkan adalah nomor telepon atau email
        $contact = $request->input('contact');

        // Tambahkan debug
        \Log::info('Contact input: ' . $contact);

        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            // Jika email
            $email = $contact;
            $phone_number = null;
        } else {
            // Jika nomor telepon
            $email = null;
            $phone_number = $contact;

            // Tambahkan debug
            \Log::info('Phone number detected: ' . $phone_number);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $email,
                'phone_number' => $phone_number,
                'password' => Hash::make($request->password),
                'acess_level' => 'user',
            ]);

            // Tambahkan debug
            \Log::info('User created with ID: ' . $user->id);

            return redirect()->route('welcome')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            // Tambahkan logging error
            \Log::error('User creation error: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        // Sanitasi khusus untuk input nomor telepon
        $loginInput = $request->input('login');

        // Hapus semua spasi dari input yang dimulai dengan 0 dan berisi digit
        if (preg_match('/^0\s*[\d\s]+$/', $loginInput)) {
            // Hapus semua spasi dan tambahkan +62
            $loginInput = '+62' . preg_replace('/\s+/', '', substr($loginInput, 1));
        }

        // Override login input dengan input yang sudah disanitasi
        $request->merge(['login' => $loginInput]);

        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ], [
            'login.required' => 'Username, email, atau nomor telepon harus diisi.',
            'login.max' => 'Username, email, atau nomor telepon terlalu panjang.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tentukan jenis input login
        $loginField = 'username';
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
        } elseif (preg_match('/^\+62\d{8,15}$/', $request->login)) {
            $loginField = 'phone_number';
        }

        // Cek apakah user dengan username/email/nomor telepon tersebut ada
        $user = User::where($loginField, $request->login)->first();

        if (!$user) {
            // Log failed login attempt
            UserActivity::create([
                'user_id' => null, // Opsional: sesuaikan dengan kebutuhan
                'activity_type' => 'login_failed',
                'description' => 'Login failed - User not found',
                'details' => json_encode([
                    'login_input' => $request->login,
                    'login_field' => $loginField
                ]),
                'ip_address' => $request->ip()
            ]);

            return back()
                ->withErrors(['login' => 'Username, email, atau nomor telepon tidak ditemukan.'])
                ->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            // Log failed login attempt
            UserActivity::create([
                'user_id' => $user->user_id, // Gunakan user_id
                'activity_type' => 'login_failed',
                'description' => 'Login failed - Incorrect password',
                'details' => json_encode([
                    'login_field' => $loginField,
                    'login_input' => $request->login
                ]),
                'ip_address' => $request->ip()
            ]);

            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->withInput();
        }

        // Lakukan autentikasi
        if (Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Log successful login
            UserActivity::create([
                'user_id' => $user->user_id, // Gunakan user_id
                'activity_type' => 'login_success',
                'description' => 'User logged in successfully',
                'details' => json_encode([
                    'login_field' => $loginField,
                    'login_method' => 'email/username/phone'
                ]),
                'ip_address' => $request->ip()
            ]);

            // Cek access level
            if ($user->acess_level === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->acess_level === 'user') {
                return redirect()->route('user.index');
            } else {
                Auth::logout();

                // Log failed login due to invalid access level
                UserActivity::create([
                    'user_id' => $user->user_id, // Gunakan user_id
                    'activity_type' => 'login_failed',
                    'description' => 'Login failed - Invalid access level',
                    'details' => json_encode([
                        'access_level' => $user->acess_level
                    ]),
                    'ip_address' => $request->ip()
                ]);

                return back()
                    ->withErrors(['login' => 'Akun tidak memiliki akses yang valid.'])
                    ->withInput();
            }
        }

        // Jika autentikasi gagal
        return back()
            ->withErrors(['login' => 'Gagal melakukan login.'])
            ->withInput();
    }

    public function logout()
    {
        $user = Auth::user();

        // Log user activity before logout
        UserActivity::create([
            'user_id' => $user ? $user->user_id : null,
            'activity_type' => 'logout',
            'description' => 'User logged out',
            'details' => json_encode([]),
            'ip_address' => request()->ip()
        ]);

        Auth::logout();
        return redirect()->route('welcome');
    }
}
