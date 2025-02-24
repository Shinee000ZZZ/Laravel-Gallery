@props(['title'])

<header class="bg-white shadow-sm border-b p-6">
    <div class="flex items-center justify-between">
        <!-- Left side: Title -->
        <h1 class="text-2xl font-semibold text-gray-800">{{ $title }}</h1>

        <!-- Right side: Just Profile Photo -->
        <div class="relative group">
            <img src="{{ asset('storage/' . (Auth::user()->profile_photo ?? 'default.jpg')) }}" alt="Profile"
                class="w-10 h-10 rounded-full object-cover cursor-pointer">

            <!-- Dropdown Menu -->
            <div
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                <div class="px-4 py-3 border-b">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->username }}</p>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Profile
                </a>
                <form action="{{ route('logout') }}" method="GET">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
