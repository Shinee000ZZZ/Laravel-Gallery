<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $profileUser->username }}'s Profile</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-monsterrat pt-16">

    @include('components.navbar')

    <!-- Profile Header -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-start gap-6">
            <!-- Profile Photo -->
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/' . $profileUser->profile_photo) }}" alt="Profile Photo"
                    class="w-32 h-32 rounded-full object-cover">
            </div>

            <!-- Profile Info -->
            <div class="flex-grow">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-medium">{{ $profileUser->username }}</h1>
                </div>

                <!-- Stats -->
                <div class="flex gap-6 mb-4">
                    <div>
                        <span class="font-medium">{{ $profileUser->photos->count() }}</span> kiriman
                    </div>
                    <div>
                        <span class="font-medium">{{ $profileUser->albums->count() }}</span> Album
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex justify-center mb-4 space-x-4">
        <button id="photoTab"
            class="text-blue-600 hover:text-blue-600 font-semibold border-b-4 border-blue-600 px-4 py-2">
            Photo
        </button>
        <button id="albumTab" class="text-gray-500 hover:text-blue-600 hover:border-blue-600 px-4 py-2">
            Album
        </button>
    </div>

    <!-- Photo Section -->
    <div class="max-w-screen-xl mx-auto p-4" id="photoSection" data-username="{{ $profileUser->username }}">
        @if ($photos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-1" id="othersProfilePhotoContainer">
                @include('partials.others-profile-photo-grid', ['photos' => $photos])
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12">
                <i class="bx bxs-folder text-6xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-xl mb-4">No photos uploaded yet</p>
            </div>
        @endif
    </div>

    <!-- Album Section -->
    <div id="albumSection" class="max-w-screen-xl mx-auto p-4 hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
            @forelse ($profileUser->albums as $album)
                <div class="bg-gray-100 rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('album.details', $album->album_id) }}">
                        @if ($album->cover)
                            <img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->title }}"
                                class="w-full h-48 object-cover hover:brightness-75 ease-in-out duration-200">
                        @else
                            <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-500">No Cover</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ $album->title }}</h3>
                            <p class="text-sm text-gray-600 mt-2">{{ $album->description }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <i class="bx bxs-folder text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-xl mb-4">No albums available</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            pointer-events: none;
        }
    </style>

    <!-- JavaScript for image protection -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('contextmenu', (e) => {
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                });

                img.oncontextmenu = (e) => {
                    e.preventDefault();
                    return false;
                };

                img.oncopy = (e) => {
                    e.preventDefault();
                    return false;
                };
            });

            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

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
