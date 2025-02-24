<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Management - Galerizz</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-montserrat">
    <div class="flex">

        {{-- sidebar --}}
        @include('admin.components.sidebar')

        <!-- Main Content -->
        <div class="fixed left-64 right-0 top-0 bottom-0 flex flex-col">

            @include('admin.components.header', ['title' => 'Photo Manajemen'])

            <!-- Content -->
            <div class="p-6 overflow-y-auto">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Photos Grid -->
                <div class="bg-white rounded-lg shadow">
                    <!-- Search and Upload Section -->
                    <div class="p-6 border-b">
                        <div class="flex items-center justify-between">
                            <!-- Search Bar -->
                            <div class="relative">
                                <input type="text" placeholder="Cari foto..."
                                    class="pl-10 pr-4 py-2 border rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                            </div>

                            <!-- Upload Button -->
                            <a href="{{ route('upload') }}"
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class='bx bx-upload'></i>
                                Upload
                            </a>
                        </div>
                    </div>

                    <!-- Grid Content -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <!-- Rest of the grid content remains the same -->
                            @forelse ($photos as $photo)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $photo->image_path) }}"
                                            alt="{{ $photo->title }}" class="w-full h-48 object-cover">

                                        <!-- Hover Overlay -->
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="flex space-x-2">
                                                <!-- View Button -->
                                                <a href="{{ route('photos.show', $photo->photo_id) }}"
                                                    class="flex items-center justify-center px-3 py-3 bg-white rounded-full hover:bg-gray-100 transition-colors">
                                                    <i class='bx bx-show text-gray-600'></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <h3 class="font-medium text-gray-900 truncate">{{ $photo->title }}</h3>
                                        <div class="mt-2 flex items-center space-x-2">
                                            <img src="{{ asset('storage/' . $photo->user->profile_photo) }}"
                                                alt="{{ $photo->user->username }}"
                                                class="h-6 w-6 rounded-full object-cover">
                                            <span class="text-sm text-gray-600">{{ $photo->user->username }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full flex flex-col items-center justify-center py-12">
                                    <i class='bx bx-image text-6xl text-gray-400 mb-4'></i>
                                    <p class="text-gray-500 text-xl">Belum ada foto yang diunggah</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t">
                        {{ $photos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
