<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galerizz</title>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .navbar-hidden {
            transform: translateY(-100%);
            transition: transform 0.5s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .navbar-visible {
            transform: translateY(0);
            transition: transform 1.2s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 1;
        }
    </style>
</head>

<body>

    <nav
        class="absolute top-0 left-0 w-full z-40 font-monsterrat bg-transparent transition-all duration-300 navbar-visible">
        <div class="max-w-screen-xl flex items-center justify-between mx-auto p-6">
            <!-- Logo -->
            <a href="#home" class="group flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap text-white">
                    Gale<span class="group-hover:text-blue-400 transition-colors duration-200 ease-in-out">rizz</span>
                </span>
            </a>

            <!-- Hamburger Menu -->
            <button data-collapse-toggle="navbar-cta" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white/80 rounded-lg hover:bg-white/10 focus:outline-none md:hidden backdrop-blur-sm"
                aria-controls="navbar-cta" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <ul class="flex space-x-8 font-medium">
                    <li>
                        <a href="#about" class="py-2 px-3 text-white/90 hover:text-white transition-colors">About</a>
                    </li>
                    <li>
                        <a href="{{ route('jelajah') }}"
                            class="py-2 px-3 text-white/90 hover:text-white transition-colors">Explore</a>
                    </li>
                </ul>

                <!-- Tombol Auth -->
                <div class="flex space-x-3">
                    <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                        class="text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-2 transition-all duration-200">
                        Masuk
                    </button>
                    <button type="button" data-modal-target="regist-modal" data-modal-toggle="regist-modal"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-full text-sm px-6 py-2 transition-all duration-200">
                        Daftar
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="hidden md:hidden" id="navbar-cta">
            <ul class="flex flex-col items-center font-medium p-4 mt-4 space-y-2 backdrop-blur-sm bg-black/30">
                <li>
                    <a href="#home"
                        class="block w-full py-2 px-3 text-white/90 hover:text-white rounded-lg hover:bg-white/10">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#about"
                        class="block w-full py-2 px-3 text-white/90 hover:text-white rounded-lg hover:bg-white/10">
                        About
                    </a>
                </li>
                <li>
                    <a href="{{ route('jelajah') }}"
                        class="block w-full py-2 px-3 text-white/90 hover:text-white rounded-lg hover:bg-white/10">
                        Explore
                    </a>
                </li>
            </ul>

            <!-- Tombol Auth Mobile -->
            <div class="flex flex-col items-center space-y-2 py-4 backdrop-blur-sm bg-black/30">
                <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                    class="text-white bg-white/10 hover:bg-white/20 border border-white/30 font-medium rounded-full text-sm px-6 py-2 w-3/4 transition-all duration-200">
                    Masuk
                </button>
                <button type="button" data-modal-target="regist-modal" data-modal-toggle="regist-modal"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-full text-sm px-6 py-2 w-3/4 transition-all duration-200">
                    Daftar
                </button>
            </div>
        </div>
    </nav>


    <div id="fullpage">

        <!-- Section 1 / Home -->
        <div class="font-monsterrat section relative flex items-center justify-center h-screen bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 overflow-hidden cursor-default"
            data-anchors="home">

            <!-- Main Content -->
            <div class="relative text-center z-10 px-4 max-w-5xl mx-auto">
                <h1 class="text-white font-bold text-3xl md:text-5xl lg:text-7xl mb-4 md:mb-6 tracking-tight">
                    Explore with your hand
                    <span
                        class="block mt-2 text-2xl md:text-4xl lg:text-6xl bg-gradient-to-r from-blue-200 to-white bg-clip-text text-transparent">
                        Discover your story
                    </span>
                </h1>
                <p
                    class="mt-4 md:mt-6 text-base md:text-lg lg:text-xl text-gray-300/90 max-w-2xl mx-auto leading-relaxed">
                    Setiap foto adalah cerita. Mulailah perjalanan Anda untuk
                    menemukan cerita Anda sendiri di Galerizz.
                </p>
            </div>

            <!-- Arrow Button -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
                <a href="#about"
                    class="flex justify-center items-center h-12 w-12 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur-sm transition-all duration-200 border border-white/30 group">
                    <i class='bx bx-chevron-down class="mb-4 text-white text-2xl group-hover:translate-y-1 transition-transform'
                        style='color:#ffffff'></i>
                </a>
            </div>
        </div>

        <!-- Section 2 / About Us -->
        <div data-anchors="about" id="section-2"
            class="font-monsterrat section relative flex items-center justify-center h-screen bg-gradient-to-br from-blue-800 via-blue-900 to-blue-950 text-white overflow-hidden">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Cerita Visual, Kreativitas Tanpa Batas
                    </h2>
                    <p class="text-lg text-gray-200 max-w-2xl mx-auto">
                        Galerizz: Platform untuk Mengeksplorasi dan Membagikan Kisah Visual Anda
                    </p>
                </div>

                <div class="bg-blue-900/20 border border-white/20 rounded-lg p-6 shadow-lg mb-9">
                    <div class="flex items-start space-x-8">
                        <!-- Code Visualization -->
                        <div
                            class="w-1/2 bg-blue-900/40 border border-white/20 rounded-lg p-6 font-mono text-sm leading-relaxed">
                            <div><span class="text-[#fffc32]">class</span> <span class="text-[#4EC9B0]">Galerizz</span>
                                {</div>

                            <div class="pl-4"><span class="text-[#fffc32]">public</span> <span
                                    class="text-[#af88f1]">$developer</span> = <span class="text-[#CE9178]">"Sultan
                                    Syaeful Millah"</span>;</div>
                            <div class="pl-4"><span class="text-[#fffc32]">public</span> <span
                                    class="text-[#af88f1]">$status</span> = <span class="text-[#CE9178]">"Student &
                                    Web
                                    Developer"</span>;</div>
                            <br>

                            <div class="pl-4"><span class="text-[#fffc32]">public function</span> showcase() {</div>
                            <div class="pl-8"><span class="text-[#af88f1]">$images</span> =
                                Gallery::latest()->get();</div>
                            <div class="pl-8"><span class="text-[#fffc32]">return</span> view(<span
                                    class="text-[#CE9178]">'galerizz.index'</span>, compact(<span
                                    class="text-[#CE9178]">'images'</span>));</div>
                            <div class="pl-4">}</div>

                            <br>
                            <div class="pl-4"><span class="text-[#fffc32]">public function</span> about() {</div>
                            <div class="pl-8"><span class="text-[#fffc32]">return</span> <span
                                    class="text-[#CE9178]">"Love anime and music"</span>;</div>
                            <div class="pl-4">}</div>
                            <div>}</div>
                        </div>

                        <!-- Content -->
                        <div class="w-1/2 space-y-6">
                            <div
                                class="bg-white/10 p-5 rounded-lg backdrop-blur-sm border border-white/20 hover:border-white/40 transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-blue-500/20 text-blue-300 py-2 px-3 rounded-full mr-4">
                                        <i class='bx bxs-bulb text-xl'></i> <!-- Ikon ide (lampu) -->
                                    </div>
                                    <h3 class="text-xl font-bold">Misi Kami</h3>
                                </div>
                                <p class="text-gray-200 text-sm">
                                    Memberdayakan kreator untuk mengubah momen pribadi menjadi karya visual yang
                                    menginspirasi.
                                    Kami percaya setiap foto memiliki cerita unik yang layak dibagikan dan dieksplorasi.
                                </p>
                            </div>

                            <div
                                class="bg-white/10 p-5 rounded-lg backdrop-blur-sm border border-white/20 hover:border-white/40 transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-blue-500/20 text-purple-300 py-2 px-3 rounded-full mr-4">
                                        <i class='bx bx-globe text-xl'></i> <!-- Ikon globe (global connection) -->
                                    </div>
                                    <h3 class="text-xl font-bold">Visi Kami</h3>
                                </div>
                                <p class="text-gray-200 text-sm">
                                    Menjadi platform global terdepan yang menghubungkan para kreator visual, di mana
                                    setiap
                                    gambar mampu bercerita, menginspirasi, dan menciptakan koneksi lintas budaya.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Arrow Button -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
                <a href="#regist"
                    class="flex justify-center items-center h-12 w-12 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur-sm transition-all duration-200 border border-white/30 group">
                    <i
                        class="bx bx-chevron-down text-white text-2xl group-hover:translate-y-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Section 3 / Regist -->
        <div class="font-monsterrat section bg-cover bg-center" data-anchors="regist"
            style="background-image: url('{{ asset('storage/bgregist.png') }}');" id="section-3">
            <div class="flex items-center justify-center h-screen bg-gray-900 bg-opacity-80 px-4">
                <div
                    class="flex flex-col md:flex-row items-center justify-between max-w-5xl w-full space-y-8 md:space-y-0">
                    <!-- Teks di sebelah kiri -->
                    <div class="text-center md:text-left text-white md:pr-8">
                        <h1 class="text-3xl md:text-4xl lg:text-6xl">
                            Daftar untuk<br>mengexplore ide baru
                        </h1>
                    </div>

                    <!-- Form di sebelah kanan -->
                    <div class="bg-white bg-opacity-95 p-8 rounded-lg shadow-lg max-w-sm w-full">
                        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6 sm:">Daftarkan diri Anda di
                            Galerizz
                        </h2>
                        <p class="text-center text-lg text-gray-600 mb-8">Dapatkan ide-ide baru untuk dicoba</p>

                        <form action="{{ route('register') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Email">
                            </div>
                            <div>
                                <input type="text" id="name" name="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Name">
                            </div>
                            <div>
                                <input type="text" id="username" name="username"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Username">
                            </div>
                            <div>
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Password">
                            </div>
                            <button type="submit"
                                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                                Lanjutkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tombol Arrow Atas di Section Registrasi -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
                <a href="#home"
                    class="flex justify-center items-center h-12 w-12 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur-sm transition-all duration-200 border border-white/30 group animate-bounce">
                    <i
                        class="bx bx-chevron-up text-white text-2xl group-hover:-translate-y-1 transition-transform"></i>
                </a>
            </div>
        </div>

    </div>

    @include('components.login-modal')

    @include('components.regist-modal')

    @include('components.forgot-password')

    @include('components.reset-password')

    @if (session('status'))
        <div id="toast-success"
            class="fixed top-5 right-5 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
            role="alert">
            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.5 9.5 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{ session('status') }}</div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast-success');
                if (toast) {
                    setTimeout(() => {
                        toast.classList.add('animate-fade-out');
                        setTimeout(() => {
                            toast.remove();
                        }, 1000);
                    }, 5000);
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('nav');
            const header = document.querySelector('#home');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        navbar.classList.remove('-translate-y-full', 'opacity-0');
                    } else {
                        navbar.classList.add('-translate-y-full', 'opacity-0');
                    }
                });
            }, {
                threshold: 0.1
            });

            observer.observe(header);
        });
    </script>


    @if (session('showLoginModal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginModal = document.getElementById('login-modal');
                const modalInstance = new Modal(loginModal);
                modalInstance.show();
            });
        </script>
    @endif

    @if ($errors->has('login'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginModal = document.getElementById('login-modal');
                const modalInstance = new Modal(loginModal);
                modalInstance.show();
            });
        </script>
    @endif

    @if (request()->has('email'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resetPasswordModal = document.getElementById('reset-password-modal');
                const modalInstance = new Modal(resetPasswordModal);
                modalInstance.show();
            });
        </script>
    @endif
</body>

</html>
