<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $album->title }} - Album Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">

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

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="bg-gray-50 font-monsterrat pt-16">

    @include('components.navbar')

    <!-- Album Details Container -->
    <div class="max-w-screen-xl mx-auto p-6">
        <!-- Album Header -->
        <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $album->title }}</h1>
                    <p class="text-gray-500 mt-2">{{ $album->description }}</p>
                </div>
                @if (auth()->id() == $album->user_id)
                    <div class="flex space-x-4">
                        <button onclick="openUploadModal()"
                            class="px-6 py-2 bg-white border-2 text-blue-600 border-blue-600 rounded-full hover:bg-blue-700 hover:text-white transition flex items-center">
                            <i class='bx bx-plus mr-2'></i> Upload Foto Baru
                        </button>
                        <button onclick="openExistingPhotoModal()"
                            class="px-6 py-2 bg-white border-2 text-green-600 border-green-600 rounded-full hover:bg-green-700 hover:text-white  transition flex items-center">
                            <i class='bx bx-folder-plus mr-2'></i> Tambah Foto dari Galeri
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Photo Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($album->photos as $photo)
                <div
                    class="group relative rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <!-- Menu Dropdown -->
                    @if (auth()->id() == $album->user_id)
                        <div class="absolute top-2 right-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="relative">
                                <button onclick="togglePhotoMenu({{ $photo->photo_id }})"
                                    class="pt-1 px-2 rounded-full bg-black bg-opacity-50 text-white hover:bg-opacity-70 transition">
                                    <i class='bx bx-dots-horizontal-rounded text-xl'></i>
                                </button>
                                <!-- Dropdown Menu -->
                                <div id="photoMenu{{ $photo->photo_id }}"
                                    class="hidden absolute right-0 mt-1 w-48 rounded-lg bg-white shadow-lg py-1 text-sm text-gray-700">
                                    <a href="{{ route('photos.edit', $photo->photo_id) }}"
                                        class="block px-4 py-2 hover:bg-gray-100 flex items-center">
                                        <i class='bx bx-edit mr-2'></i> Edit Foto
                                    </a>
                                    <form
                                        action="{{ route('photos.trash', ['album' => $album->album_id, 'photo' => $photo->photo_id]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                            <i class='bx bx-trash text-red-500 mr-2'></i>
                                            Buang ke sampah
                                        </button>
                                    </form>
                                    <form
                                        action="{{ route('album.removePhoto', ['album' => $album->album_id, 'photo' => $photo->photo_id]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                            <i class='bx bx-trash text-red-500 mr-2'></i>
                                            Hapus dari Album
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Photo Content -->
                    <a href="{{ Route('photos.show', $photo->photo_id) }}">
                        <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->title }}"
                            class="w-full h-64 object-cover">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300
                    flex flex-col justify-end p-4 text-white opacity-0 group-hover:opacity-100">
                            <h3 class="font-semibold text-lg">{{ $photo->title }}</h3>
                            @if ($photo->description)
                                <p class="text-sm line-clamp-2">{{ $photo->description }}</p>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl shadow-md">
                    <i class='bx bx-image-alt text-6xl text-gray-400 mb-4'></i>
                    <p class="text-gray-500 text-xl">Tidak ada foto di album ini</p>
                    @if (auth()->id() == $album->user_id)
                        <button onclick="openUploadModal()"
                            class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                            Tambah Foto Pertama
                        </button>
                    @endif
                </div>
            @endforelse
        </div>


        <!-- Upload Modal -->
        <div id="uploadPhotoModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-bold">Tambah Foto Baru</h2>
                    <button onclick="closeUploadModal()"
                        class="text-3xl hover:text-blue-200 transition">&times;</button>
                </div>

                <!-- Modal Content with Scrollable Body -->
                <form action="{{ route('album.upload', $album->album_id) }}" method="POST"
                    enctype="multipart/form-data" class="flex-grow overflow-y-auto p-6 space-y-6 custom-scrollbar">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Width Upload Section -->
                        <div class="col-span-full">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Upload Foto</label>
                            <div
                                class="w-full relative border-2 border-dashed border-gray-300 rounded-lg
                        p-6 text-center hover:border-blue-500 transition group">
                                <input type="file" id="photoUpload" name="photo" accept="image/*" required
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    onchange="previewImage(event)">

                                <div id="imagePreviewContainer" class="hidden relative">
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        class="max-h-64 mx-auto rounded-lg object-cover">
                                </div>

                                <div id="uploadPlaceholder" class="flex flex-col items-center justify-center">
                                    <i
                                        class='bx bx-cloud-upload text-6xl text-gray-400 mb-4 group-hover:text-blue-500 transition'></i>
                                    <p class="text-gray-500 group-hover:text-blue-500 transition">Drag and drop or
                                        click to
                                        upload</p>
                                    <p class="text-xs text-gray-400 mt-2">Support JPEG, PNG, JPG, GIF, SVG (Max 2MB)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Title -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Judul Foto</label>
                            <input type="text" name="title" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg
                        focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <!-- Categories Section -->
                        <div class="col-span-full">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Kategori Terbaru</label>
                            <div id="tagContainer" class="flex flex-wrap gap-2 mb-3">
                                @foreach ($recentCategories as $category)
                                    <label
                                        class="flex items-center bg-gray-100 border border-gray-300
                                rounded-full px-4 py-1 cursor-pointer hover:bg-blue-100
                                transition group"
                                        id="tagContainer">
                                        <input type="checkbox" name="categories[]" value="{{ $category->name }}"
                                            class="hidden">
                                        <span class="group-hover:text-blue-600">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <label class="block mb-2 text-sm font-medium text-gray-700">Tambah Kategori Baru</label>
                            <input type="text" name="newCategory"
                                placeholder="Kategori Baru (Pisahkan dengan koma)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <!-- Description -->
                        <div class="col-span-full">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg
                        focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeUploadModal()"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg
                    hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg
                    hover:bg-blue-700 transition">
                            Upload Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Existing Photos Modal -->
        <div id="existingPhotoModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-4xl overflow-hidden shadow-2xl max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="bg-green-600 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-bold">Tambah Foto dari Galeri</h2>
                    <button onclick="closeExistingPhotoModal()"
                        class="text-3xl hover:text-green-200 transition">&times;</button>
                </div>

                <!-- Modal Content -->
                <div class="flex-grow overflow-y-auto p-6 custom-scrollbar">
                    <form action="{{ route('album.addExistingPhotos', $album->album_id) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        <!-- Select All Checkbox -->
                        <div class="flex items-center mb-4 space-x-2">
                            <input type="checkbox" id="selectAll"
                                class="w-5 h-5 rounded border-gray-300 focus:ring-green-500 text-green-600">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Pilih Semua Foto</label>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($userPhotos as $photo)
                                <div class="relative group">
                                    <input type="checkbox" name="photo_ids[]" value="{{ $photo->photo_id }}"
                                        class="photo-checkbox absolute top-2 left-2 z-10 w-5 h-5 rounded border-gray-300 focus:ring-green-500 text-green-600">
                                    <div class="relative rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->title }}"
                                            class="w-full h-48 object-cover">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300">
                                            <!-- Judul foto pada hover -->
                                            <div
                                                class="absolute bottom-0 left-0 right-0 p-3 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                <p class="text-sm font-medium">{{ $photo->title }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <i class='bx bx-image text-4xl text-gray-400 mb-2'></i>
                                    <p class="text-gray-500">Tidak ada foto yang tersedia untuk ditambahkan</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-4 border-t">
                            <button type="button" onclick="closeExistingPhotoModal()"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Tambahkan Foto Terpilih
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Kompresi -->
        <div id="compressionModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">Kompresi Foto</h2>
                <p class="mb-4">Foto Anda melebihi 2 MB. Apakah Anda ingin mengompres foto?</p>
                <div class="flex justify-end space-x-4">
                    <button onclick="cancelUpload()" class="px-4 py-2 bg-gray-200 rounded-lg">Batalkan</button>
                    <button onclick="confirmCompression()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">Kompres</button>
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
            function clearImagePreview() {
                const photoUpload = document.getElementById('photoUpload');
                const previewContainer = document.getElementById('imagePreviewContainer');
                const uploadPlaceholder = document.getElementById('uploadPlaceholder');

                photoUpload.value = ''; // Clear file input
                previewContainer.classList.add('hidden');
                uploadPlaceholder.classList.remove('hidden');
            }

            function previewImage(event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('imagePreviewContainer');
                const uploadPlaceholder = document.getElementById('uploadPlaceholder');
                const imagePreview = document.getElementById('imagePreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>

        <script>
            function openExistingPhotoModal() {
                document.getElementById('existingPhotoModal').classList.remove('hidden');
                document.getElementById('existingPhotoModal').classList.add('flex');
            }

            function closeExistingPhotoModal() {
                document.getElementById('existingPhotoModal').classList.add('hidden');
                document.getElementById('existingPhotoModal').classList.remove('flex');
            }

            // Select All functionality
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.getElementsByClassName('photo-checkbox');
                for (let checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            });

            // Update "Select All" state when individual checkboxes change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('photo-checkbox')) {
                    const checkboxes = document.getElementsByClassName('photo-checkbox');
                    const selectAll = document.getElementById('selectAll');
                    let allChecked = true;

                    for (let checkbox of checkboxes) {
                        if (!checkbox.checked) {
                            allChecked = false;
                            break;
                        }
                    }

                    selectAll.checked = allChecked;
                }
            });
        </script>

        <!-- JavaScript untuk menu dropdown -->
        <script>
            let activeMenu = null;

            function togglePhotoMenu(photoId) {
                event.preventDefault(); // Prevent link navigation
                event.stopPropagation(); // Prevent event bubbling

                const menu = document.getElementById(`photoMenu${photoId}`);

                // If there's an active menu and it's not the current one, hide it
                if (activeMenu && activeMenu !== menu) {
                    activeMenu.classList.add('hidden');
                }

                // Toggle current menu
                menu.classList.toggle('hidden');
                activeMenu = menu;
            }

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (activeMenu && !event.target.closest('.relative')) {
                    activeMenu.classList.add('hidden');
                    activeMenu = null;
                }
            });
        </script>

        <script>
            function openUploadModal() {
                document.getElementById('uploadPhotoModal').classList.remove('hidden');
            }

            function closeUploadModal() {
                document.getElementById('uploadPhotoModal').classList.add('hidden');
                // Reset form
                document.getElementById('photoUpload').value = '';
                document.getElementById('imagePreviewContainer').classList.add('hidden');
                document.getElementById('uploadPlaceholder').classList.remove('hidden');
            }

            function previewImage(event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('imagePreviewContainer');
                const uploadPlaceholder = document.getElementById('uploadPlaceholder');
                const imagePreview = document.getElementById('imagePreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>

        <style>
            img {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                pointer-events: none;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const images = document.querySelectorAll('img');
                images.forEach(img => {
                    // Prevent right-click save options
                    img.addEventListener('contextmenu', (e) => {
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                    });

                    // Remove event listeners to ensure image can't be interacted with
                    img.oncontextmenu = (e) => {
                        e.preventDefault();
                        return false;
                    };

                    // Prevent copying
                    img.oncopy = (e) => {
                        e.preventDefault();
                        return false;
                    };
                });

                // Prevent keyboard copy
                document.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C')) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        </script>
</body>

</html>
