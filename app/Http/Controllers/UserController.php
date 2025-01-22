<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Photos;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $photos = Photos::all();
        $user = Auth::user();
        return view('user.index', compact('user', 'photos'));
    }

    public function explore()
    {
        $photos = Photos::all();
        return view('jelajah', compact('photos'));
    }

    public function profile()
    {
        $user = Auth::user();
        $photos = Photos::where('user_id', Auth::id())->get();
        $albums = Albums::where('user_id', Auth::id())->get();
        return view('user.profile', compact('user', 'photos', 'albums'));
    }


    public function upload()
    {
        $user = Auth::user();
        return view('user.upload', compact('user'));
    }


    public function storeAlbums(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ]);

        try {
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover')->store('covers', 'public');
            } else {
                $cover = null;
            }

            Albums::create([
                'title' => $request->title,
                'description' => $request->description,
                'cover' => $cover,
                'user_id' => Auth::id(),
            ]);

            return redirect('user.profile');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan album: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan album!');
        }
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'album_id'    => 'nullable|exists:albums,album_id',
        ]);

        // Upload file gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('photos', 'public');
        }

        // Simpan data ke database
        Photos::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image_path'  => $imagePath ?? null,
            'user_id'     => Auth::id(),  // Ambil user yang sedang login
            'album_id'    => null,        // Jika belum ada album, diisi null
        ]);

        // Redirect dengan pesan sukses
        return redirect('/index-user')->with('success', 'Foto berhasil diunggah!');
    }
}
