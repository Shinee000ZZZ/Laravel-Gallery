<!-- Sidebar -->
<div class="fixed top-0 left-0 h-screen w-64 bg-white shadow-xl border-r flex flex-col">
    <div class="flex flex-col h-full">
        <div class="p-6 border-b flex items-center space-x-3">
            <img src="{{ asset('storage/galerizzicon.png') }}" alt="Galerizz Logo" class="h-10 w-10">
            <h1 class="text-xl font-bold text-gray-800">Galerizz Admin</h1>
        </div>
        <nav class="p-4 flex-1 overflow-y-auto">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-3 rounded-lg hover:bg-blue-100 group {{ request()->routeIs('admin.dashboard') ? 'text-gray-700 bg-blue-50' : 'text-gray-600' }}">
                        <i
                            class='bx bx-home mr-3 text-xl {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500' }}'></i>
                        <span
                            class="{{ request()->routeIs('admin.dashboard') ? 'font-medium text-blue-800' : '' }}">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}"
                        class="flex items-center p-3 rounded-lg hover:bg-blue-100 group {{ request()->routeIs('admin.users') ? 'text-gray-700 bg-blue-50' : 'text-gray-600' }}">
                        <i
                            class='bx bx-user mr-3 text-xl {{ request()->routeIs('admin.users') ? 'text-blue-600' : 'text-gray-500' }}'></i>
                        <span
                            class="{{ request()->routeIs('admin.users') ? 'font-medium text-blue-800' : '' }}">User
                            Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.photos') }}"
                        class="flex items-center p-3 rounded-lg hover:bg-blue-100 group {{ request()->routeIs('admin.photos') ? 'text-gray-700 bg-blue-50' : 'text-gray-600' }}">
                        <i
                            class='bx bx-image mr-3 text-xl {{ request()->routeIs('admin.photos') ? 'text-blue-600' : 'text-gray-500' }}'></i>
                        <span
                            class="{{ request()->routeIs('admin.photos') ? 'font-medium text-blue-800' : '' }}">Photo
                            Management</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.reports') }}"
                        class="flex items-center p-3 rounded-lg hover:bg-blue-100 group {{ request()->routeIs('admin.reports') ? 'text-gray-700 bg-blue-50' : 'text-gray-600' }}">
                        <i
                            class='bx bx-flag mr-3 text-xl {{ request()->routeIs('admin.reports') ? 'text-blue-600' : 'text-gray-500' }}'></i>
                        <span
                            class="{{ request()->routeIs('admin.reports') ? 'font-medium text-blue-800' : '' }}">Reported
                            Content</span>
                    </a>
                </li> --}}
            </ul>
        </nav>

        <!-- Logout di bagian bawah -->
        <div class="p-4 border-t">
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center p-3 text-red-600 rounded-lg hover:bg-red-50 group">
                <i class='bx bx-log-out mr-3 text-xl'></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>
