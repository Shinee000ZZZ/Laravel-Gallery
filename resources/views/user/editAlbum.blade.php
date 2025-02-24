<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-monsterrat">
    @include('components.navbar')

    <div class="flex justify-center items-center min-h-screen bg-gray-100 py-8 mt-10">
        <div class="w-4/5 bg-white shadow-md rounded-lg p-6 grid grid-cols-2 gap-8">
            <div class="flex items-center justify-center">
                <div class="w-full h-[500px] bg-gray-50 flex items-center justify-center">
                    <div class="p-8">
                        <img id="coverPreview" src="{{ $album->cover ? asset('storage/' . $album->cover) : '' }}"
                            alt="Album Cover"
                            class="max-w-full max-h-full object-contain {{ $album->cover ? '' : 'hidden' }}">
                    </div>
                    <div id="noCoverText" class="text-gray-500 {{ $album->cover ? 'hidden' : '' }}">Belum ada sampul
                    </div>
                </div>
            </div>

            <div>
                <h1 class="text-2xl font-bold mb-6 text-center">Edit Album</h1>

                <form action="{{ route('albums.update', $album->album_id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block mb-2">Judul Album</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $album->title) }}"
                            required class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block mb-2">Deskripsi Album</label>
                        <textarea name="description" id="description" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">{{ old('description', $album->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cover" class="block mb-2">Ganti Sampul Album</label>
                        <div class="relative w-full">
                            <input type="file" name="cover" id="cover" accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                            <div class="w-full px-4 py-2 border rounded-lg bg-white flex items-center justify-between">
                                <span id="fileNameDisplay" class="text-gray-500">Pilih File</span>
                                <button type="button"
                                    class=" px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Cari
                                </button>
                            </div>
                        </div>
                        @error('cover')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('profile') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('cover').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('coverPreview');
            const noCoverText = document.getElementById('noCoverText');
            const container = preview.closest('div');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        // Create a canvas to crop/resize the image
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Get container dimensions
                        const containerWidth = container.clientWidth;
                        const containerHeight = container.clientHeight;

                        // Calculate scaling and cropping
                        const scale = Math.max(
                            containerWidth / this.width,
                            containerHeight / this.height
                        );

                        const scaledWidth = this.width * scale;
                        const scaledHeight = this.height * scale;

                        // Center crop
                        const offsetX = (scaledWidth - containerWidth) / 2;
                        const offsetY = (scaledHeight - containerHeight) / 2;

                        // Set canvas size to container size
                        canvas.width = containerWidth;
                        canvas.height = containerHeight;

                        // Draw the scaled and cropped image
                        ctx.drawImage(
                            img,
                            offsetX, offsetY,
                            containerWidth, containerHeight,
                            0, 0,
                            containerWidth, containerHeight
                        );

                        // Set preview source to cropped canvas
                        preview.src = canvas.toDataURL();
                        preview.classList.remove('hidden');
                        noCoverText.classList.add('hidden');
                    };
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                noCoverText.classList.remove('hidden');
            }
        });
    </script>

    <script>
        document.getElementById('cover').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : 'Pilih File';
            document.getElementById('fileNameDisplay').textContent = fileName;
        });
    </script>
</body>

</html>
