<nav class="fixed top-0 left-0 right-0 z-50 bg-white/50 backdrop-blur-md border-gray-200 shadow-md transition-shadow duration-300">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-3">
        <a href="{{ route('user.index') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
            <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                    class="group-hover:text-blue-600 transition-colors duration-200 ease-in-out">rizz</span></span>
        </a>

        <!-- Search Bar -->
        <div class="flex-grow flex items-center mx-4">
            <input type="text" id="search"
                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search...">
            <a href="{{ route('upload') }}"
                class="ml-4 px-6 py-2 text-blue-600 bg-white hover:bg-blue-600 hover:text-white ease-in-out duration-200 border-2 border-blue-600 rounded-full text-sm font-medium">
                Upload
            </a>
        </div>

        <!-- User Menu -->
        <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button type="button"
                class="flex text-sm bg-gray-800 border border-blue-600 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                data-dropdown-placement="bottom">
                <img class="w-8 h-8 rounded-full" src="{{ asset('storage/' . $user->profile_photo) }}" alt="user photo">
            </button>
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                id="user-dropdown">
                <div class="px-4 py-3">
                    <span class="block text-sm text-gray-900 dark:text-white">{{ $user->username }}</span>
                    <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ $user->email }}</span>
                </div>
                <ul class="py-2" aria-labelledby="user-menu-button">
                    <li>
                        <a href="{{ route('profile') }}"
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
