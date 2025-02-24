@forelse ($photos as $photo)
    @if ($photo->image_path)
        <div class="photo-item break-inside-avoid mb-3 relative group">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="relative">
                    <a href="{{ route('photo.detail', $photo->photo_id) }}" class="block overflow-hidden rounded-t-xl">
                        <img class="w-full h-auto object-cover hover:brightness-75 ease-in-out duration-200 lazy"
                            data-src="{{ asset('storage/' . $photo->image_path) }}?v={{ filemtime(public_path('storage/' . $photo->image_path)) }}"
                            lazy="loading"
                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                            alt="Photo: {{ $photo->title ?? '' }}">
                    </a>

                    <!-- Wrapper yang akan mengontrol visibilitas tiga titik -->
                    <div
                        class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="relative group-dropdown">
                            <!-- Tombol tiga titik -->
                            <button id="dropdown-btn-{{ $photo->photo_id }}"
                                class="bg-white rounded-full px-1 hover:bg-white/70 transition-all duration-300">
                                <i class='bx bx-dots-horizontal-rounded text-lg text-gray-700'></i>
                            </button>

                            <!-- Menu Dropdown -->
                            <div id="dropdown-menu-{{ $photo->photo_id }}"
                                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden z-50 opacity-0 invisible transition-all duration-300">
                                <button
                                    onclick="downloadPhoto('{{ asset('storage/' . $photo->image_path) }}', '{{ $photo->title ?? 'photo' }}')"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2">
                                    <i class='bx bx-download'></i>
                                    <span>Download</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Info Section -->
                <div class="p-3">
                    <h3 class="font-medium text-sm truncate">{{ $photo->title }}</h3>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center space-x-2">
                            <!-- User Avatar -->
                            <img src="{{ $photo->user->profile_photo ? asset('storage/' . $photo->user->profile_photo) : asset('img/default-avatar.png') }}"
                                class="w-6 h-6 rounded-full object-cover" alt="{{ $photo->user->username }}">
                            <span class="text-xs text-gray-600">{{ $photo->user->username }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@empty
    <div class="col-span-full text-center py-10 text-gray-500">
        <p class="text-lg">Belum ada foto yang tersedia.</p>
        <p class="text-sm mt-2">Mulai bagikan momen indahmu!</p>
    </div>
@endforelse

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupDropdownHandlers() {
            const dropdownButtons = document.querySelectorAll('[id^="dropdown-btn-"]');

            dropdownButtons.forEach(button => {
                const photoId = button.id.split('-')[2];
                const dropdownMenu = document.getElementById(`dropdown-menu-${photoId}`);

                // Hapus event listener sebelumnya
                button.onmouseenter = null;
                button.onmouseleave = null;
                dropdownMenu.onmouseleave = null;

                button.addEventListener('mouseenter', () => {
                    dropdownMenu.classList.remove('opacity-0', 'invisible');
                });

                button.addEventListener('mouseleave', () => {
                    setTimeout(() => {
                        if (!dropdownMenu.matches(':hover')) {
                            dropdownMenu.classList.add('opacity-0', 'invisible');
                        }
                    }, 200);
                });

                dropdownMenu.addEventListener('mouseleave', () => {
                    dropdownMenu.classList.add('opacity-0', 'invisible');
                });
            });
        }

        // Setup event listener untuk infinite scroll
        function setupInfiniteScrollListener() {
            const photoContainer = document.getElementById('explorePhotoContainer');

            const infiniteScrollHandler = () => {
                // Tunggu proses penambahan foto selesai
                setTimeout(() => {
                    setupDropdownHandlers();
                }, 100);
            };

            // Gunakan event yang sudah ada di infinite scroll
            window.addEventListener('scroll', () => {
                const scrollTrigger = document.body.offsetHeight - 300;
                if ((window.innerHeight + window.scrollY) >= scrollTrigger) {
                    infiniteScrollHandler();
                }
            });
        }

        // Inisialisasi
        setupDropdownHandlers();
        setupInfiniteScrollListener();

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function(e) {
            const dropdownButtons = document.querySelectorAll('[id^="dropdown-btn-"]');
            dropdownButtons.forEach(button => {
                const photoId = button.id.split('-')[2];
                const dropdownMenu = document.getElementById(`dropdown-menu-${photoId}`);

                if (!button.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('opacity-0', 'invisible');
                }
            });
        });
    });

    function downloadPhoto(url, title) {
        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = title + '.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })
            .catch(error => {
                console.error('Download failed:', error);
                alert('Gagal mengunduh gambar');
            });
    }
</script>
