<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>

<body>

    <!-- Navbar -->
    <nav class="bg-white border-gray-200 dark:bg-gray-900 fixed top-0 left-0 w-full z-40 shadow font-monsterrat">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                        class="group-hover:text-blue-400 transition-colors duration-200 ease-in-out">Rizz</span></span>
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


</body>

</html>
