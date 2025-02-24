<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Galerizz</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-montserrat">
    <div class="flex min-h-screen">
        @include('admin.components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->

            @include('admin.components.header', ['title' => 'User Manajemen'])
            <!-- Content -->
            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Content Header -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" placeholder="Cari user..."
                                class="pl-10 pr-4 py-2 border rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        </div>

                        <!-- Tambah Admin Button -->
                        <button onclick="openModal()"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class='bx bx-plus'></i>
                            Tambah Admin
                        </button>
                    </div>

                    <!-- Table -->
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">USER</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">EMAIL</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">ROLE</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">JOINED DATE</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                                alt="{{ $user->username }}">
                                            <span class="font-medium">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->acess_level === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($user->acess_level) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Admin -->
    <div id="adminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Tambah Admin Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>

                <form action="{{ route('admin.users.create-admin') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <input type="text" name="name" required placeholder="Masukkan nama lengkap Anda"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                        <div>
                            <input type="text" name="username" required placeholder="Buat username Anda"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <input type="email" name="email" required placeholder="Masukkan email Anda"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <input type="password" name="password" required placeholder="Buat password Anda"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Tambah Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('adminModal').classList.remove('hidden');
            document.getElementById('adminModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('adminModal').classList.add('hidden');
            document.getElementById('adminModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('adminModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>

</html>
