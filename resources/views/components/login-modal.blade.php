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
                    <i class='bx bx-x bx-sm'></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="px-4 pb-4 md:px-5 md:pb-5">
                <form class="space-y-4" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div>
                        <input type="text" name="login" id="login"
                            placeholder="Username, Email, atau Nomor Ponsel"
                            class="bg-gray-50 border @error('login') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required value="{{ old('login') }}" />
                        @error('login')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="password" name="password" id="password"
                            class="bg-gray-50 border @error('password') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan password Anda di sini" required />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Login to your account
                    </button>

                    <div class="flex items-center gap-2">
                        <a href="#" class="text-sm text-blue-600 hover:underline dark:text-blue-500"
                            data-modal-toggle="forgot-password-modal" data-modal-hide="login-modal"
                            data-modal-target="forgot-password-modal">
                            Klik disini
                        </a>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            jika Anda lupa password
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Belum punya akun?
                            <a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                data-modal-hide="login-modal" data-modal-toggle="regist-modal">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
