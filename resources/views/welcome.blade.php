<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galerizz</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <nav class="bg-white border-gray-200 dark:bg-gray-900 fixed top-0 left-0 w-full z-40 shadow font-monsterrat">
        <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="#" class="group flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                        class="group-hover:text-blue-400 transition-colors duration-200 ease-in-out">Rizz</span></span>
            </a>

            <!-- Hamburger Menu -->
            <button data-collapse-toggle="navbar-cta" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 md:hidden"
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
                    <li><a href="#home"
                            class="py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white dark:hover:text-blue-500">Home</a>
                    </li>
                    <li><a href="#about"
                            class="py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white dark:hover:text-blue-500">About</a>
                    </li>
                    <li><a href="{{ route('jelajah') }}"
                            class="py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white dark:hover:text-blue-500">Explore</a>
                    </li>
                </ul>

                <!-- Tombol Auth -->
                <div class="flex space-x-3">
                    <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                        class="text-blue-700 bg-gray-100 border border-blue-700 hover:bg-gray-200 font-medium rounded-full text-sm px-6 py-2">Masuk</button>
                    <button type="button" data-modal-target="regist-modal" data-modal-toggle="regist-modal"
                        class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-full text-sm px-6 py-2">Daftar</button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="hidden md:hidden" id="navbar-cta">
            <ul
                class="flex flex-col items-center font-medium p-4 mt-4 border-t border-gray-100 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 space-y-2">
                <li><a href="#home"
                        class="block w-full py-2 px-3 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Home</a>
                </li>
                <li><a href="#about"
                        class="block w-full py-2 px-3 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">About</a>
                </li>
                <li><a href="{{ route('jelajah') }}"
                        class="block w-full py-2 px-3 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Explore</a>
                </li>
            </ul>

            <!-- Tombol Auth Mobile -->
            <div class="flex flex-col items-center space-y-2 py-4">
                <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                    class="text-blue-700 bg-gray-100 border border-blue-700 hover:bg-gray-200 font-medium rounded-full text-sm px-6 py-2 w-3/4">Masuk</button>
                <button type="button" data-modal-target="regist-modal" data-modal-toggle="regist-modal"
                    class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-full text-sm px-6 py-2 w-3/4">Daftar</button>
            </div>
        </div>
    </nav>


    <div id="fullpage">

        <!-- Section 1 #home -->
        <div class="font-monsterrat section relative flex items-center justify-center h-screen bg-cover bg-blue-800 cursor-default"
            data-anchors="home" style="background-image: url('{{ asset('') }}')"">
            <!-- Text Content -->
            <div class="text-center z-10">
                <h1 class="text-white font-semibold md:text-5xl lg:text-6xl">Explore with your hand</h1>
                <p class="mt-4 text-lg text-gray-300 lg:text-xl">Setiap foto adalah cerita. Mulailah perjalanan Anda
                    untuk <br>
                    menemukan cerita Anda sendiri di Galerizz.</p>
            </div>

            <!-- Tombol Arrow Bawah di Home Section -->
            <div
                class="flex justify-center items-center h-12 w-12 absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-white rounded-full arrow-icon">
                <a href="#regist">
                    <box-icon name="chevron-down" class="text-white text-4xl"></box-icon>
                </a>
            </div>
        </div>

        <!-- Section 3 -->
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
            <div
                class="flex justify-center items-center h-12 w-12 absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-white rounded-full arrow-icon">
                <a href="#home">
                    <box-icon name="chevron-up" class="text-white text-4xl"></box-icon>
                </a>
            </div>
        </div>

    </div>

    <!-- Modal untuk login -->
    <div id="login-modal" tabindex="-1" aria-hidden="true"
        class="font-monsterrat hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-tl-lg rounded-tr-lg dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Masuk ke Galerizz</h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="login-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form class="space-y-4" action="{{ route('login') }}" method="POST">
                        @csrf
                        <!-- Form fields -->
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="name@company.com" required />
                        </div>
                        <div>
                            <label for="username"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                username</label>
                            <input type="text" name="username" id="username"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Masukkan username Anda di sini" required />
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                password</label>
                            <input type="password" name="password" id="password"
                                placeholder="Masukkan password Anda di sini"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required />
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login
                            to your account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Regist modal -->
    <div id="regist-modal" tabindex="-1" aria-hidden="true"
        class="font-monsterrat hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Daftarkan diri Anda ke Galerizz
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="regist-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->

                <div class="p-4 md:p-5">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form class="space-y-4" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Masukkan nama lengkap Anda di sini" required />
                        </div>
                        <div>
                            <label for="username"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                username</label>
                            <input type="text" name="username" id="username"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Buat username Anda" required />
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="name@company.com" required />
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                password</label>
                            <input type="password" name="password" id="password"
                                placeholder="Masukkan password Anda di sini"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required />
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Daftarkan
                            diri Anda</button>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Sudah punya akun? <a href=""
                                class="text-blue-700 hover:underline dark:text-blue-500">Masuk ke Galerizz</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
