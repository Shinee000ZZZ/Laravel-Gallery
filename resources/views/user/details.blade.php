<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $photo->title ?? 'Photo Details' }}</title>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <style>
        .photo-container {
            height: calc(100vh - 250px);
            max-height: 1200px;
            min-height: 542px;
        }

        .details-container {
            height: calc(100vh - 250px);
            max-height: 1200px;
            min-height: 542px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .content-scrollable {
            flex: 1;
            overflow-y: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-bottom: 70px;
            /* Space for fixed comment input */
        }

        .photo-info {
            flex-shrink: 0;
        }

        .comments-section {
            flex-grow: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 200px;
            max-height: calc(100% - 120px);
            margin-bottom: 20px;
            /* Extra space between comments and input */
        }

        .comments-wrapper {
            position: relative;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .comment-options-btn .absolute {
            z-index: 9999 !important;
            position: fixed;
            top: auto !important;
            bottom: auto !important;
            left: auto !important;
            right: auto !important;
            transform: translate(calc(-100% + 40px), -100%);
        }

        #comments-scroll-container {
            height: auto;
            max-height: 100px;
            overflow-y: auto;
            transition: height 0.3s ease-in-out;
            margin-bottom: 30px;
            /* Space between comment list and input */
        }

        .comment-input-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 1rem;
            z-index: 10;
            border-radius: 0 0 0.75rem 0.75rem;
            /* Match container rounded corners */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .comment-group {
            transition: background-color 0.2s ease;
        }

        .comment-group:hover {
            background-color: #f8fafc;
        }

        .animate-fade-in {
            animation: commentFadeIn 0.5s ease forwards;
        }

        @keyframes commentFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #toggle-comments {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        #toggle-comments:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        /* Custom scrollbar for comments */
        #comments-scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        #comments-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }

        #comments-scroll-container::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 8px;
        }

        #comments-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-gray-50 font-monsterrat pt-16">

    @include('components.navbar')

    <div class="container max-w-screen-xl mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 gap-8">
            {{-- Image Section --}}
            <div
                class="photo-container bg-white rounded-xl shadow-lg overflow-hidden flex items-center justify-center p-4 relative group">
                <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title ?? 'Photo Details' }}"
                    class="max-w-full max-h-full object-contain rounded-lg transition-transform duration-200 hover:scale-105">

                {{-- Three-dot dropdown menu --}}
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="relative">
                        <button id="photo-options-btn" class="bg-white/70 rounded-full p-2 hover:bg-white/90">
                            <i class="bx bx-dots-vertical-rounded text-gray-700"></i>
                        </button>
                        <div id="photo-options-menu"
                            class="absolute right-0 top-full z-10 hidden w-48 bg-white rounded-lg shadow-lg border mt-2 overflow-hidden">
                            @if (auth()->id() == $photo->user_id)
                                <a href="{{ route('photos.edit', $photo->photo_id) }}"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2">
                                    <i class="bx bx-edit"></i>
                                    <span>Edit Foto</span>
                                </a>
                                <form action="{{ route('photos.trash', $photo->photo_id) }}" method="POST"
                                    class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2 text-red-500">
                                        <i class="bx bx-trash"></i>
                                        <span>Buang ke Sampah</span>
                                    </button>
                                </form>
                            @endif
                            <button onclick="downloadImage('{{ asset('storage/' . $photo->image_path) }}')"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2">
                                <i class="bx bx-download"></i>
                                <span>Download</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details Section --}}
            <div class="details-container bg-white shadow-md rounded-xl">
                <div class="content-scrollable p-6 overflow-visible">
                    {{-- Photo Information Card --}}
                    <div class="photo-info mb-6">
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
                                    <span class="likes-count">{{ $photo->likes()->count() }}</span>
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
                    </div>

                    {{-- Comments Section --}}
                    <div class="comments-section mb-16">

                        {{-- Comments Container --}}
                        <div class="comments-wrapper">
                            @if ($photo->comments->count() > 0)
                                <div class="text-center mb-3">
                                    <button id="toggle-comments"
                                        class="inline-flex items-center justify-center text-blue-500 hover:text-blue-600 transition-colors text-sm font-medium">
                                        <span>Lihat Komentar ({{ $photo->comments->count() }})</span>
                                        <i class="bx bx-chevron-down ml-1 text-lg"></i>
                                    </button>
                                </div>

                                <div id="comments-scroll-container" class="space-y-5 overflow-y-auto px-1 py-2" style="display: none;">
                                    @foreach ($photo->comments as $comment)
                                        <div class="flex space-x-3 comment-group bg-gray-50 p-3 rounded-lg" data-comment-id={{ $comment->comment_id }}>
                                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                                alt="{{ $comment->user->username }}"
                                                onclick="window.location.href='{{ $comment->user->username === auth()->user()->username ? route('profile') : route('user.profile', $comment->user->username) }}'"
                                                class="w-9 h-9 rounded-full object-cover border border-gray-200 cursor-pointer">
                                            <div class="flex-grow">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="font-semibold text-gray-800 text-sm">
                                                        <a href="{{ $comment->user->username === auth()->user()->username ? route('profile') : route('user.profile', $comment->user->username) }}">
                                                            {{ $comment->user->username }}
                                                        </a>
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-700 mb-2">{{ $comment->comment_text }}</p>
                                                <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500">
                                                    <button class="hover:text-blue-500 flex items-center transition-colors">
                                                        <i class="bx bx-heart mr-1 text-base"></i>
                                                        <span>Suka</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-lg">
                                    <i class="bx bx-message-rounded-detail text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">Belum ada komentar</p>
                                    <p class="text-gray-400 text-xs mt-1">Jadilah yang pertama berkomentar</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Comment Input --}}
                    <div class="comment-input-container">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="photo_id" value="{{ $photo->photo_id }}">
                            <div class="relative">
                                <input type="text" name="comment_text" placeholder="Tulis komentar..."
                                    class="w-full px-3 py-2 border rounded-lg pr-10 text-sm">
                                <button type="submit"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-blue-500 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // First get all necessary elements
            const tagElements = document.querySelectorAll('.bg-blue-100');
            const photoContainer = document.querySelector('.photo-container');
            const detailsContainer = document.querySelector('.details-container');
            const toggleCommentsBtn = document.getElementById('toggle-comments');
            const commentsScrollContainer = document.getElementById('comments-scroll-container');
            const commentForm = document.querySelector('form[action="{{ route('comments.store') }}"]');
            const commentInput = commentForm.querySelector('input[name="comment_text"]');
            const commentSubmitBtn = commentForm.querySelector('button[type="submit"]');

            // Adjust container heights if many tags
            if (tagElements.length > 5) {
                const tagsContainer = document.querySelector('.flex.flex-wrap.gap-2');

                if (tagsContainer && tagsContainer.offsetHeight > 50) {
                    if (photoContainer && detailsContainer) {
                        photoContainer.style.height = 'calc(100vh - 150px)';
                        photoContainer.style.maxHeight = '1200px';

                        detailsContainer.style.height = 'calc(100vh - 150px)';
                        detailsContainer.style.maxHeight = '1200px';
                    }
                }
            }

            // Comments Functionality
            if (toggleCommentsBtn) {
                toggleCommentsBtn.addEventListener('click', function() {
                    const isVisible = commentsScrollContainer.style.display === 'block';
                    const icon = this.querySelector('i');
                    const commentCount = parseInt(this.getAttribute('data-comment-count') ||
                        {{ $photo->comments->count() }});
                    const hasComments = commentCount > 0;

                    if (!isVisible) {
                        commentsScrollContainer.style.display = 'block';
                        this.querySelector('span').textContent = 'Sembunyikan Komentar';
                        icon.classList.remove('bx-chevron-down');
                        icon.classList.add('bx-chevron-up');

                        // Smooth animation for height - handle empty comments case differently
                        commentsScrollContainer.style.height = '0';
                        commentsScrollContainer.style.transition = 'height 0.3s ease-in-out';
                        setTimeout(() => {
                            if (hasComments) {
                                commentsScrollContainer.style.maxHeight = '150px';
                                commentsScrollContainer.style.height = 'auto';
                            } else {
                                // For empty comments, set a smaller height for the "no comments" message
                                commentsScrollContainer.style.maxHeight = '120px';
                                commentsScrollContainer.style.height = 'auto';
                            }
                        }, 10);
                    } else {
                        this.querySelector('span').textContent = 'Lihat Komentar (' + commentCount + ')';
                        icon.classList.remove('bx-chevron-up');
                        icon.classList.add('bx-chevron-down');
                        // Smooth animation for hiding
                        commentsScrollContainer.style.height = '0px';
                        setTimeout(() => {
                            commentsScrollContainer.style.display = 'none';
                            commentsScrollContainer.style.transition = '';
                        }, 300);
                    }
                });
            }

            // Setup comment options with improved z-index and functionality
            function setupCommentOptions() {
                document.querySelectorAll('.comment-group').forEach(comment => {
                    // Skip if already processed
                    if (comment.classList.contains('options-added')) return;
                    comment.classList.add('options-added');

                    // Get the comment username
                    const commentUsername = comment.querySelector('.font-semibold').textContent.trim();
                    const currentUsername = '{{ auth()->user()->username }}';
                    const commentId = comment.dataset.commentId;

                    // Only add options to user's own comments
                    if (commentUsername === currentUsername) {
                        const actionsContainer = comment.querySelector('.flex.items-center.space-x-3');
                        if (!actionsContainer) return;

                        // Add comment options button if not already present
                        if (!comment.querySelector('.comment-options-btn')) {
                            const optionsBtn = document.createElement('button');
                            optionsBtn.className =
                                'comment-options-btn ml-auto hover:text-blue-500 flex items-center transition-colors relative';
                            optionsBtn.innerHTML = `
                        <i class="bx bx-dots-vertical-rounded text-base"></i>
                    `;

                            // Create dropdown menu with higher z-index
                            const dropdownMenu = document.createElement('div');
                            dropdownMenu.className =
                                'absolute bottom-full right-0 bg-white rounded-lg shadow-lg border py-1 w-32 hidden z-50';
                            dropdownMenu.innerHTML = `
                        <button class="edit-comment-btn w-full text-left px-3 py-1.5 hover:bg-gray-100 text-xs flex items-center">
                            <i class="bx bx-edit mr-2"></i> Edit Komentar
                        </button>
                        <button class="delete-comment-btn w-full text-left px-3 py-1.5 hover:bg-gray-100 text-xs flex items-center text-red-500">
                            <i class="bx bx-trash mr-2"></i> Hapus Komentar
                        </button>
                    `;

                            optionsBtn.appendChild(dropdownMenu);
                            actionsContainer.appendChild(optionsBtn);

                            // Toggle dropdown
                            optionsBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                dropdownMenu.classList.toggle('hidden');
                            });

                            // Edit comment
                            dropdownMenu.querySelector('.edit-comment-btn').addEventListener('click',
                                () => {
                                    const commentText = comment.querySelector('p.text-sm.text-gray-700')
                                        .textContent.trim();
                                    startEditingComment(commentId, commentText);
                                    dropdownMenu.classList.add('hidden');
                                });

                            // Delete comment
                            dropdownMenu.querySelector('.delete-comment-btn').addEventListener('click',
                                () => {
                                    if (confirm('Yakin ingin menghapus komentar ini?')) {
                                        deleteComment(commentId, comment);
                                    }
                                    dropdownMenu.classList.add('hidden');
                                });
                        }
                    }
                });
            }

            // Set up editing mode for a comment
            function startEditingComment(commentId, commentText) {
                // Set form to edit mode
                commentForm.classList.add('edit-mode');
                commentForm.dataset.editingCommentId = commentId;

                // Populate input with comment text
                commentInput.value = commentText;
                commentInput.focus();

                // Change submit button appearance
                const originalButtonHtml = commentSubmitBtn.innerHTML;
                commentSubmitBtn.dataset.originalHtml = originalButtonHtml;
                commentSubmitBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
            </svg>
        `;

                // Add cancel button
                if (!document.querySelector('.cancel-edit-btn')) {
                    const cancelBtn = document.createElement('button');
                    cancelBtn.className =
                        'cancel-edit-btn absolute right-10 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600';
                    cancelBtn.type = 'button';
                    cancelBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            `;

                    commentForm.querySelector('.relative').appendChild(cancelBtn);

                    // Add edit indicator
                    const editIndicator = document.createElement('div');
                    editIndicator.className = 'text-xs text-blue-500 absolute -top-5 left-0';
                    editIndicator.textContent = 'Mengedit komentar...';
                    commentForm.querySelector('.relative').appendChild(editIndicator);

                    // Handle cancel edit
                    cancelBtn.addEventListener('click', () => {
                        if (commentInput.value !== commentText) {
                            if (confirm('Batalkan pengeditan komentar?')) {
                                resetCommentForm();
                            }
                        } else {
                            resetCommentForm();
                        }
                    });
                }

                // Click outside to cancel with confirmation
                const clickOutsideHandler = (e) => {
                    if (!commentForm.contains(e.target) && commentForm.classList.contains('edit-mode')) {
                        if (commentInput.value !== commentText) {
                            if (confirm('Batalkan pengeditan komentar?')) {
                                resetCommentForm();
                                document.removeEventListener('click', clickOutsideHandler);
                            }
                        } else {
                            resetCommentForm();
                            document.removeEventListener('click', clickOutsideHandler);
                        }
                    }
                };

                // Add with a small delay to prevent immediate triggering
                setTimeout(() => {
                    document.addEventListener('click', clickOutsideHandler);
                }, 100);

                // Store the handler for later removal
                commentForm._clickOutsideHandler = clickOutsideHandler;
            }

            // Reset comment form to normal state
            function resetCommentForm() {
                const originalButtonHtml = commentSubmitBtn.dataset.originalHtml;
                if (originalButtonHtml) {
                    commentSubmitBtn.innerHTML = originalButtonHtml;
                }

                commentForm.classList.remove('edit-mode');
                commentForm.removeAttribute('data-editing-comment-id');
                commentInput.value = '';

                // Remove cancel button and edit indicator
                const cancelBtn = document.querySelector('.cancel-edit-btn');
                if (cancelBtn) cancelBtn.remove();

                const editIndicator = document.querySelector('.text-xs.text-blue-500');
                if (editIndicator) editIndicator.remove();

                // Remove click outside handler
                if (commentForm._clickOutsideHandler) {
                    document.removeEventListener('click', commentForm._clickOutsideHandler);
                    delete commentForm._clickOutsideHandler;
                }
            }

            // Delete comment function with improved handling
            function deleteComment(commentId, commentElement) {
                fetch(`/comments/${commentId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Animasi penghapusan
                            commentElement.style.transition = 'opacity 0.3s, transform 0.3s';
                            commentElement.style.opacity = '0';
                            commentElement.style.transform = 'translateY(-10px)';

                            setTimeout(() => {
                                commentElement.remove();
                                updateCommentCount(-1);

                                // Periksa jumlah komentar yang tersisa
                                const commentsContainer = document.getElementById(
                                    'comments-scroll-container');
                                const remainingComments = commentsContainer.querySelectorAll(
                                    '.comment-group');

                                if (remainingComments.length === 0) {
                                    // Jika tidak ada komentar lagi, tampilkan status kosong
                                    commentsContainer.innerHTML = `
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <i class="bx bx-message-rounded-detail text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Belum ada komentar</p>
                                <p class="text-gray-400 text-xs mt-1">Jadilah yang pertama berkomentar</p>
                            </div>
                        `;

                                    // Sembunyikan tombol lihat komentar
                                    const toggleCommentsBtn = document.getElementById(
                                        'toggle-comments');
                                    if (toggleCommentsBtn) {
                                        toggleCommentsBtn.style.display = 'none';
                                    }
                                }
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal menghapus komentar');
                    });
            }

            // Update comment count in UI
            function updateCommentCount(change) {
                if (!toggleCommentsBtn) return;

                let currentCount = 0;
                const toggleSpan = toggleCommentsBtn.querySelector('span');

                if (toggleSpan.textContent.includes('(')) {
                    const match = toggleSpan.textContent.match(/\((\d+)\)/);
                    if (match && match[1]) {
                        currentCount = parseInt(match[1]);
                    }
                } else {
                    currentCount = parseInt(toggleCommentsBtn.getAttribute('data-comment-count') || '0');
                }

                const newCount = Math.max(0, currentCount + change);
                toggleCommentsBtn.setAttribute('data-comment-count', newCount);

                if (newCount === 0) {
                    // Sembunyikan tombol lihat komentar jika tidak ada komentar
                    toggleCommentsBtn.style.display = 'none';
                } else {
                    toggleCommentsBtn.style.display = 'inline-flex';
                    if (commentsScrollContainer.style.display === 'block') {
                        toggleSpan.textContent = 'Sembunyikan Komentar';
                    } else {
                        toggleSpan.textContent = `Lihat Komentar (${newCount})`;
                    }
                }
            }

            // Set up edit options on page load
            setupCommentOptions();

            // Close all comment dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.comment-options-btn').forEach(btn => {
                    const dropdown = btn.querySelector('div');
                    if (dropdown && !dropdown.classList.contains('hidden') && !btn.contains(e
                            .target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            });

            // Handle comment form submit for both adding and editing
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const commentText = commentInput.value.trim();
                if (commentText === '') return;

                const isEditing = commentForm.classList.contains('edit-mode');
                const commentId = isEditing ? commentForm.dataset.editingCommentId : null;

                if (isEditing) {
                    // Update existing comment
                    fetch(`/comments/${commentId}/update`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                comment_text: commentText
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Find and update the comment in the UI
                                const commentElement = document.querySelector(
                                    `.comment-group[data-comment-id="${commentId}"]`);
                                if (commentElement) {
                                    const commentTextElement = commentElement.querySelector(
                                        'p.text-sm.text-gray-700');
                                    if (commentTextElement) {
                                        commentTextElement.textContent = commentText;
                                    }

                                    // Show edited indicator
                                    const timeElement = commentElement.querySelector(
                                        '.text-xs.text-gray-500');
                                    if (timeElement) {
                                        timeElement.textContent = 'Diedit baru saja';
                                    }

                                    // Highlight briefly
                                    commentElement.style.transition = 'background-color 0.5s';
                                    commentElement.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
                                    setTimeout(() => {
                                        commentElement.style.backgroundColor = '';
                                    }, 1500);
                                }

                                // Reset form
                                resetCommentForm();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal memperbarui komentar');
                        });
                } else {
                    // Add new comment
                    fetch('{{ route('comments.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                photo_id: '{{ $photo->photo_id }}',
                                comment_text: commentText
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Create new comment element
                                const newCommentHTML = `
                        <div class="flex space-x-3 comment-group bg-gray-50 p-3 rounded-lg animate-fade-in" data-comment-id="${data.comment.id}">
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                alt="{{ auth()->user()->username }}"
                                class="w-9 h-9 rounded-full object-cover border border-gray-200">
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-semibold text-gray-800 text-sm">
                                        {{ auth()->user()->username }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Baru saja
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2">${commentText}</p>
                                <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500">
                                    <button class="hover:text-blue-500 flex items-center transition-colors">
                                        <i class="bx bx-heart mr-1 text-base"></i>
                                        <span>Suka</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                                // Add new comment to container
                                const commentsContainer = document.getElementById(
                                    'comments-scroll-container');

                                // If this is the first comment, we need to update the UI
                                if (!commentsContainer) {
                                    location.reload();
                                    return;
                                }

                                // Check if empty state message exists, and if so, remove it
                                const emptyState = commentsContainer.querySelector('.text-center.py-6');
                                if (emptyState) {
                                    commentsContainer.innerHTML = '';
                                }

                                // Show comments if they're hidden
                                if (commentsScrollContainer.style.display === 'none') {
                                    toggleCommentsBtn.click();
                                }

                                // Insert the new comment
                                commentsContainer.insertAdjacentHTML('afterbegin', newCommentHTML);

                                // Setup edit/delete options for new comment
                                setTimeout(setupCommentOptions, 100);

                                // Update comment count
                                updateCommentCount(1);

                                // Reset input and focus
                                commentInput.value = '';
                                commentInput.focus();

                                // Handle submit button visibility
                                if (commentSubmitBtn.classList.contains('hidden')) {
                                    commentSubmitBtn.classList.remove('hidden');
                                }

                                // Scroll to show the new comment with animation
                                const newComment = commentsContainer.firstElementChild;
                                newComment.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Don't show alert since comment might still be added
                            // alert('Gagal mengirim komentar. Silakan coba lagi.');
                        });
                }
            });

            // Like Functionality
            window.toggleLike = function(button) {
                const photoId = button.dataset.photoId;
                const heartIcon = button.querySelector('i');
                const likesCountElement = button.querySelector('.likes-count');

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
                        // Update likes count
                        likesCountElement.textContent = data.total_likes;

                        // Toggle heart icon
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
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memproses like');
                    });
            };

            // Input validation with enhanced feedback
            commentInput.addEventListener('input', function() {
                const isEmpty = this.value.trim() === '';

                if (isEmpty) {
                    commentSubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    commentSubmitBtn.disabled = true;
                } else {
                    commentSubmitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    commentSubmitBtn.disabled = false;
                }
            });

            // Initialize input state
            commentInput.dispatchEvent(new Event('input'));

            // Initialize photo-options dropdown
            const photoOptionsBtn = document.getElementById('photo-options-btn');
            const photoOptionsMenu = document.getElementById('photo-options-menu');

            if (photoOptionsBtn) {
                photoOptionsBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    photoOptionsMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function() {
                    if (!photoOptionsMenu.classList.contains('hidden')) {
                        photoOptionsMenu.classList.add('hidden');
                    }
                });
            }
        });

        // Download image function
        function downloadImage(imageSrc) {
            const a = document.createElement('a');
            a.href = imageSrc;
            a.download = '{{ $photo->title ?? 'photo' }}_' + new Date().getTime() + '.jpg';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
</body>

</html>
