<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
<link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<title>Home</title>
</head>

<body class="bg-gray-100 font-monsterrat">
    <div class="max-w-screen-xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

            <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Profile Photo -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <img id="profilePreview" src="{{ asset('storage/' . $user->profile_photo) }}"
                            alt="Profile Preview"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">

                        <label for="profile_photo"
                            class="absolute bottom-0 right-0 bg-blue-600 text-white px-1 rounded-full cursor-pointer hover:bg-blue-700 transition">
                            <i class='bx bx-edit text-xl'></i>
                            <input type="file" id="profile_photo" name="profile_photo" class="hidden"
                                accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>
                    <p class="text-sm text-gray-500">Click to change profile photo</p>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('profile', $user->username) }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('profilePreview');
                preview.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>

</html>
