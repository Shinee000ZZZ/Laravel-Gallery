<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>trash</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-monsterrat pt-16">

    @include('components.navbar')

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Foto yang Dihapus</h1>
            <div class="flex items-center space-x-3">
                <span class="text-gray-600">Total: {{ $trashedPhotos->count() }} foto</span>
            </div>
        </div>

        @if ($trashedPhotos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($trashedPhotos as $photo)
                    <div
                        class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-xl group">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title }}"
                                class="w-full h-56 object-cover">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300">
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">
                                {{ $photo->title ?? 'Untitled Photo' }}
                            </h3>

                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500">
                                    Dihapus: {{ $photo->deleted_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="mt-4 flex space-x-2">
                                <form action="{{ route('photos.restore', $photo->photo_id) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="w-full bg-green-500 text-white py-2 px-2 rounded-lg hover:bg-green-600 transition flex items-center justify-center space-x-2">
                                        <i class='bx bx-revision'></i>
                                        <span>Kembalikan</span>
                                    </button>
                                </form>

                                <form action="{{ route('photos.force-delete', $photo->photo_id) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini secara permanen?')"
                                        class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition flex items-center justify-center space-x-2">
                                        <i class='bx bx-trash'></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center text-gray-600">
                <p>Foto di sampah akan dihapus secara permanen setelah 7 hari</p>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 bg-gray-50 rounded-xl">
                <i class='bx bx-archive text-6xl text-gray-400 mb-4'></i>
                <p class="text-xl text-gray-600 mb-4">Tidak ada foto di sampah</p>
                <a href="{{ route('profile') }}"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Kembali ke Profil
                </a>
            </div>
        @endif
    </div>



    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = e.submitter;
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    ${button.textContent}
                `;
            });
        });
    </script>
</body>

</html>
