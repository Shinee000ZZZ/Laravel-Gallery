<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #tagContainer label {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
        }

        #tagContainer label:hover {
            background-color: #cce4ff;
            border-color: #007bff;
        }

        #tagContainer input:checked+span {
            color: #007bff;
            font-weight: bold;
        }
    </style>

</head>

<body class="bg-gray-100 font-monsterrat">

    @include('components.navbar-upload')

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
            <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="flex flex-col items-center justify-center w-full">
                    <!-- Upload Input -->
                    <label for="photoInput"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 relative"
                        ondragover="event.preventDefault(); this.classList.add('border-blue-500');"
                        ondragleave="event.preventDefault(); this.classList.remove('border-blue-500');"
                        ondrop="event.preventDefault(); event.stopPropagation();
            this.classList.remove('border-blue-500');
            handleFileDrop(event, 'photoInput', 'photoPreview', 'photoDefault');">
                        <img id="photoPreview" class="absolute w-full h-full object-contain rounded-lg hidden"
                            alt="Photo Preview">
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

                <!-- album select -->
                <div>
                    <label for="album_id" class="block mb-2 font-semibold">Pilih Album:</label>
                    <select name="album_id" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">
                        <option value="">Pilih Album (Opsional)</option>
                        @foreach ($albums as $album)
                            <option value="{{ $album->album_id }}">{{ $album->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Multi-select untuk kategori yang sudah ada -->
                <div>
                    <label for="categories" class="block mb-2 font-semibold">Kategori terbaru anda:</label>
                    <div id="tagContainer" class="flex flex-wrap gap-2">
                        @foreach ($categories as $category)
                            <label
                                class="flex items-center bg-gray-100 border border-gray-300 rounded-full px-4 py-1 cursor-pointer hover:bg-blue-100">
                                <input type="checkbox" name="categories[]" value="{{ $category->name }}" class="hidden" autocomplete="off">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Input kategori baru -->
                <div>
                    <label for="newCategory" class="block mb-2 font-semibold">Tambah Kategori Baru:</label>
                    <input type="text" id="newCategory" name="newCategory"
                        placeholder="Kategori Baru (Pisahkan dengan koma)"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">
                </div>

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
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 relative"
                        ondragover="event.preventDefault(); this.classList.add('border-blue-500');"
                        ondragleave="event.preventDefault(); this.classList.remove('border-blue-500');"
                        ondrop="event.preventDefault(); event.stopPropagation();
                        this.classList.remove('border-blue-500');
                        handleFileDrop(event, 'albumCoverInput', 'albumCoverPreview', 'albumDefault');">
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
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Buat
                    Album</button>
            </form>
        </div>

    </div>

    <!-- Modal Konfirmasi Kompresi -->
    <div id="compressionModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center cursor-pointer"
        onclick="event.stopPropagation()">
        <div class="bg-white rounded-lg p-6 max-w-md w-full" onclick="event.stopPropagation()">
            <h2 class="text-xl font-bold mb-4">Kompresi Foto</h2>
            <p class="mb-4">Foto Anda melebihi 2 MB. Apakah Anda ingin mengompres foto?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelUpload()"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Batalkan</button>
                <button onclick="confirmCompression()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Kompres</button>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('#photoInput, #photoUpload, #albumCoverInput');
            fileInputs.forEach(input => {
                input.addEventListener('change', checkImageSize);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('type') === 'album') {
                uploadAlbumBtn.click();
            }
        });
    </script>

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

    <script>
        function handleFileDrop(event, inputId, previewId, defaultId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const defaultElement = document.getElementById(defaultId);

            const files = event.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];

                // Gunakan cara yang sama seperti input biasa
                input.files = files;

                // Trigger event change untuk mengaktifkan fungsi checkImageSize di app.js
                const changeEvent = new Event('change', {
                    bubbles: true
                });
                input.dispatchEvent(changeEvent);

                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    defaultElement.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
