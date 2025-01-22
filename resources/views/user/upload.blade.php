<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    {{-- navbar --}}
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl mx-auto p-4">
            <div class="flex justify-between items-center">
                <a href="{{ Route('user.index') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                    <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                            class="group-hover:text-blue-400 transition-colors duration-200 ease-in-out">rizz</span></span>
                </a>

                <div class="flex items-center space-x-3 md:order-2">
                    <!-- Dropdown Menu User -->
                    <button type="button"
                        class="flex text-sm bg-gray-800 border border-blue-600 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src=" {{ asset('storage/' . $user->profile_photo) }}"
                            alt="user photo">
                    </button>

                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:divide-gray-600 focus:ring-4 focus:ring-blue-500"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900 dark:text-white">{{ $user->username }}</span>
                            <span
                                class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ $user->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="{{ Route('profile') }}"
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

                <!-- Search Bar untuk Desktop -->
                <div class="hidden md:flex flex-grow items-center mx-4">
                    <input type="text" id="search"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500 cursor-not-allowed"
                        placeholder="Search..." disabled>

                    <!-- Tombol Upload -->
                    <a href="{{ Route('upload') }}"
                        class="ml-4 px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-full text-sm font-medium">
                        Upload
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-screen-xl mx-auto p-6 flex space-x-6 mt-4">

        <!-- Sidebar Menu -->
        <div class="w-1/5 bg-white shadow-md rounded-lg p-4 h-[560px] flex flex-col justify-between">
            <div>
                <button id="uploadPhotoBtn"
                    class="w-full mb-4 px-4 py-2 text-white bg-blue-600 hover:bg-gray-200 border border-blue-800 rounded-lg">
                    Upload Foto
                </button>
                <button id="uploadAlbumBtn"
                    class="w-full mb-4 px-4 py-2 text-blue-800 bg-white hover:bg-gray-200 border border-blue-800 rounded-lg">
                    Buat Album
                </button>
            </div>
            <div>
                <button onclick="window.location.href='{{ Route('user.index') }}'"
                    class="w-full px-4 py-2 text-red-500">
                    <i class='bx bx-arrow-back'></i> Kembali
                </button>
            </div>
        </div>

        <!-- Upload Foto Form -->
        <div id="uploadPhotoForm" class="w-4/5 bg-white shadow-md rounded-lg p-6 h-[560px] overflow-y-auto">
            <form action="{{ Route('photos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="flex flex-col items-center justify-center w-full">
                    <!-- Upload Input -->
                    <label for="photoInput"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 relative">
                        <!-- Preview Image -->
                        <img id="photoPreview" class="absolute w-full h-full object-contain rounded-lg hidden"
                            alt="Photo Preview">

                        <!-- Default Upload Icon -->
                        <div id="photoDefault" class="flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload
                                    Photo</span></p>
                        </div>
                        <input id="photoInput" type="file" name="image" class="hidden" required />
                    </label>
                </div>

                <input type="text" name="title" placeholder="Judul Foto" required
                    class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">
                <textarea name="description" placeholder="Deskripsi Foto"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300"></textarea>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Upload
                    Foto</button>
            </form>
        </div>


        <!-- Buat Album Form -->
        <div id="uploadAlbumForm" class="w-4/5 bg-white shadow-md rounded-lg p-6 h-[560px] overflow-y-auto hidden">
            <form action="{{ Route('albums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="text" name="title" placeholder="Nama Album" required
                    class="w-full px-3 py-2 border rounded-lg focus:ring-green-300">
                <textarea name="description" placeholder="Deskripsi Album"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-green-300"></textarea>

                <div class="flex flex-col items-center justify-center w-full">
                    <!-- Upload Input -->
                    <label for="albumCoverInput"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 relative">
                        <!-- Preview Cover -->
                        <img id="albumCoverPreview" class="absolute w-full h-full object-contain rounded-lg hidden"
                            alt="Album Cover Preview">

                        <!-- Default Upload Icon -->
                        <div id="albumDefault" class="flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload album
                                    cover</span></p>
                        </div>
                        <input id="albumCoverInput" type="file" name="cover" class="hidden" />
                    </label>
                </div>
                <button type="submit" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg">Buat
                    Album</button>
            </form>
        </div>

    </div>


    <script>
        const uploadPhotoBtn = document.getElementById('uploadPhotoBtn');
        const uploadAlbumBtn = document.getElementById('uploadAlbumBtn');
        const uploadPhotoForm = document.getElementById('uploadPhotoForm');
        const uploadAlbumForm = document.getElementById('uploadAlbumForm');
        // Preview Photo
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const photoDefault = document.getElementById('photoDefault');
        // Preview Cover Album
        const albumCoverInput = document.getElementById('albumCoverInput');
        const albumCoverPreview = document.getElementById('albumCoverPreview');
        const albumDefault = document.getElementById('albumDefault');


        uploadPhotoBtn.addEventListener('click', () => {
            uploadPhotoForm.classList.remove('hidden');
            uploadAlbumForm.classList.add('hidden');
            setActiveButton(uploadPhotoBtn);
        });

        uploadAlbumBtn.addEventListener('click', () => {
            uploadAlbumForm.classList.remove('hidden');
            uploadPhotoForm.classList.add('hidden');
            setActiveButton(uploadAlbumBtn);
        });

        function setActiveButton(activeButton) {
            const buttons = [uploadPhotoBtn, uploadAlbumBtn];
            buttons.forEach(button => {
                if (button === activeButton) {
                    button.classList.add('bg-blue-600', 'text-white');
                    button.classList.remove('bg-white', 'text-blue-800');
                } else {
                    button.classList.remove('bg-blue-600', 'text-white');
                    button.classList.add('bg-white', 'text-blue-800', 'border', 'border-blue-600');
                }
            });
        }

        // Preview Photo
        photoInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    photoPreview.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                    photoDefault.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Preview Cover Album
        albumCoverInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    albumCoverPreview.src = e.target.result;
                    albumCoverPreview.classList.remove('hidden');
                    albumDefault.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
