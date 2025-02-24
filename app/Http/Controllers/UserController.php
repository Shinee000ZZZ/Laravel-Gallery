<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Str;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\Likes;
use App\Models\Photos;
use App\Models\categories;
use App\Models\Comments;
use Illuminate\Support\Facades\DB;
use App\Models\photo_category;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = $request->input('search');

        $photosQuery = Photos::with(['user', 'categories']);

        if ($query) {
            $photosQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhereHas('categories', function ($categoryQuery) use ($query) {
                        $categoryQuery->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($query) {
                        $userQuery->where('username', 'LIKE', "%{$query}%");
                    });
            });
        }

        $photos = $photosQuery->latest()->paginate(8);

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
        return view('user.index', compact('user', 'photos', 'query'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Cari di berbagai kategori
        $suggestions = collect();

        // Cari berdasarkan judul foto
        $photoTitles = Photos::where('title', 'LIKE', "%{$query}%")
            ->limit(3)
            ->pluck('title')
            ->map(function ($title) {
                return [
                    'type' => 'title',
                    'value' => $title,
                    'label' => $title,
                    'icon' => 'bx-search'
                ];
            });

        // Cari berdasarkan username
        $usernames = User::where('username', 'LIKE', "%{$query}%")
            ->limit(3)
            ->pluck('username')
            ->map(function ($username) {
                return [
                    'type' => 'username',
                    'value' => $username,
                    'label' => $username,
                    'icon' => 'bx-search'
                ];
            });

        // Cari berdasarkan kategori
        $categories = Categories::where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->pluck('name')
            ->map(function ($category) {
                return [
                    'type' => 'category',
                    'value' => $category,
                    'label' => $category,
                    'icon' => 'bx-search'
                ];
            });

        // Gabungkan semua suggestions
        $suggestions = $photoTitles->merge($usernames)->merge($categories)->unique('value')->take(6);

        return response()->json($suggestions);
    }

    public function editProfile()
    {
        return view('user.editProfile', [
            'user' => Auth::user()
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

        $trashedAlbums = Albums::onlyTrashed()->get();

        return view('user.trashedPhotos', compact('trashedPhotos', 'trashedAlbums',  'user'));
    }

    public function trashAlbum(Albums $album)
    {
        // Soft delete album
        $album->delete();

        return redirect()->route('profile')->with([
            'success' => 'Album dipindahkan ke trash',
            'albumSection' => true
        ]);
    }

    public function restoreAlbum($album_id)
    {
        // Temukan album yang sudah dihapus sementara
        $album = Albums::withTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($album_id);

        // Kembalikan album
        $album->restore();

        return redirect()->route('profile')->with([
            'success' => 'Album dikembalikan',
            'albumSection' => true
        ]);
    }

    /**
     * Hapus permanen album dari trash
     */
    public function forceDeleteAlbum($album_id)
    {
        // Temukan album yang sudah dihapus sementara
        $album = Albums::withTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($album_id);

        // Hapus cover album dari storage jika ada
        if ($album->cover && Storage::exists($album->cover)) {
            Storage::delete($album->cover);
        }

        // Hapus semua foto di dalam album
        $album->photos()->forceDelete();

        // Hapus permanen album
        $album->forceDelete();

        return redirect()->route('profile')->with([
            'success' => 'Album dikembalikan',
            'albumSection' => true
        ]);
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

    public function explore(Request $request, $selectedPhotoId = null)
    {
        $photos = Photos::with(['user', 'categories', 'comments', 'likes.user'])
            ->oldest()
            ->paginate(8);

        $selectedPhoto = null;
        $totalLikes = 0;
        $lastLikeUser = null;

        // Hanya proses $selectedPhoto jika ada parameter photoId
        if ($selectedPhotoId) {
            $selectedPhoto = Photos::with(['user', 'comments.user', 'likes.user'])
                ->find($selectedPhotoId);

            if ($selectedPhoto) {
                $totalLikes = $selectedPhoto->likes->count();
                $lastLikeUser = $selectedPhoto->likes->last()
                    ? $selectedPhoto->likes->last()->user
                    : null;
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('partials.explore-photo-grid', compact('photos'))->render(),
                'next_page' => $photos->nextPageUrl(),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage()
            ]);
        }

        return view('jelajah', compact('photos', 'selectedPhoto', 'lastLikeUser', 'totalLikes'));
    }

    public function photoDetail($photoId)
    {
        $photos = Photos::with(['user', 'categories', 'comments', 'likes.user'])
            ->oldest()
            ->paginate(8);

        $selectedPhoto = Photos::with(['user', 'comments.user', 'likes.user'])->findOrFail($photoId);

        $lastLikeUser = $selectedPhoto->likes->last()->user ?? null;
        $totalLikes = $selectedPhoto->likes->count();

        return view('jelajah', compact('photos', 'selectedPhoto', 'lastLikeUser', 'totalLikes'));
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

    public function editPhoto($photoId)
    {
        $photo = Photos::where('user_id', Auth::id())
            ->where('photo_id', $photoId)
            ->firstOrFail();

        $albums = Albums::where('user_id', Auth::id())->get();

        // Get all categories available
        $categories = Categories::all();

        $user = Auth::user();

        return view('user.editPhoto', compact('photo', 'albums', 'user', 'categories'));
    }

    public function updatePhoto(Request $request, $photoId)
    {
        // Tambahkan debugging
        \Log::info('Request Data:', $request->all());

        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'album_id' => 'nullable|exists:albums,album_id',
            'categories' => 'nullable|array', // Array of existing category ids
            'categories.*' => 'exists:categories,id',
            'newCategories' => 'nullable|string', // String input for new categories
        ]);

        $photo = Photos::where('user_id', Auth::id())
            ->where('photo_id', $photoId)
            ->firstOrFail();

        $updateData = [
            'title' => $validatedData['title'] ?? $photo->title,
            'description' => $validatedData['description'] ?? $photo->description,
        ];

        // Update album
        $updateData['album_id'] = $validatedData['album_id'] ?? null;

        // Update photo details
        $photo->update($updateData);

        // Handle existing categories
        if (isset($validatedData['categories'])) {
            $photo->categories()->sync($validatedData['categories']);
        }

        // Handle new categories
        if (isset($validatedData['newCategories']) && !empty($validatedData['newCategories'])) {
            // Debugging log
            \Log::info('New Categories Input: ' . $validatedData['newCategories']);

            // Split categories, trim whitespace, remove empty values
            $newCategories = array_filter(array_map('trim', explode(',', $validatedData['newCategories'])));

            \Log::info('Processed New Categories:', $newCategories);

            foreach ($newCategories as $newCategoryName) {
                // Skip if category name is empty
                if (empty($newCategoryName)) continue;

                // Check if category already exists (case-insensitive)
                $existingCategory = Categories::where('name', 'LIKE', $newCategoryName)->first();

                if (!$existingCategory) {
                    // Create new category if it doesn't exist
                    $category = Categories::create(['name' => $newCategoryName]);
                    \Log::info('Created New Category: ' . $newCategoryName);
                } else {
                    $category = $existingCategory;
                    \Log::info('Found Existing Category: ' . $newCategoryName);
                }

                // Attach category to photo if not already attached
                if (!$photo->categories->contains($category->id)) {
                    $photo->categories()->attach($category->id);
                    \Log::info('Attached Category to Photo: ' . $category->name);
                }
            }
        }

        return redirect()->route('photos.show', $photo->photo_id)->with('success', 'Foto berhasil diperbarui');
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

        // Proses kategori
        $user = auth()->user();
        $categoryIds = [];

        // Proses kategori yang sudah ada
        $existingCategories = $request->input('categories', []);
        if (!empty($existingCategories)) {
            foreach ($existingCategories as $categoryName) {
                if (!empty(trim($categoryName))) {
                    $category = Categories::firstOrCreate(
                        [
                            'name' => trim($categoryName),
                            'user_id' => $user->user_id
                        ],
                        [
                            'description' => null,
                            'created_at' => now()
                        ]
                    );
                    $categoryIds[] = $category->id;
                }
            }
        }

        // Proses kategori baru yang diinput manual
        if ($request->has('newCategory') && !empty($request->input('newCategory'))) {
            // Pecah kategori berdasarkan koma, kemudian trim setiap elemen untuk menghapus spasi yang tidak perlu
            $newCategories = array_map('trim', explode(',', $request->input('newCategory')));

            foreach ($newCategories as $newCategory) {
                if (!empty($newCategory)) {
                    $category = Categories::firstOrCreate(
                        [
                            'name' => $newCategory,
                            'user_id' => $user->user_id
                        ],
                        [
                            'description' => null,
                            'created_at' => now()
                        ]
                    );
                    $categoryIds[] = $category->id;
                }
            }
        }

        // Attach kategori ke foto
        if (!empty($categoryIds)) {
            $insertData = [];
            foreach ($categoryIds as $categoryId) {
                if ($categoryId) {
                    $insertData[] = [
                        'photo_id' => $photo->photo_id,
                        'category_id' => $categoryId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            if (!empty($insertData)) {
                DB::table('photo_categories')->insert($insertData);
            }
        }

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
                'description' => $request->description ?? null,
                'cover' => $cover,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('profile')->with('albumSection', true);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan album: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan album!');
        }
    }

    public function editAlbum($albumId)
    {
        $album = Albums::where('user_id', Auth::id())
            ->where('album_id', $albumId)
            ->firstOrFail();

        $user = Auth::user();

        return view('user.editAlbum', compact('album', 'user'));
    }

    public function updateAlbum(Request $request, $albumId)
    {
        $album = Albums::where('user_id', Auth::id())
            ->where('album_id', $albumId)
            ->firstOrFail();

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cover' => 'nullable|image|max:5120' // 5MB max
        ]);

        // Update album details
        $album->title = $validatedData['title'];
        $album->description = $validatedData['description'] ?? $album->description;

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($album->cover) {
                Storage::delete('public/' . $album->cover);
            }

            // Store new cover
            $coverPath = $request->file('cover')->store('album_covers', 'public');
            $album->cover = $coverPath;
        }

        $album->save();

        return redirect()->route('profile')->with('success', 'Album berhasil diperbarui');
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

        // Proses kategori
        $user = auth()->user();
        $categoryIds = [];

        // Proses kategori yang sudah ada
        $existingCategories = $request->input('categories', []);
        if (!empty($existingCategories)) {
            foreach ($existingCategories as $categoryName) {
                if (!empty(trim($categoryName))) {
                    $category = Categories::firstOrCreate(
                        [
                            'name' => trim($categoryName),
                            'user_id' => $user->user_id
                        ],
                        [
                            'description' => null,
                            'created_at' => now()
                        ]
                    );
                    $categoryIds[] = $category->id;
                }
            }
        }

        if ($request->has('newCategory') && !empty($request->input('newCategory'))) {
            // Pecah kategori berdasarkan koma, kemudian trim setiap elemen untuk menghapus spasi yang tidak perlu
            $newCategories = array_map('trim', explode(',', $request->input('newCategory')));

            foreach ($newCategories as $newCategory) {
                if (!empty($newCategory)) {
                    $category = Categories::firstOrCreate(
                        [
                            'name' => $newCategory,  // Gunakan langsung $newCategory yang sudah di-trim
                            'user_id' => $user->user_id
                        ],
                        [
                            'description' => null,
                            'created_at' => now()
                        ]
                    );
                    $categoryIds[] = $category->id;
                }
            }
        }

        // Attach kategori ke foto
        if (!empty($categoryIds)) {
            $insertData = [];
            foreach ($categoryIds as $categoryId) {
                if ($categoryId) {
                    $insertData[] = [
                        'photo_id' => $photo->photo_id,
                        'category_id' => $categoryId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            if (!empty($insertData)) {
                DB::table('photo_categories')->insert($insertData);
            }
        }

        return redirect('/profile')->with('success', 'Foto berhasil diunggah!');
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'photo_id' => 'required|exists:photos,photo_id',
            'comment_text' => 'required|string|max:500'
        ]);

        // Ambil foto untuk mendapatkan judul
        $photo = Photos::findOrFail($request->photo_id);

        $comment = Comments::create([
            'photo_id' => $request->photo_id,
            'user_id' => auth()->id(),
            'comment_text' => $request->comment_text
        ]);

        // Log comment activity
        UserActivity::create([
            'user_id' => auth()->user()->user_id, // Gunakan user_id
            'activity_type' => 'comment',
            'description' => 'User added a comment',
            'details' => json_encode([
                'photo_id' => $request->photo_id,
                'photo_title' => $photo->title,
                'comment_id' => $comment->id,
                'comment_preview' => substr($request->comment_text, 0, 50)
            ]),
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    // Update comment
    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'comment_text' => 'required|string|max:500'
        ]);

        $comment = Comments::findOrFail($commentId);

        // Verifikasi pemilik komentar
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Simpan komentar lama untuk log
        $oldCommentText = $comment->comment_text;

        // Update komentar
        $comment->comment_text = $request->comment_text;
        $comment->save();

        // Ambil foto untuk mendapatkan judul
        $photo = Photos::findOrFail($comment->photo_id);

        // Log comment update activity
        UserActivity::create([
            'user_id' => auth()->user()->user_id,
            'activity_type' => 'comment_update',
            'description' => 'User updated a comment',
            'details' => json_encode([
                'photo_id' => $comment->photo_id,
                'photo_title' => $photo->title,
                'comment_id' => $comment->id,
                'old_comment_preview' => substr($oldCommentText, 0, 50),
                'new_comment_preview' => substr($request->comment_text, 0, 50)
            ]),
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    // Delete comment
    public function deleteComment($commentId)
    {
        $comment = Comments::findOrFail($commentId);

        // Verifikasi pemilik komentar
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Ambil foto untuk mendapatkan judul sebelum menghapus
        $photo = Photos::findOrFail($comment->photo_id);

        // Log comment deletion activity sebelum menghapus
        UserActivity::create([
            'user_id' => auth()->user()->user_id,
            'activity_type' => 'comment_delete',
            'description' => 'User deleted a comment',
            'details' => json_encode([
                'photo_id' => $comment->photo_id,
                'photo_title' => $photo->title,
                'comment_id' => $comment->id,
                'comment_preview' => substr($comment->comment_text, 0, 50)
            ]),
            'ip_address' => request()->ip()
        ]);

        // Hapus komentar
        $comment->delete();

        return response()->json([
            'success' => true
        ]);
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

            // Log unlike activity
            UserActivity::create([
                'user_id' => $user->user_id,
                'activity_type' => 'unlike',
                'description' => 'User unliked a photo',
                'details' => json_encode([
                    'photo_id' => $photoId,
                    'photo_title' => $photo->title
                ]),
                'ip_address' => request()->ip()
            ]);
        } else {
            // If not liked, create a new like
            Likes::create([
                'photo_id' => $photoId,
                'user_id' => $user->user_id
            ]);
            $liked = true;

            // Log like activity
            UserActivity::create([
                'user_id' => $user->user_id,
                'activity_type' => 'like',
                'description' => 'User liked a photo',
                'details' => json_encode([
                    'photo_id' => $photoId,
                    'photo_title' => $photo->title
                ]),
                'ip_address' => request()->ip()
            ]);
        }

        return response()->json([
            'liked' => $liked,
            'total_likes' => $photo->likes()->count()
        ]);
    }
}
