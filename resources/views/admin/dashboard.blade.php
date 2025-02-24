<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Galerizz</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-montserrat">
    <div class="flex min-h-screen">
        @include('admin.components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto ml-64 bg-gray-50">
            @include('admin.components.header', ['title' => 'Dashboard'])

            <!-- Statistics Cards -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-xl transition-all">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 text-sm uppercase tracking-wide">Total Users</h3>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalUsers }}</p>
                        </div>
                        <i class='bx bx-user text-4xl text-blue-400 opacity-50'></i>
                    </div>
                </div>

                <!-- Total Photos Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-xl transition-all">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 text-sm uppercase tracking-wide">Total Photos</h3>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalPhotos }}</p>
                        </div>
                        <i class='bx bx-image text-4xl text-green-400 opacity-50'></i>
                    </div>
                </div>

                <!-- Total Comments Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-xl transition-all">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 text-sm uppercase tracking-wide">Total Comments</h3>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalComments }}</p>
                        </div>
                        <i class='bx bx-comment text-4xl text-purple-400 opacity-50'></i>
                    </div>
                </div>

                <!-- Total Likes Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-xl transition-all">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 text-sm uppercase tracking-wide">Total Likes</h3>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $totalLikes }}</p>
                        </div>
                        <i class='bx bx-heart text-4xl text-red-400 opacity-50'></i>
                    </div>
                </div>
            </div>

             <!-- User Activities Section -->
             <div class="p-6">
                <div class="bg-white rounded-lg shadow-md border p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent User Activities</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">User</th>
                                    <th scope="col" class="px-6 py-3">Activity Type</th>
                                    <th scope="col" class="px-6 py-3">Description</th>
                                    <th scope="col" class="px-6 py-3">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentActivities as $activity)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <img src="{{ asset('storage/' . ($activity->user->profile_photo ?? 'default-avatar.png')) }}"
                                                    alt="{{ $activity->user->username }}"
                                                    class="w-8 h-8 rounded-full mr-3 object-cover">
                                                {{ $activity->user->username ?? 'Unknown User' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 rounded text-xs
                                                @switch($activity->activity_type)
                                                    @case('login') bg-blue-100 text-blue-800 @break
                                                    @case('upload') bg-green-100 text-green-800 @break
                                                    @case('delete') bg-red-100 text-red-800 @break
                                                    @case('edit') bg-yellow-100 text-yellow-800 @break
                                                    @case('like') bg-purple-100 text-purple-800 @break
                                                    @case('comment') bg-indigo-100 text-indigo-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                {{ ucfirst($activity->activity_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activity->description }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No recent activities
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($recentActivities->total() > 0)
                            <div class="mt-4">
                                {{ $recentActivities->appends([
                                        'user_page' => request('user_page'),
                                        'photo_page' => request('photo_page'),
                                    ])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Recent User --}}
                <div class="bg-white rounded-lg shadow-md border p-6 h-[420px] flex flex-col">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Users</h3>
                    </div>

                    <div class="flex-grow">
                        @if ($recentUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach ($recentUsers as $user)
                                    @php
                                        // Tentukan route berdasarkan apakah user adalah admin sendiri atau bukan
                                        $profileRoute = $user->user_id === Auth::user()->user_id
                                            ? route('admin.profile')
                                            : route('admin.users.profile', $user->username);
                                    @endphp
                                    <div class="flex items-center justify-between hover:bg-gray-50 p-2 rounded-lg cursor-pointer"
                                        onclick="window.location.href='{{ $profileRoute }}'">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ asset('storage/' . ($user->profile_photo ?? 'default-avatar.png')) }}"
                                                alt="{{ $user->username }}"
                                                class="w-10 h-10 rounded-full object-cover border">
                                            <div>
                                                <p class="font-medium text-gray-800">
                                                    {{ $user->username }}
                                                    @if ($user->user_id === Auth::user()->user_id)
                                                        <span class="text-xs text-blue-500 ml-2">(You)</span>
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">
                                            {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full text-center text-gray-500">
                                <div>
                                    <i class='bx bx-user-x text-4xl mb-4'></i>
                                    <p class="text-sm">No new users</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($recentUsers->total() > 0)
                        <div class="mt-4">
                            {{ $recentUsers->appends(['photo_page' => request('photo_page')])->links() }}
                        </div>
                    @endif
                </div>

                <!-- Recent Photos -->
                <div class="bg-white rounded-lg shadow-md border p-6 h-[420px] flex flex-col">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Photos</h3>
                    </div>

                    <div class="flex-grow">
                        @if ($recentPhotos->count() > 0)
                            <div class="space-y-4">
                                @foreach ($recentPhotos as $photo)
                                    <div class="flex items-center justify-between hover:bg-gray-50 p-2 rounded-lg cursor-pointer"
                                        onclick="window.location.href='{{ route('photos.show', $photo->photo_id) }}'">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ asset('storage/' . $photo->image_path) }}"
                                                alt="{{ $photo->title }}"
                                                class="w-16 h-16 object-cover rounded-lg border">
                                            <div>
                                                <p class="font-medium text-gray-800">
                                                    {{ Str::limit($photo->title, 20) }}</p>
                                                <p class="text-sm text-gray-500">
                                                    By {{ $photo->user->username ?? 'Unknown User' }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">
                                            {{ $photo->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full text-center text-gray-500">
                                <div>
                                    <i class='bx bx-image-alt text-4xl mb-4'></i>
                                    <p class="text-sm">No photos uploaded</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($recentPhotos->total() > 0)
                        <div class="mt-4">
                            {{ $recentPhotos->appends(['user_page' => request('user_page')])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
