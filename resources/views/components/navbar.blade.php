<nav
    class="fixed top-0 left-0 right-0 z-50 bg-white/50 backdrop-blur-md border-gray-200 shadow-md transition-shadow duration-300">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-3">
        @if (Auth::user()->acess_level === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
            @else
                <a href="{{ route('user.index') }}" class="group flex items-center space-x-3 rtl:space-x-reverse">
        @endif
        <img src="/storage/galerizzicon.png" class="h-8" alt="galerizz Logo" />
        <span class="self-center text-2xl font-bold whitespace-nowrap text-black">Gale<span
                class="group-hover:text-blue-600 transition-colors duration-200 ease-in-out">rizz</span></span>
        </a>

        <!-- Search Bar -->
        <div class="relative flex-grow flex items-center mx-4">
            <div class="w-full relative">
                <input type="text" id="search"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search..." autocomplete="off">

                <div id="search-suggestions"
                    class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto z-50 w-full">
                    <!-- Suggestions will be dynamically inserted here -->
                </div>
            </div>

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
                    @if (Auth::user()->acess_level === 'admin')
                        <li>
                            <a href="{{ route('admin.profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                        </li>
                    @endif
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const suggestionsContainer = document.getElementById('search-suggestions');
        let suggestions = [];
        let selectedIndex = -1;

        // Debounce function
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args);
                }, timeout);
            };
        }

        // Fetch suggestions
        const fetchSuggestions = debounce(async (query) => {
            if (query.length < 2) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(
                    `/api/search-suggestions?query=${encodeURIComponent(query)}`);
                suggestions = await response.json();

                // Render suggestions
                renderSuggestions(suggestions);
            } catch (error) {
                console.error('Error fetching suggestions:', error);
            }
        });

        // Render suggestions
        function renderSuggestions(suggestions) {
            if (suggestions.length === 0) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
                return;
            }

            suggestionsContainer.innerHTML = suggestions.map((suggestion, index) => `
        <div class="suggestion-item px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center space-x-2"
             data-index="${index}"
             data-value="${suggestion.value}"
             data-type="${suggestion.type}">
            <i class='bx ${suggestion.icon} text-gray-500'></i>
            <span>${suggestion.label}</span>
        </div>
    `).join('');

            suggestionsContainer.classList.remove('hidden');
            selectedIndex = -1;
        }

        // Event listener for input
        searchInput.addEventListener('input', (e) => {
            fetchSuggestions(e.target.value);
        });

        // Handle key navigation
        searchInput.addEventListener('keydown', (e) => {
            const suggestionItems = suggestionsContainer.querySelectorAll('.suggestion-item');

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, suggestionItems.length - 1);
                    updateSelectedSuggestion(suggestionItems);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelectedSuggestion(suggestionItems);
                    break;
                case 'Tab':
                    if (selectedIndex >= 0) {
                        e.preventDefault();
                        const selectedSuggestion = suggestionItems[selectedIndex];
                        searchInput.value = selectedSuggestion.getAttribute('data-value');
                        suggestionsContainer.classList.add('hidden');
                    }
                    break;
                case 'Enter':
                    if (selectedIndex >= 0) {
                        e.preventDefault();
                        const selectedSuggestion = suggestionItems[selectedIndex];
                        selectSuggestion(selectedSuggestion);
                    } else if (e.target.value.trim()) {
                        // Default behavior for Enter when no suggestion is selected
                        window.location.href =
                            `/index-user?search=${encodeURIComponent(e.target.value.trim())}`;
                    }
                    break;
                case 'Escape':
                    suggestionsContainer.innerHTML = '';
                    suggestionsContainer.classList.add('hidden');
                    break;
            }
        });

        // Update selected suggestion
        function updateSelectedSuggestion(suggestionItems) {
            suggestionItems.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('bg-gray-200');
                    searchInput.value = item.getAttribute('data-value');
                } else {
                    item.classList.remove('bg-gray-200');
                }
            });
        }

        // Select suggestion
        function selectSuggestion(suggestionItem) {
            const value = suggestionItem.getAttribute('data-value');
            const type = suggestionItem.getAttribute('data-type');

            // Redirect based on suggestion type
            window.location.href = `/index-user?search=${encodeURIComponent(value)}`;
        }

        // Click outside to close suggestions
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
            }
        });

        // Click on suggestion
        suggestionsContainer.addEventListener('click', (e) => {
            const suggestionItem = e.target.closest('.suggestion-item');
            if (suggestionItem) {
                selectSuggestion(suggestionItem);
            }
        });
    });
</script>
