<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menangani proses registrasi
    public function register(Request $request)
    {
        // Validasi inputan
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('welcome');
    }

    // Menangani proses login

    public function login(Request $request)
    {
        // Validasi inputan
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Cek apakah login valid
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil user yang sedang login
            $user = Auth::user();

            // Cek access_level dan redirect sesuai role
            if ($user->acess_level === 'admin') {
                return redirect()->route('admin.index');  // Redirect ke admin dashboard
            } elseif ($user->acess_level === 'user') {
                return redirect()->route('user.index');   // Redirect ke user dashboard
            } else {
                Auth::logout();  // Logout jika access_level tidak sesuai
                return back()->withErrors([
                    'email' => 'Akun tidak memiliki akses yang valid.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('welcome');
    }
}
