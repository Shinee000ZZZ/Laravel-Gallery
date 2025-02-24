<div class="flex flex-col items-center justify-center min-h-screen w-full p-4">
    <div class="flex flex-col items-center text-center space-y-4">
        <div class="bg-gray-100 rounded-xl p-4 mb-4">
            @if ($type === 'index')
                <i class="bx bx-image-add text-6xl text-gray-500"></i>
            @elseif ($type === 'search')
                <i class="bx bx-search-alt-2 text-6xl text-gray-500"></i>
            @endif
        </div>

        @if ($type === 'index')
            <h2 class="text-xl font-semibold text-gray-700">Belum ada foto yang tersedia</h2>
            <p class="text-sm text-gray-500">Mulai bagikan momen indahmu!</p>
            <a href="{{ route('upload') }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Unggah Foto Pertamamu
            </a>
        @elseif ($type === 'search')
            <h2 class="text-xl font-semibold text-gray-700">Tidak ada foto yang ditemukan</h2>
            <p class="text-sm text-gray-500">Coba kata kunci lain atau filter berbeda</p>
            <a href="{{ route('user.index') }}" class="text-blue-500 hover:underline">
                Kembali ke Beranda
            </a>
        @endif
    </div>
</div>

