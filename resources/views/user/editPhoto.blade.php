<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Foto</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-monsterrat">

    @include('components.navbar')

    <div class="flex justify-center items-center min-h-screen bg-gray-100 py-8 mt-10">
        <div id="editPhotoForm" class="w-4/5 bg-white shadow-md rounded-lg p-6 grid grid-cols-2 gap-8">
            <div class="flex items-center justify-center">
                <div
                    class="w-full h-[500px] border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 flex items-center justify-center">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo Preview"
                        class="max-w-full max-h-full object-contain rounded-lg">
                </div>
            </div>

            <div>
                <h1 class="text-2xl font-bold mb-6 text-center">Edit Foto</h1>

                <form action="{{ route('photos.update', $photo->photo_id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="text" name="title" placeholder="Judul Foto"
                        value="{{ old('title', $photo->title) }}" required
                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <textarea name="description" placeholder="Deskripsi Foto"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">{{ old('description', $photo->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    @if ($albums->count() > 0)
                        <div>
                            <label for="album_id" class="block mb-2 font-semibold">Pilih Album:</label>
                            <select name="album_id" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300">
                                <option value="">Pilih Album (Opsional)</option>
                                @foreach ($albums as $album)
                                    <option value="{{ $album->album_id }}"
                                        {{ $photo->album_id == $album->album_id ? 'selected' : '' }}>
                                        {{ $album->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Input kategori baru di atas -->
                    <div>
                        <label for="newCategories" class="block mb-2 font-semibold">Tambah Kategori Baru:</label>
                        <input type="text" name="newCategories" id="newCategories"
                            placeholder="Pisahkan kategori dengan koma" class="w-full px-3 py-2 border rounded-lg">
                    </div>

                    <!-- Kategori yang sudah ada -->
                    <div>
                        <div id="categories-container" class="flex flex-wrap gap-3 mb-8">
                            @foreach ($photo->categories as $category)
                                <div id="category-{{ $category->id }}" class="flex items-center mb-2">
                                    <div
                                        class="flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                        <span>{{ $category->name }}</span>
                                    </div>
                                    <!-- Tombol x untuk menghapus kategori di sebelah kategori -->
                                    <button type="button" class=" text-red-500 text-lg"
                                        onclick="removeCategory({{ $category->id }}, '{{ $category->name }}')">
                                        <i class="bx bx-x"></i>
                                    </button>
                                    <input type="hidden" name="categories[]" value="{{ $category->id }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('profile') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newCategoriesInput = document.getElementById('newCategories');
            const categoriesContainer = document.getElementById('categories-container');
            const form = document.querySelector('form');

            function addCategory(categoryName) {
                // Trim and validate category name
                categoryName = categoryName.trim();
                if (!categoryName) return;

                // Check for duplicate categories (case-insensitive)
                const existingCategories = document.querySelectorAll('#categories-container span');
                for (let cat of existingCategories) {
                    if (cat.textContent.trim().toLowerCase() === categoryName.toLowerCase()) {
                        alert('Kategori sudah ada!');
                        return;
                    }
                }

                // Create category element
                const categoryElement = document.createElement('div');
                categoryElement.id = 'category-new-' + Date.now(); // Unique ID for new categories
                categoryElement.className = 'flex items-center mb-2';
                categoryElement.innerHTML = `
            <div class="flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                <span>${categoryName}</span>
            </div>
            <button type="button" class="text-red-500 text-lg"
                onclick="removeCategory(null, '${categoryName}')">
                <i class="bx bx-x"></i>
            </button>
            <input type="hidden" name="newCategories" value="${categoryName}">
        `;

                categoriesContainer.appendChild(categoryElement);
                newCategoriesInput.value = ''; // Clear input
            }

            // Add category on button press (if you have an add button)
            const addCategoryBtn = document.getElementById('addCategoryBtn');
            if (addCategoryBtn) {
                addCategoryBtn.addEventListener('click', function() {
                    const categoryName = newCategoriesInput.value;
                    addCategory(categoryName);
                });
            }

            // Add category on Enter or comma
            newCategoriesInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ',') {
                    event.preventDefault();
                    const categoryName = this.value;
                    addCategory(categoryName);
                }
            });

            // Ensure form submission includes new categories
            form.addEventListener('submit', function(event) {
                // Collect all new category inputs
                const newCategoryInputs = document.querySelectorAll('input[name="newCategories"]');

                // If there are new categories, create a comma-separated string
                if (newCategoryInputs.length > 0) {
                    const newCategories = Array.from(newCategoryInputs)
                        .map(input => input.value.trim())
                        .filter(category => category !== '')
                        .join(',');

                    // Create a hidden input to send new categories
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'newCategories';
                    hiddenInput.value = newCategories;

                    form.appendChild(hiddenInput);
                }
            });
        });

        function removeCategory(categoryId, categoryName) {
            let categoryElement;

            // If it's an existing category (with numeric ID)
            if (categoryId !== null) {
                categoryElement = document.getElementById('category-' + categoryId);
            }
            // If it's a newly added category
            else {
                // Find the category by its name
                const categoryElements = document.querySelectorAll('#categories-container div');
                for (let element of categoryElements) {
                    if (element.querySelector('span').textContent.trim() === categoryName) {
                        categoryElement = element;
                        break;
                    }
                }
            }

            // Remove the category if found
            if (categoryElement) {
                categoryElement.remove();
            }
        }
    </script>

</body>

</html>
