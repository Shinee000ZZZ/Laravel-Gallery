<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $photo->title ?? 'Photo Details' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>

<body class="bg-gray-50 font-monsterrat pt-16">

   @include('components.navbar')

    <div class="container max-w-screen-xl mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 gap-8">
            {{-- Image Section --}}
            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden flex items-center justify-center p-4 h-[calc(100vh-250px)] max-h-[800px] min-h-[542px]">
                <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title ?? 'Photo Details' }}"
                    class="max-w-full max-h-full object-contain rounded-lg transition-transform duration-200 hover:scale-105">
            </div>

            {{-- Details Section --}}
            <div class="space-y-6">
                {{-- Photo Information Card --}}
                <div class="bg-white shadow-md rounded-xl p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center space-x-4">
                            @if ($photo->user)
                                @if ($photo->user->username === auth()->user()->username)
                                    <a href="{{ route('profile') }}">
                                    @else
                                        <a href="{{ route('user.profile', $photo->user->username) }}">
                                @endif
                                <img src="{{ $photo->user->profile_photo ? asset('storage/' . $photo->user->profile_photo) : asset('default-avatar.png') }}"
                                    alt="{{ $photo->user->username ?? 'Unknown User' }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-blue-100">
                                </a>
                                <div>
                                    @if ($photo->user->username === auth()->user()->username)
                                        <a href="{{ route('profile') }}"
                                            class="font-semibold text-gray-800 hover:text-blue-500">
                                        @else
                                            <a href="{{ route('user.profile', $photo->user->username) }}"
                                                class="font-semibold text-gray-800 hover:text-blue-500">
                                    @endif
                                    {{ $photo->user->username ?? 'Unknown' }}
                                    </a>
                                    <p class="text-sm text-gray-500">
                                        {{ $photo->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @else
                                <p>No user information available</p>
                            @endif
                        </div>

                        <div class="flex items-center space-x-1">
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="bx bx-share-alt"></i>
                            </button>
                            <button
                                class="like-button text-gray-500 hover:text-blue-500 {{ $photo->isLikedByUser(auth()->id()) ? 'text-blue-500' : '' }}"
                                data-photo-id="{{ $photo->photo_id }}" onclick="toggleLike(this)">
                                <i class="bx {{ $photo->isLikedByUser(auth()->id()) ? 'bxs-heart' : 'bx-heart' }}"
                                    style="{{ $photo->isLikedByUser(auth()->id()) ? 'color:#3f83f8' : '' }}"></i>
                                <span class="likes-count pb-4">{{ $photo->likes()->count() }}</span>
                            </button>
                        </div>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $photo->title ?? 'Untitled Photo' }}
                    </h1>

                    @if ($photo->description)
                        <p class="text-gray-600 mb-4 border-l-4 border-blue-500 pl-4 italic">
                            {{ $photo->description }}
                        </p>
                    @endif

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Related tags:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($photo->categories as $category)
                                <span
                                    class="bg-blue-100 mt-2 text-blue-800 text-sm font-medium px-2.5 py-1 rounded-full dark:bg-blue-200 dark:text-blue-800">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 mt-4">
                        <button
                            class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition-colors duration-300"
                            onclick="downloadImage('{{ asset('storage/' . $photo->image_path) }}')">
                            <i class="bx bx-download"></i>
                            <span>Download</span>
                        </button>
                    </div>
                </div>

                {{-- Additional Photo Information --}}
                <div class="bg-white shadow-md rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Photo Details</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Uploaded</p>
                            <p class="font-medium">{{ $photo->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">File Type</p>
                            <p class="font-medium">{{ strtoupper(pathinfo($photo->image_path, PATHINFO_EXTENSION)) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">File Size</p>
                            <p class="font-medium">
                                {{ round(filesize(storage_path('app/public/' . $photo->image_path)) / 1024 / 1024, 2) }}
                                MB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Comment Section --}}
        <div class="max-w-screen-xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-5">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Comments ({{ $photo->comments->count() }})</h2>

            {{-- Comment Form --}}
            <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="photo_id" value="{{ $photo->photo_id }}">
                <div class="relative">
                    <textarea id="comment-textarea" name="comment_text" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-300"
                        placeholder="Add a comment..." rows="3" required></textarea>
                    <button id="submit-comment" type="submit"
                        class="absolute bottom-2 right-2 bg-blue-500 text-white rounded-full p-2 hover:bg-blue-600 hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Existing Comments --}}
            <div id="comments-container" class="space-y-4 max-h-[400px] overflow-y-auto relative">
                <div id="initial-comments" class="space-y-4">
                    @php $displayLimit = 1 @endphp
                    @forelse($photo->comments->take($displayLimit) as $comment)
                        <div class="flex space-x-4 border-b pb-4">
                            @if ($comment->user->username === auth()->user()->username)
                                <a href="{{ route('profile') }}">
                                @else
                                    <a href="{{ route('user.profile', $comment->user->username) }}">
                            @endif
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                alt="{{ $comment->user->username }}"
                                class="w-10 h-10 rounded-full object-cover hover:opacity-90 transition-opacity">
                            </a>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-2">
                                    @if ($comment->user->username === auth()->user()->username)
                                        <a href="{{ route('profile') }}"
                                            class="font-semibold hover:text-blue-500 transition-colors">
                                        @else
                                            <a href="{{ route('user.profile', $comment->user->username) }}"
                                                class="font-semibold hover:text-blue-500 transition-colors">
                                    @endif
                                    {{ $comment->user->username }}
                                    </a>
                                    <span class="text-sm text-gray-500">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-gray-700">{{ $comment->comment_text }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">No comments yet</p>
                    @endforelse
                </div>

                {{-- Additional Comments (Hidden by default) --}}
                <div id="additional-comments" class="space-y-4 hidden">
                    @foreach ($photo->comments->skip($displayLimit) as $comment)
                        <div class="flex space-x-4 border-b pb-4">
                            @if ($comment->user->username === auth()->user()->username)
                                <a href="{{ route('profile') }}">
                                @else
                                    <a href="{{ route('user.profile', $comment->user->username) }}">
                            @endif
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                alt="{{ $comment->user->username }}"
                                class="w-10 h-10 rounded-full object-cover hover:opacity-90 transition-opacity">
                            </a>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-2">
                                    @if ($comment->user->username === auth()->user()->username)
                                        <a href="{{ route('profile') }}"
                                            class="font-semibold hover:text-blue-500 transition-colors">
                                        @else
                                            <a href="{{ route('user.profile', $comment->user->username) }}"
                                                class="font-semibold hover:text-blue-500 transition-colors">
                                    @endif
                                    {{ $comment->user->username }}
                                    </a>
                                    <span class="text-sm text-gray-500">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-gray-700">{{ $comment->comment_text }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Show More Comments Button --}}
                @if ($photo->comments->count() > $displayLimit)
                    <button id="show-more-comments" class="mt-4 text-blue-500 hover:underline">
                        Show More Comments
                    </button>
                @endif
            </div>
        </div>

    </div>
    <script>
        function downloadImage(imageUrl) {
            fetch(imageUrl)
                .then(response => response.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = '{{ $photo->title ?? 'photo' }}_' + new Date().getTime() + '.jpg';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch(error => {
                    console.error('Download failed:', error);
                    alert('Failed to download image');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('comment-textarea');
            const submitButton = document.getElementById('submit-comment');
            const showMoreButton = document.getElementById('show-more-comments');
            const additionalComments = document.getElementById('additional-comments');

            // Textarea submit button visibility
            textarea.addEventListener('input', function() {
                submitButton.classList.toggle('hidden', this.value.trim().length === 0);
            });

            // Show more comments functionality
            if (showMoreButton) {
                showMoreButton.addEventListener('click', function() {
                    additionalComments.classList.toggle('hidden');
                    this.textContent = additionalComments.classList.contains('hidden') ?
                        'Show More Comments' : 'Show Less Comments';
                });
            }
        });
    </script>

    <style>
        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            pointer-events: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                // Prevent right-click save options
                img.addEventListener('contextmenu', (e) => {
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                });

                // Remove event listeners to ensure image can't be interacted with
                img.oncontextmenu = (e) => {
                    e.preventDefault();
                    return false;
                };

                // Prevent copying
                img.oncopy = (e) => {
                    e.preventDefault();
                    return false;
                };
            });

            // Prevent keyboard copy
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

    <script>
        function toggleLike(button) {
            const photoId = button.dataset.photoId;
            const heartIcon = button.querySelector('i');

            fetch(`/photos/${photoId}/toggle-like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const likesCountElement = button.querySelector('.likes-count');
                    likesCountElement.textContent = data.total_likes;

                    if (data.liked) {
                        button.classList.add('text-blue-500');
                        heartIcon.classList.remove('bx-heart');
                        heartIcon.classList.add('bxs-heart');
                        heartIcon.style.color = '#3f83f8';
                    } else {
                        button.classList.remove('text-blue-500');
                        heartIcon.classList.remove('bxs-heart');
                        heartIcon.classList.add('bx-heart');
                        heartIcon.style.color = '';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>
