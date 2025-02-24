<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Photos;
use App\Models\Comments;
use App\Models\UserActivity;
use App\Models\Likes;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;



class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Existing code for total statistics
        $totalUsers = User::count();
        $totalPhotos = Photos::count();
        $totalComments = Comments::count();
        $totalLikes = Likes::count();

        // Paginated recent users (existing code)
        $userPage = $request->input('user_page', 1);
        $recentUsers = User::whereNotNull('created_at')
            ->latest('created_at')
            ->paginate(3, ['*'], 'user_page', $userPage)
            ->onEachSide(1);

        // Paginated recent photos (existing code)
        $photoPage = $request->input('photo_page', 1);
        $recentPhotos = Photos::with('user')
            ->whereNotNull('created_at')
            ->latest('created_at')
            ->paginate(3, ['*'], 'photo_page', $photoPage)
            ->onEachSide(1);

        // Fetch recent user activities
        $activityPage = $request->input('activity_page', 1);
        $recentActivities = UserActivity::with('user')
            ->latest('created_at')
            ->paginate(5, ['*'], 'activity_page', $activityPage)
            ->onEachSide(1);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPhotos',
            'totalComments',
            'totalLikes',
            'recentUsers',
            'recentPhotos',
            'recentActivities'
        ));
    }

    public function userManagement()
    {
        $users = User::latest()
            ->paginate(10);

        return view('admin.user-management', compact('users'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:8|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'acess_level' => 'admin'
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function photoManagement()
    {
        $photos = Photos::with('user')
            ->latest()
            ->paginate(8);

        return view('admin.photo-management', compact('photos'));
    }

    public function profile()
    {
        $user = Auth::user();
        $photos = Photos::where('user_id', $user->user_id)
            ->oldest()
            ->paginate(8);
        $albums = Albums::where('user_id', $user->user_id)->get();

        return view('admin.profile', compact('user', 'photos', 'albums'));
    }

    public function editProfile()
    {
        return view('admin.editProfile', [
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
                Rule::unique('users')->ignore($user->user_id, 'user_id')
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
                Rule::unique('users')->ignore($user->user_id, 'user_id')
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

        // Log aktivitas update profil
        UserActivity::create([
            'user_id' => $user->user_id,
            'activity_type' => 'profile_update',
            'description' => 'Admin updated profile',
            'details' => json_encode([
                'fields_updated' => array_keys($validated)
            ]),
            'ip_address' => $request->ip()
        ]);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully');
    }

    public function showUserProfile($username, Request $request)
    {
        try {
            // Pastikan hanya admin yang bisa mengakses
            $adminUser = Auth::user();

            // Cari user yang akan dilihat profilnya
            $profileUser = User::where('username', $username)->firstOrFail();

            // Hindari admin melihat profil sendiri
            if ($adminUser->username === $username) {
                return redirect()->route('admin.profile');
            }

            // Pagination untuk foto
            $photos = $profileUser->photos()
                ->withCount([
                    'likes' => function ($query) {
                        $query->whereNotNull('user_id');
                    },
                    'comments'
                ])
                ->paginate(8);

            // Tambahkan informasi likes ke setiap foto
            $photos->getCollection()->transform(function ($photo) use ($adminUser) {
                // Gunakan primary key yang benar untuk likes
                $photo->is_liked = Likes::where('photo_id', $photo->photo_id)
                    ->where('user_id', $adminUser->user_id)
                    ->exists();
                return $photo;
            });

            // Debugging: Log detail foto
            \Log::info('Photos data', [
                'total_photos' => $photos->total(),
                'photos_data' => $photos->toArray()
            ]);

            // Jika request adalah AJAX
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('partials.others-profile-photo-grid', compact('photos'))->render(),
                    'next_page' => $photos->nextPageUrl(),
                    'current_page' => $photos->currentPage(),
                    'last_page' => $photos->lastPage()
                ]);
            }

            // Tampilkan halaman profil user untuk admin
            return view('admin.othersProfile', compact('profileUser', 'adminUser', 'photos'));
        } catch (\Exception $e) {
            // Log error lengkap
            \Log::error('Error in showUserProfile', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Tangani error dengan lebih baik
            return back()->with('error', 'Terjadi kesalahan saat memuat profil: ' . $e->getMessage());
        }
    }
}
