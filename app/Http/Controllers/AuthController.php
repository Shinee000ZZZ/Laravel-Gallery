<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ], [
            'username.unique' => 'Username sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('welcome')->with('success', 'Registrasi berhasil!');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ], [
            'login.required' => 'Username atau email harus diisi.',
            'login.max' => 'Username atau email terlalu panjang.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah inputan adalah email atau username
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Cek apakah user dengan username/email tersebut ada
        $user = User::where($loginField, $request->login)->first();

        if (!$user) {
            return back()
                ->withErrors(['login' => 'Username atau email tidak ditemukan.'])
                ->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->withInput();
        }

        // Lakukan autentikasi
        if (Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Cek acess level
            if ($user->acess_level === 'admin') {
                return redirect()->route('admin.index');
            } elseif ($user->acess_level === 'user') {
                return redirect()->route('user.index');
            } else {
                Auth::logout();
                return back()
                    ->withErrors(['login' => 'Akun tidak memiliki akses yang valid.'])
                    ->withInput();
            }
        }

        return back()
            ->withErrors(['password' => 'Login gagal.'])
            ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
}
