<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="{{ route('user.index') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
                <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                        class="group-hover:text-blue-400 transition-colors duration-200 ease-in-out">rizz</span></span>
            </a>

            <!-- Search Bar untuk Desktop -->
            <div class="flex-grow flex items-center mx-4">
                <input type="text" id="search"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search...">

                <!-- Tombol Upload -->
                <a href="{{ Route('upload') }}"
                    class="ml-4 px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-full text-sm font-medium">
                    Upload
                </a>
            </div>

            <!-- Menu dan Profil -->
            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <!-- Dropdown Menu User -->
                <button type="button"
                    class="flex text-sm bg-gray-800 border border-blue-600 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src=" {{ asset('storage/' . $user->profile_photo) }}"
                        alt="user photo">
                </button>

                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">{{ $user->username }}</span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ $user->email }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="{{ Route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                                out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Responsif: Tampilan Search Bar di bawah logo dan profil -->
    <div class="md:hidden flex flex-col items-center space-y-4 mt-4">
        <input type="text" id="search-mobile"
            class="block w-72 px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Search...">
    </div>

    <!-- Profile Content -->
    <div class="max-w-screen-xl mx-auto p-4">
        <!-- Profile Photo in the Center -->
        <div class="flex justify-center items-center mb-4">
            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo"
                class="w-32 h-32 rounded-full border-4 border-blue-500">
        </div>

        <!-- Username -->
        <div class="flex justify-center">
            <h2 class="text-2xl font-semibold">{{ $user->username }}</h2>
        </div>

        <!-- Email -->
        <div class="flex justify-center mb-4">
            <p class="text-gray-600">{{ $user->email }}</p>
        </div>
    </div>
</body>

</html>
