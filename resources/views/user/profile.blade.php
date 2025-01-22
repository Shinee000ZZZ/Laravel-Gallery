<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="{{ route('user.index') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                        class="group-hover:text-blue-400 transition-colors duration-200 ease-in-out">rizz</span></span>
            </a>

            <!-- Search Bar -->
            <div class="flex-grow flex items-center mx-4">
                <input type="text" id="search"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search...">
                <a href="{{ route('upload') }}"
                    class="ml-4 px-6 py-2 text-blue-600 bg-white hover:bg-blue-600 hover:text-white ease-in-out duration-200 border-2 border-blue-600 rounded-full text-sm font-medium">
                    Upload
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <button type="button"
                    class="flex text-sm bg-gray-800 border border-blue-600 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <img class="w-8 h-8 rounded-full" src="{{ asset('storage/' . $user->profile_photo) }}"
                        alt="user photo">
                </button>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">{{ $user->username }}</span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ $user->email }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                                out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Profile Content -->
    <div class="max-w-screen-xl mx-auto p-4">
        <!-- Profile Photo -->
        <div class="flex justify-center items-center mb-4">
            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo"
                class="w-32 h-32 rounded-full border-4 border-blue-500">
        </div>

        <!-- Username and Email -->
        <div class="flex justify-center">
            <h2 class="text-2xl font-semibold">{{ $user->username }}</h2>
        </div>
        <div class="flex justify-center mb-4">
            <p class="text-gray-600">{{ $user->email }}</p>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex justify-center mb-4 space-x-4">
            <button id="photoTab" class="text-blue-600 font-semibold border-b-4 border-blue-600 px-4 py-2">
                Photo
            </button>
            <button id="albumTab" class="text-gray-500 hover:text-blue-600 hover:border-blue-600 px-4 py-2">
                Album
            </button>
        </div>

        <!-- Photo Section -->
        <div id="photoSection" class="max-w-screen-xl mx-auto p-4">
            <h2 class="text-2xl font-semibold mb-4">Photos Uploaded</h2>
            <div class="columns-3 md:columns-4 lg:columns-5 gap-4 space-y-4">
                @foreach ($photos as $photo)
                    <div class="break-inside-avoid overflow-hidden rounded-lg">
                        <img src="{{ Storage::url($photo->image_path) }}" alt="Uploaded Image"
                            class="w-full h-auto object-cover">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Album Section -->
        <div id="albumSection" class="max-w-screen-xl mx-auto p-4 hidden">
            <h2 class="text-2xl font-semibold mb-4">Albums</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($albums as $album)
                    <div class="bg-gray-100 rounded-lg shadow-md overflow-hidden">
                        @if ($album->cover)
                            <img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->title }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-500">No Cover</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ $album->title }}</h3>
                            <p class="text-sm text-gray-600 mt-2">{{ $album->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- JavaScript for Tabs -->
    <script>
        const photoTab = document.getElementById('photoTab');
        const albumTab = document.getElementById('albumTab');
        const photoSection = document.getElementById('photoSection');
        const albumSection = document.getElementById('albumSection');

        photoTab.addEventListener('click', () => {
            photoSection.classList.remove('hidden');
            albumSection.classList.add('hidden');
            photoTab.classList.add('text-blue-600', 'font-semibold', 'border-b-4', 'border-blue-600');
            albumTab.classList.remove('text-blue-600', 'font-semibold', 'border-b-4', 'border-blue-600');
            albumTab.classList.add('text-gray-500');
        });

        albumTab.addEventListener('click', () => {
            albumSection.classList.remove('hidden');
            photoSection.classList.add('hidden');
            albumTab.classList.add('text-blue-600', 'font-semibold', 'border-b-4', 'border-blue-600');
            photoTab.classList.remove('text-blue-600', 'font-semibold', 'border-b-4', 'border-blue-600');
            photoTab.classList.add('text-gray-500');
        });
    </script>
</body>

</html>
