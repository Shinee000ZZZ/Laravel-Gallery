<!-- Modal Lupa Password -->
<div id="forgot-password-modal" tabindex="-1" aria-hidden="true"
    class="font-monsterrat hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-tl-lg rounded-tr-lg dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Reset Password</h3>
                <button type="button"
                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="forgot-password-modal">
                    <i class='bx bx-x bx-sm'></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="px-4 pb-4 md:px-5 md:pb-5">
                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="reset_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="reset_email"
                            placeholder="Masukkan email Anda"
                            class="bg-gray-50 border @error('email') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required value="{{ old('email') }}" />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Kirim Link Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
