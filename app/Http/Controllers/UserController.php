<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Str;
use App\Models\User;
use App\Models\Likes;
use App\Models\Photos;
use App\Models\categories;
use App\Models\Comments;
use App\Models\photo_category;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $photos = Photos::with('user')
            ->oldest()
            ->paginate(8);

        // Pastikan ini adalah request AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('partials.photo-grid', compact('photos'))->render(),
                'next_page' => $photos->nextPageUrl(),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage()
            ]);
        }

        $user = Auth::user();
        return view('user.index', compact('user', 'photos'));
    }

    public function editProfile()
    {
        return view('user.editProfile', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Siapkan rules validasi dasar (non-unique fields)
        $rules = [
            'name' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Validasi username hanya jika berubah
        if ($request->username !== $user->username) {
            $rules['username'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ];
        } else {
            $rules['username'] = 'nullable|string|max:255';
        }

        // Validasi email hanya jika berubah
        if ($request->email !== $user->email) {
            $rules['email'] = [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ];
        } else {
            $rules['email'] = 'nullable|string|email|max:255';
        }

        // Validasi request
        $validated = $request->validate($rules);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }

        // Update user
        $user->fill($validated);
        $user->touch(); // Update updated_at column
        $user->save();

        return redirect()
            ->route('profile', $user->username)
            ->with('success', 'Profile updated successfully');
    }

    public function trash(Photos $photo)
    {
        // Hapus sementara foto
        $photo->delete();

        return redirect()->back()->with('success', 'Foto dipindahkan ke trash');
    }

    /**
     * Kembalikan foto dari trash
     */
    public function restore($photo_id)
    {
        // Temukan foto yang sudah dihapus sementara
        $photo = Photos::withTrashed()->findOrFail($photo_id);

        // Kembalikan foto
        $photo->restore();

        return redirect()->back()->with('success', 'Foto dikembalikan');
    }

    /**
     * Hapus permanen foto dari trash
     */
    public function forceDelete($photo_id)
    {
        // Temukan foto yang sudah dihapus sementara
        $photo = Photos::withTrashed()->findOrFail($photo_id);

        // Hapus file foto dari storage
        if ($photo->image_path && Storage::exists($photo->image_path)) {
            Storage::delete($photo->image_path);
        }

        // Hapus permanen foto
        $photo->forceDelete();

        return redirect()->back()->with('success', 'Foto dihapus permanen');
    }

    /**
     * Tampilkan foto-foto yang sudah di-trash
     */
    public function trashedPhotos()
    {
        $user = Auth::user();
        $trashedPhotos = Photos::onlyTrashed()->get();

        return view('user.trashedPhotos', compact('trashedPhotos', 'user'));
    }

    public function show($photo_id)
    {
        $photo = Photos::with(['comments', 'user', 'categories'])->findOrFail($photo_id);
        $user = Auth::user();

        return view('user.details', compact('photo', 'user'));
    }

    public function showProfile($username, Request $request)
    {
        $user = Auth::user();

        $profileUser = User::where('username', $username)->firstOrFail();

        // Cek apakah user yang sedang login melihat profilnya sendiri
        if (auth()->check() && auth()->user()->username === $username) {
            return redirect()->route('profile');
        }

        // Pagination untuk foto
        $photos = $profileUser->photos()
            ->withCount(['likes', 'comments'])
            ->paginate(8);

        // Jika request adalah AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.others-profile-photo-grid', compact('photos'))->render(),
                'next_page' => $photos->nextPageUrl(),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage()
            ]);
        }

        // Jika bukan profile sendiri, tampilkan halaman othersProfile
        return view('user.othersProfile', compact('profileUser', 'user', 'photos'));
    }

    public function explore(Request $request)
    {
        $photos = Photos::with('user')
            ->oldest() // Atau sesuaikan dengan kebutuhan sorting
            ->paginate(8); // Gunakan pagination

        // Pastikan ini adalah request AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('partials.explore-photo-grid', compact('photos'))->render(),
                'next_page' => $photos->nextPageUrl(),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage()
            ]);
        }

        return view('jelajah', compact('photos'));
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $photos = Photos::where('user_id', Auth::id())
            ->oldest()
            ->paginate(8);
        $albums = Albums::where('user_id', Auth::id())->get();

        // Jika request adalah AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.profile-photo-grid', compact('photos'))->render(),
                'next_page' => $photos->nextPageUrl(),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage()
            ]);
        }

        return view('user.profile', compact('user', 'photos', 'albums'));
    }

    public function removePhoto(Request $request, $album_id, $photo_id)
    {
        // Verify ownership
        $album = Albums::findOrFail($album_id);
        if ($album->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action');
        }

        // Update photo to remove it from album
        Photos::where('photo_id', $photo_id)
            ->where('album_id', $album_id)
            ->update(['album_id' => null]);

        return back()->with('success', 'Foto berhasil dihapus dari album');
    }

    public function upload()
    {
        $user = Auth::user();
        $albums = Albums::where('user_id', $user->user_id)->get();
        $categories = Categories::where('user_id', $user->user_id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.upload', [
            'user' => $user,
            'albums' => $albums,
            'categories' => $categories
        ]);
    }

    public function showAlbumDetails($album_id)
    {
        $album = Albums::with('photos')->findOrFail($album_id);

        // Fetch 5 most recent categories
        $recentCategories = categories::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch user's photos that are not in any album
        $userPhotos = Photos::where('user_id', auth()->id())
            ->whereNull('album_id')
            ->latest()
            ->get();

        return view('user.albumDetails', [
            'album' => $album,
            'user' => auth()->user(),
            'recentCategories' => $recentCategories,
            'userPhotos' => $userPhotos
        ]);
    }

    public function addExistingPhotos(Request $request, $album_id)
    {
        $request->validate([
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:photos,photo_id'
        ]);

        // Update album_id untuk foto yang dipilih
        Photos::whereIn('photo_id', $request->photo_ids)
            ->where('user_id', auth()->id())
            ->update(['album_id' => $album_id]);

        return redirect()->back()->with('success', 'Foto berhasil ditambahkan ke album');
    }

    public function uploadPhotoToAlbum(Request $request, $albumId)
    {
        $album = Albums::findOrFail($albumId);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // validasi lainnya
        ]);

        if ($album->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Kompresi gambar jika ukuran > 2MB
        $file = $request->file('photo');
        if ($file->getSize() > 2 * 1024 * 1024) {
            $image = Image::make($file);
            $image->resize(1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->encode('jpg', 80);

            $filename = 'compressed_' . uniqid() . '.jpg';
            $path = 'albums/' . $albumId . '/' . $filename;
            Storage::put('public/' . $path, $image);
            $photoPath = $path;
        } else {
            $photoPath = $file->store('albums/' . $albumId, 'public');
        }

        $photo = new Photos([
            'album_id' => $albumId,
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'image_path' => $photoPath,
            'description' => $request->input('description', '')
        ]);
        $photo->save();

        // Proses kategori (kode sebelumnya)

        return redirect()->back()->with('success', 'Foto berhasil ditambahkan ke album');
    }

    public function storeAlbums(Request $request)
    {
        $request->validate([
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');

                // Kompresi gambar jika ukuran > 2MB
                if ($file->getSize() > 2 * 1024 * 1024) {
                    $image = Image::make($file);
                    $image->resize(1920, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image->encode('jpg', 80);

                    $filename = 'compressed_cover_' . uniqid() . '.jpg';
                    $path = 'covers/' . $filename;
                    Storage::put('public/' . $path, $image);
                    $cover = $path;
                } else {
                    $cover = $file->store('covers', 'public');
                }
            } else {
                $cover = null;
            }

            Albums::create([
                'title' => $request->title,
                'description' => $request->description,
                'cover' => $cover,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('profile')->with('albumSection', true);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan album: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan album!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // validasi lainnya
        ]);

        $file = $request->file('image');

        // Kompresi gambar jika ukuran > 2MB
        if ($file->getSize() > 2 * 1024 * 1024) {
            $image = Image::make($file);
            $image->resize(1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->encode('jpg', 80);

            $filename = 'compressed_' . uniqid() . '.jpg';
            $path = 'photos/' . $filename;
            Storage::put('public/' . $path, $image);
            $imagePath = $path;
        } else {
            $imagePath = $file->store('photos', 'public');
        }

        $photo = Photos::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'user_id' => auth()->id(),
            'album_id' => $request->album_id ?? null,
        ]);

        // Proses kategori (kode sebelumnya)

        return redirect('/index-user')->with('success', 'Foto berhasil diunggah!');
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'photo_id' => 'required|exists:photos,photo_id',
            'comment_text' => 'required|string|max:500'
        ]);

        Comments::create([
            'photo_id' => $request->photo_id,
            'user_id' => auth()->id(),
            'comment_text' => $request->comment_text
        ]);

        return back()->with('success', 'Comment added successfully');
    }

    public function toggleLike($photoId)
    {
        $photo = Photos::findOrFail($photoId);
        $user = auth()->user();

        $existingLike = Likes::where('photo_id', $photoId)
            ->where('user_id', $user->user_id)
            ->first();

        if ($existingLike) {
            // Explicitly delete the like
            $existingLike->delete();
            $liked = false;
        } else {
            // If not liked, create a new like
            Likes::create([
                'photo_id' => $photoId,
                'user_id' => $user->user_id
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'total_likes' => $photo->likes()->count()
        ]);
    }
}
