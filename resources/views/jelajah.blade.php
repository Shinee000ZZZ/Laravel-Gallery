<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Explore</title>

    <style>
        #photo-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #photo-detail-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .modal-backdrop {
            transition: opacity 0.3s ease-out;
            opacity: 0;
        }

        .modal-backdrop.show {
            opacity: 1;
        }

        .modal-content {
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            transform: perspective(1000px) rotateX(-60deg);
            transform-origin: 50% 0;
            opacity: 0;
            backface-visibility: hidden;
        }

        .modal-content.show {
            transform: perspective(1000px) rotateX(0deg);
            opacity: 1;
        }

        /* Add some depth to the modal content */
        .modal-inner {
            transform-style: preserve-3d;
        }
    </style>

</head>

<body>

    <!-- Navbar -->
    <nav class="bg-white border-gray-200 dark:bg-gray-900 fixed top-0 left-0 w-full z-40 shadow font-monsterrat">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                        class="group-hover:text-blue-600 transition-colors duration-200 ease-in-out">rizz</span></span>
            </a>

            <!-- Search Bar Desktop -->
            <div class="hidden md:flex items-center">
                <input type="text" id="search"
                    class="lg:w-[600px] md:w-[400px] w-full px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search...">
            </div>


            <!-- Hamburger Menu -->
            <button data-collapse-toggle="navbar-cta" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 md:hidden">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                <ul class="flex space-x-6 font-medium">
                    <li><a href="{{ Route('welcome') }}"
                            class="py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white dark:hover:text-blue-500">Home</a>
                    </li>
                    <li><a href="{{ Route('jelajah') }}"
                            class="py-2 px-3 text-blue-700 hover:text-blue-500 dark:text-white dark:hover:text-blue-200">Explore</a>
                    </li>
                </ul>

                <!-- Tombol Auth (Desktop) -->
                <div class="flex space-x-3">
                    <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                        class="text-blue-700 bg-gray-100 border border-blue-700 hover:bg-gray-200 font-medium rounded-full text-sm px-6 py-2">Masuk</button>
                    <button type="button" data-modal-target="regist-modal" data-modal-toggle="regist-modal"
                        class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-full text-sm px-6 py-2">Daftar</button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="hidden w-full md:hidden" id="navbar-cta">
            <div class="p-4">
                <!-- Search Bar Mobile -->
                <input type="text" id="search-mobile"
                    class="w-full px-4 py-2 mb-4 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search...">

                <ul
                    class="flex flex-col items-start font-medium space-y-2 border-t pt-4 border-gray-100 dark:border-gray-700">
                    <li><a href="{{ Route('welcome') }}"
                            class="block py-2 px-3 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Home</a>
                    </li>
                    <li><a href="{{ Route('jelajah') }}"
                            class="block py-2 px-3 text-blue-900 hover:bg-blue-100 dark:text-white dark:hover:bg-blue-700">Explore</a>
                    </li>
                </ul>

                <!-- Tombol Auth (Mobile) -->
                <div class="flex flex-col space-y-2 mt-4">
                    <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                        class="text-blue-700 bg-gray-100 border border-blue-700 hover:bg-gray-200 font-medium rounded-full text-sm px-6 py-2">Masuk</button>
                    <button type="button" data-modal-target="regist-modal" data-modal-toggle="regist-modal"
                        class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-full text-sm px-6 py-2">Daftar</button>
                </div>
            </div>
        </div>
    </nav>


    <!-- Gallery -->
    <div class="max-w-screen-xl mx-auto p-4 mt-20">
        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" id="explorePhotoContainer">
            @include('partials.explore-photo-grid', ['photos' => $photos])
        </div>
    </div>

    @include('components.login-modal')
    @include('components.regist-modal')
    @include('components.forgot-password')
    @include('components.reset-password')

    <!-- Modal Detail Foto -->
    @if ($selectedPhoto)
        <div id="photo-detail-modal" class="fixed inset-0 z-50 overflow-y-auto {{ $selectedPhoto ? '' : 'hidden' }}">
            <div class="min-h-screen px-4 flex items-center justify-center">
                <div class="fixed inset-0 bg-black/70 transition-opacity" onclick="closePhotoDetailModal()"></div>

                <div class="inline-block w-full max-w-6xl my-8 relative z-50">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-xl grid grid-cols-1 md:grid-cols-2 gap-0">

                        <!-- Photo Section -->
                        <div class="bg-black relative">
                            <!-- Dropdown Menu Titik Tiga -->
                            <div class="absolute top-4 right-4 z-10 group">
                                <button
                                    class="bg-white/50 rounded-full px-1 hover:bg-white/70 transition-all duration-300">
                                    <i class='bx bx-dots-horizontal-rounded text-xl text-gray-700'></i>
                                </button>

                                <div
                                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <button id="download-photo-btn"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2">
                                        <i class='bx bx-download'></i>
                                        <span>Download</span>
                                    </button>
                                    <button id="view-fullsize-btn"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2">
                                        <i class='bx bx-fullscreen'></i>
                                        <span>Lihat Full Size</span>
                                    </button>
                                </div>
                            </div>

                            <div id="photo-container" class="w-full aspect-square">
                                <img id="photo-detail-image" src="{{ asset('storage/' . $selectedPhoto->image_path) }}"
                                    class="absolute max-w-full max-h-full object-cover top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            </div>
                        </div>

                        <!-- Info and Comments Side -->
                        <div class="flex flex-col h-[80vh] relative">
                            <!-- Close Button -->
                            <button onclick="closePhotoDetailModal()"
                                class="absolute top-2 right-2 z-10 text-gray-500 hover:text-gray-700 bg-white/50 rounded-full p-2"
                                id="close-modal-btn">
                                <i class='bx bx-x text-2xl'></i>
                            </button>

                            <!-- Container Tanpa Scroll -->
                            <div class="p-6 border-b flex-shrink-0">
                                <!-- User Info -->
                                <div class="flex items-center space-x-3 mb-4">
                                    <img src="{{ $selectedPhoto->user->profile_photo ? asset('storage/' . $selectedPhoto->user->profile_photo) : asset('img/default-avatar.png') }}"
                                        class="w-10 h-10 rounded-full object-cover" alt="Profile photo">
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $selectedPhoto->user->username }}
                                    </span>
                                </div>

                                <!-- Photo Details -->
                                <h2 class="text-xl font-bold mb-2 text-gray-900">
                                    {{ $selectedPhoto->title ?? 'No Title' }}
                                </h2>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ $selectedPhoto->description ?? 'No Description' }}
                                </p>
                            </div>

                            <!-- Comments Section dengan Scroll -->
                            <div class="flex-1 overflow-y-auto px-6" id="comments-container">
                                @if (count($selectedPhoto->comments) > 0)
                                    <button id="toggle-comments"
                                        class="w-full py-3 text-blue-600 hover:bg-gray-50 transition-colors border-b">
                                        Lihat Komentar ({{ count($selectedPhoto->comments) }})
                                    </button>

                                    <div id="comments-section" class="hidden">
                                        <div class="p-6">
                                            @foreach ($selectedPhoto->comments as $comment)
                                                <div class="mb-4 pb-4 border-b last:border-b-0">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <img src="{{ $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : asset('img/default-avatar.png') }}"
                                                            class="w-8 h-8 rounded-full object-cover">
                                                        <span class="font-medium text-sm text-gray-800">
                                                            {{ $comment->user->username }}
                                                        </span>
                                                    </div>
                                                    <p class="text-gray-600 text-sm">
                                                        {{ $comment->comment_text }}
                                                    </p>
                                                    <span class="text-xs text-gray-500 mt-1">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-500">
                                        <i class='bx bx-message-rounded-dots text-4xl'></i>
                                        <p class="ml-2">Belum ada komentar</p>
                                    </div>
                                @endif


                            </div>

                            <div class="border-t bg-white p-4 flex flex-col">
                                <div
                                    class="flex items-center space-x-2 text-gray-700 hover:text-red-500 transition-all duration-300 mb-2">
                                    <button type="button" class="interaction-trigger flex items-center space-x-2"
                                        data-interaction="like">
                                        <i class='bx bx-heart text-xl'></i>
                                        <span>{{ $totalLikes }} suka</span>
                                    </button>
                                </div>

                                @if ($lastLikeUser)
                                    <p class="text-sm text-gray-600">
                                        Disukai oleh <strong>{{ $lastLikeUser->username }}</strong>
                                        @if ($totalLikes > 1)
                                            dan {{ $totalLikes - 1 }} lainnya
                                        @endif
                                    </p>
                                @endif
                            </div>

                            <!-- Fixed Comment Input Section -->
                            <div class="bg-white p-4 flex-shrink-0">
                                <form class="interaction-trigger" data-interaction="comment">
                                    @csrf
                                    <input type="hidden" name="photo_id" value="{{ $selectedPhoto->photo_id }}">
                                    <div class="flex space-x-2">
                                        <textarea name="content"
                                            class="flex-1 p-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                            placeholder="Tulis komentar..." rows="1"></textarea>
                                        <button type="button"
                                            class="px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center justify-center">
                                            <i class='bx bx-send text-xl'></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Fullsize --}}
    <div id="fullsize-modal"
        class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm transition-all duration-400 flex items-center justify-center p-8">
        <div class="relative w-full h-full max-w-[90vw] max-h-[90vh] bg-transparent flex items-center justify-center">
            <img id="fullsize-image" class="absolute max-w-full max-h-full object-contain rounded-md shadow-2xl"
                style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
        </div>
        <button id="close-fullsize-modal"
            class="absolute top-4 right-4 text-white text-3xl transition-all duration-300">
            <i class='bx bx-x'></i>
        </button>
    </div>

    {{-- Modal Warning --}}
    <div id="login-warning-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <!-- Background overlay dengan animasi -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 modal-backdrop"></div>

            <!-- Modal panel dengan animasi flip -->
            <div class="relative w-full max-w-md mx-4 modal-content">
                <div class="bg-white rounded-lg overflow-hidden shadow-xl modal-inner">
                    <!-- Close button -->
                    <button type="button" data-modal-hide="login-warning-modal" id="cancel-login"
                        class="absolute top-2 right-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <span class="sr-only">Close</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Modal content -->
                    <div class="px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex flex-col items-center">
                            <!-- Warning icon -->
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>

                            <!-- Text content -->
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900 mt-2 mb-2">
                                    Anda Perlu Login
                                </h3>
                                <p class="text-sm text-gray-500 mb-3">
                                    Untuk melakukan interaksi, Anda harus login <br> terlebih dahulu.
                                </p>
                            </div>

                            <!-- Login button -->
                            <div class="flex justify-center w-full">
                                <button type="button" data-modal-toggle="login-modal"
                                    data-modal-hide="login-warning-modal"
                                    class="inline-flex justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Login Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all required elements
            const photoDetailModal = document.getElementById('photo-detail-modal');
            const loginWarningModal = document.getElementById('login-warning-modal');
            const modalBackdrop = loginWarningModal ? loginWarningModal.querySelector('.modal-backdrop') : null;
            const modalContent = loginWarningModal ? loginWarningModal.querySelector('.modal-content') : null;
            const interactionTriggers = document.querySelectorAll('.interaction-trigger');
            const cancelLoginBtn = document.getElementById('cancel-login');
            const photoContainer = document.getElementById('photo-container');
            const photoDetailImage = document.getElementById('photo-detail-image');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const modalOverlay = document.querySelector('.fixed.inset-0.bg-black\\/70');
            const toggleCommentsBtn = document.getElementById('toggle-comments');
            const commentsSection = document.getElementById('comments-section');
            const fullsizeModal = document.getElementById('fullsize-modal');
            const fullsizeImage = document.getElementById('fullsize-image');
            const closeFullsizeModal = document.getElementById('close-fullsize-modal');
            const viewFullsizeBtn = document.getElementById('view-fullsize-btn');

            function openFullsizeWithAnimation(imageSrc) {
                // Reset posisi dan skala
                fullsizeImage.style.transform = 'translate(-50%, -50%) scale(0.7)';
                fullsizeImage.style.opacity = '0';

                // Tampilkan modal
                fullsizeModal.classList.remove('hidden');

                // Tunggu sejenak untuk memastikan elemen sudah di-render
                setTimeout(() => {
                    // Animasi masuk
                    fullsizeModal.classList.add('bg-opacity-80');
                    fullsizeImage.src = imageSrc;

                    // Animasi scaling dan opacity
                    fullsizeImage.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    fullsizeImage.style.transform = 'translate(-50%, -50%) scale(1)';
                    fullsizeImage.style.opacity = '1';
                }, 10);
            }

            // Fungsi untuk menutup full size dengan animasi
            function closeFullsizeWithAnimation() {
                // Animasi keluar
                fullsizeImage.style.transform = 'translate(-50%, -50%) scale(0.7)';
                fullsizeImage.style.opacity = '0';
                fullsizeModal.classList.remove('bg-opacity-80');

                // Sembunyikan modal setelah animasi selesai
                setTimeout(() => {
                    fullsizeModal.classList.add('hidden');
                }, 400);
            }

            // Event listener untuk membuka full size
            if (viewFullsizeBtn) {
                viewFullsizeBtn.addEventListener('click', function() {
                    const currentImageSrc = document.getElementById('photo-detail-image').src;
                    openFullsizeWithAnimation(currentImageSrc);
                });
            }

            // Event listener untuk menutup full size
            if (closeFullsizeModal) {
                closeFullsizeModal.addEventListener('click', closeFullsizeWithAnimation);
            }

            // Tutup modal jika klik di luar gambar
            fullsizeModal.addEventListener('click', function(e) {
                if (e.target === fullsizeModal) {
                    closeFullsizeWithAnimation();
                }
            });

            // Tambahkan dukungan tombol ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !fullsizeModal.classList.contains('hidden')) {
                    closeFullsizeWithAnimation();
                }
            });

            // Auto-resize textarea
            const commentTextarea = document.querySelector('textarea[name="content"]');
            if (commentTextarea) {
                commentTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            if (toggleCommentsBtn && commentsSection) {
                // Pastikan jumlah komentar sudah dihitung
                const commentCount = document.querySelectorAll('#comments-section .mb-4').length;

                toggleCommentsBtn.addEventListener('click', function() {
                    // Toggle kelas hidden untuk menampilkan/sembunyikan komentar
                    commentsSection.classList.toggle('hidden');

                    // Perbarui teks tombol sesuai keadaan komentar
                    toggleCommentsBtn.textContent = commentsSection.classList.contains('hidden') ?
                        `Lihat Komentar (${commentCount})` :
                        'Sembunyikan Komentar';

                    // Scroll ke atas saat komentar dibuka
                    if (!commentsSection.classList.contains('hidden')) {
                        commentsSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            } else {
                console.warn('Toggle Comments button or Comments Section not found.');
            }

            // Download foto
            const downloadPhotoBtn = document.getElementById('download-photo-btn');
            if (downloadPhotoBtn && '{{ $selectedPhoto?->image_path }}') {
                downloadPhotoBtn.addEventListener('click', () => {
                    const imageUrl =
                        '{{ $selectedPhoto ? asset('storage/' . $selectedPhoto->image_path) : '' }}';
                    const filename = '{{ $selectedPhoto?->title ?? 'photo' }}' + '.jpg';

                    if (imageUrl) {
                        downloadImage(imageUrl, filename);
                    }
                });
            }

            function downloadImage(imageUrl, filename) {
                fetch(imageUrl)
                    .then(response => response.blob())
                    .then(blob => {
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    })
                    .catch(error => {
                        console.error('Download failed:', error);
                        alert('Gagal mengunduh gambar');
                    });
            }

            // Function to update URL to /explore
            function updateUrlToExplore() {
                const baseUrl = window.location.origin + '/explore';
                history.pushState({}, '', baseUrl);
            }

            // Function to close photo detail modal and update URL
            function closePhotoDetailModal() {
                if (photoDetailModal) {
                    photoDetailModal.classList.add('hidden');
                    updateUrlToExplore();
                }
            }

            // Function to open photo detail modal and update URL
            function openPhotoDetail(photoId) {
                if (photoDetailModal) {
                    photoDetailModal.classList.remove('hidden');
                    const newUrl = window.location.origin + `/explore/photo/${photoId}`;
                    history.pushState({}, '', newUrl);
                    handlePhotoSizing(); // Sesuaikan ukuran foto saat modal terbuka
                }
            }

            // Function to handle photo sizing
            function handlePhotoSizing() {
                if (!photoDetailImage || !photoContainer) return;

                const containerWidth = photoContainer.clientWidth;
                const containerHeight = photoContainer.clientHeight;
                const containerAspect = containerWidth / containerHeight;

                const img = new Image();
                img.src = photoDetailImage.src;

                img.onload = function() {
                    const imageAspect = img.naturalWidth / img.naturalHeight;

                    // Reset styles
                    photoDetailImage.style.width = '';
                    photoDetailImage.style.height = '';
                    photoDetailImage.style.maxWidth = '100%';
                    photoDetailImage.style.maxHeight = '100%';
                    photoDetailImage.style.objectFit = 'cover'; // Mencegah black bar
                    photoDetailImage.style.objectPosition = 'center';

                    // Jika gambar lebih kecil dari container, isi penuh tanpa black bar
                    if (img.naturalWidth < containerWidth && img.naturalHeight < containerHeight) {
                        photoDetailImage.style.width = '100%';
                        photoDetailImage.style.height = '100%';
                    }
                    // Jika aspek rasio gambar lebih besar dari container, isi penuh width
                    else if (imageAspect > containerAspect) {
                        photoDetailImage.style.width = '100%';
                        photoDetailImage.style.height = 'auto';
                    }
                    // Jika aspek rasio gambar lebih kecil dari container, isi penuh height
                    else {
                        photoDetailImage.style.width = 'auto';
                        photoDetailImage.style.height = '100%';
                    }

                    // Center image
                    photoDetailImage.style.position = 'absolute';
                    photoDetailImage.style.left = '50%';
                    photoDetailImage.style.top = '50%';
                    photoDetailImage.style.transform = 'translate(-50%, -50%)';
                };
            }

            // Handle closing modal via close button
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closePhotoDetailModal);
            }

            // Handle closing modal via clicking overlay
            if (modalOverlay) {
                modalOverlay.addEventListener('click', function(e) {
                    if (e.target === modalOverlay) {
                        closePhotoDetailModal();
                    }
                });
            }

            // Handle clicking on photo thumbnails
            document.querySelectorAll('.photo-thumbnail').forEach(photo => {
                photo.addEventListener('click', function(event) {
                    event.preventDefault();
                    const photoId = this.getAttribute(
                        'data-photo-id'); // Pastikan ada `data-photo-id` pada elemen foto
                    openPhotoDetail(photoId);
                });
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                if (photoDetailModal && !photoDetailModal.classList.contains('hidden')) {
                    closePhotoDetailModal();
                }
            });

            // Function to show login warning modal
            function showLoginWarning(e) {
                e.preventDefault();
                if (photoDetailModal) {
                    photoDetailModal.classList.add('hidden');
                }
                if (loginWarningModal) {
                    loginWarningModal.classList.remove('hidden');

                    requestAnimationFrame(() => {
                        if (modalBackdrop) modalBackdrop.classList.add('show');
                        if (modalContent) modalContent.classList.add('show');
                    });
                }
            }

            // Function to close login warning modal
            function closeWarningModal() {
                if (modalBackdrop) modalBackdrop.classList.remove('show');
                if (modalContent) modalContent.classList.remove('show');

                setTimeout(() => {
                    if (loginWarningModal) loginWarningModal.classList.add('hidden');
                    if (photoDetailModal && !photoDetailModal.classList.contains('hidden')) {
                        photoDetailModal.classList.remove('hidden');
                    }
                }, 600);
            }

            // Add event listeners to interaction triggers (like, comment, etc.)
            interactionTriggers.forEach(trigger => {
                trigger.addEventListener('click', showLoginWarning);
            });

            if (cancelLoginBtn) {
                cancelLoginBtn.addEventListener('click', closeWarningModal);
            }

            // Handle clicking outside warning modal
            window.addEventListener('click', function(e) {
                if (e.target.classList.contains('bg-gray-500') && e.target.closest(
                        '#login-warning-modal')) {
                    closeWarningModal();
                }
            });

            // Handle ESC key for both modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (loginWarningModal && !loginWarningModal.classList.contains('hidden')) {
                        closeWarningModal();
                    }
                    if (photoDetailModal && !photoDetailModal.classList.contains('hidden')) {
                        closePhotoDetailModal();
                    }
                }
            });

            // Add event listener for proceed to login button
            const loginButton = document.querySelector(
                '[data-modal-toggle="login-modal"][data-modal-hide="login-warning-modal"]'
            );
            if (loginButton) {
                loginButton.addEventListener('click', function() {
                    closeWarningModal();
                });
            }

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(handlePhotoSizing, 100);
            });

            // Initialize photo sizing if modal is open
            if (photoDetailModal && !photoDetailModal.classList.contains('hidden')) {
                handlePhotoSizing();
            }
        });
    </script>

</body>

</html>
