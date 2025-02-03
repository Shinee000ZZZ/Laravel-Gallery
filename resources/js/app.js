import './bootstrap';
import 'flowbite';
import fullpage from 'fullpage.js';
import 'fullpage.js/dist/fullpage.css';

window.addEventListener('scroll', () => {
    const nav = document.querySelector('nav');
    if (window.scrollY > 10) {
        nav.classList.add('shadow-md');
    } else {
        nav.classList.remove('shadow-md');
    }
});

// fullpage js settings
document.addEventListener('DOMContentLoaded', () => {
    new fullpage('#fullpage', {
        autoScrolling: true,
        scrollHorizontally: true,
        scrollOverflow: true,
        anchors: ['home', 'regist',],
        menu: '#menu',
    });
});

// infinite scroll / lazy load
document.addEventListener('DOMContentLoaded', () => {
    // Fungsi untuk membuat loading indicator
    function createLoadingIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'loading-indicator';
        indicator.classList.add(
            'w-full',
            'text-center',
            'text-gray-500',
            'py-4',
            'flex',
            'justify-center',
            'items-center'
        );
        indicator.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat foto...
        `;
        return indicator;
    }

    // Fungsi untuk setup infinite scroll pada container tertentu
    function setupInfiniteScrollForContainer(containerSelector) {
        // Selector yang lebih fleksibel untuk container foto
        const photoContainer = document.querySelector(containerSelector);
        if (!photoContainer) return;

        let page = 1;
        let isLoading = false;
        let hasMorePhotos = true;

        function lazyLoadImages() {
            const lazyImages = photoContainer.querySelectorAll('.lazy[data-src]:not(.loaded)');

            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        }

        function loadMorePhotos() {
            if (isLoading || !hasMorePhotos) return;

            isLoading = true;
            page++;

            // Tentukan URL berdasarkan container
        let url;
        if (containerSelector.includes('explore')) {
            url = `/explore?page=${page}`;
        } else if (containerSelector.includes('profile')) {
            url = `/profile?page=${page}`;
        } else if (containerSelector.includes('index')) {
            url = `/index-user?page=${page}`;
        } else if (containerSelector.includes('othersProfile')) {
            // Ambil username dari URL atau dari elemen di halaman
            const username = document.querySelector('[data-username]')?.getAttribute('data-username');
            if (username) {
                url = `/user/${username}?page=${page}`;
            } else {
                console.error('Username not found');
                return;
            }
        } else {
            console.error('Invalid container selector');
            return;
        }

            // Tambahkan loading indicator
            const loadingIndicator = createLoadingIndicator();
            photoContainer.appendChild(loadingIndicator);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Pastikan response adalah JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new TypeError("Oops, we haven't got JSON!");
                }
                return response.json();
            })
            .then(data => {
                // Hapus loading indicator
                const existingIndicator = document.getElementById('loading-indicator');
                if (existingIndicator) {
                    existingIndicator.remove();
                }

                console.log('Received data:', data);

                if (!data.html || data.html.trim() === '') {
                    hasMorePhotos = false;
                    console.log('No more photos to load');
                    return;
                }

                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;

                const newPhotos = tempDiv.querySelectorAll('.photo-item');

                if (newPhotos.length === 0) {
                    hasMorePhotos = false;
                    console.log('No new photo items found');
                    return;
                }

                console.log(`Adding ${newPhotos.length} new photos`);

                newPhotos.forEach(photo => {
                    photoContainer.appendChild(photo);
                });

                // Cek apakah sudah halaman terakhir
                if (data.current_page >= data.last_page) {
                    hasMorePhotos = false;
                    console.log('Reached last page');

                    // Tambahkan pesan jika sudah halaman terakhir
                    const endMessage = document.createElement('div');
                    endMessage.classList.add('w-full', 'text-center', 'text-gray-500', 'py-4');
                    endMessage.textContent = 'Tidak ada foto lagi';
                    photoContainer.appendChild(endMessage);
                }

                lazyLoadImages();
                isLoading = false;
            })
            .catch(error => {
                // Hapus loading indicator
                const existingIndicator = document.getElementById('loading-indicator');
                if (existingIndicator) {
                    existingIndicator.remove();
                }

                console.error('Full error details:', error);

                // Tambahkan pesan error
                const errorMessage = document.createElement('div');
                errorMessage.classList.add('w-full', 'text-center', 'text-red-500', 'py-4');
                errorMessage.textContent = 'Gagal memuat foto. Silakan coba lagi.';
                photoContainer.appendChild(errorMessage);

                isLoading = false;
            });
        }

        // Event listener scroll
        window.addEventListener('scroll', () => {
            const scrollTrigger = document.body.offsetHeight - 300;

            if ((window.innerHeight + window.scrollY) >= scrollTrigger) {
                console.log('Scroll trigger activated');
                loadMorePhotos();
            }
        });

        // Lazy load awal
        lazyLoadImages();
    }

    // Setup infinite scroll untuk berbagai halaman
    setupInfiniteScrollForContainer('#explorePhotoContainer');
    setupInfiniteScrollForContainer('#profilePhotoContainer');
    setupInfiniteScrollForContainer('#indexPhotoContainer');
    setupInfiniteScrollForContainer('#othersProfilePhotoContainer');
});


// kompres foto
let currentFileInput = null;

document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('#photoInput, #photoUpload, #albumCoverInput');
    fileInputs.forEach(input => {
        input.addEventListener('change', checkImageSize);
    });
});

function checkImageSize(event) {
    const file = event.target.files[0];
    const maxSize = 2 * 1024 * 1024; // 2 MB

    if (file && file.size > maxSize) {
        currentFileInput = event.target;
        const modal = document.getElementById('compressionModal');

        // Pastikan modalnya benar-benar visible
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }
}

window.cancelUpload = function() {
    if (!currentFileInput) return;

    // Reset input file
    currentFileInput.value = '';

    const modal = document.getElementById('compressionModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';

    // Reset preview
    const previewMap = {
        '#photoInput': ['#photoPreview', '#photoDefault'],
        '#photoUpload': ['#imagePreview', '#uploadPlaceholder'],
        '#albumCoverInput': ['#albumCoverPreview', '#albumDefault']
    };

    const selector = `#${currentFileInput.id}`;
    const [previewSelector, defaultSelector] = previewMap[selector] || [];

    if (previewSelector && defaultSelector) {
        const previewElement = document.querySelector(previewSelector);
        const defaultElement = document.querySelector(defaultSelector);

        if (previewElement && defaultElement) {
            previewElement.classList.add('hidden');
            defaultElement.classList.remove('hidden');
        }
    }

    currentFileInput = null;
};

window.confirmCompression = async function() {
    if (!currentFileInput) return;

    try {
        const file = currentFileInput.files[0];
        const compressedFile = await compressImage(file);

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(compressedFile);
        currentFileInput.files = dataTransfer.files;

        // Trigger change event for preview
        const event = new Event('change', { bubbles: true });
        currentFileInput.dispatchEvent(event);

        const modal = document.getElementById('compressionModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';

        currentFileInput = null;
    } catch (error) {
        console.error('Kompresi gagal:', error);
    }
};

async function compressImage(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                const maxWidth = 1920;
                const maxHeight = 1080;
                let width = img.width;
                let height = img.height;

                if (width > maxWidth || height > maxHeight) {
                    const ratio = Math.min(maxWidth / width, maxHeight / height);
                    width = Math.round(width * ratio);
                    height = Math.round(height * ratio);
                }

                canvas.width = width;
                canvas.height = height;

                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    const compressedFile = new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    resolve(compressedFile);
                }, 'image/jpeg', 0.8);
            };
            img.onerror = reject;
            img.src = e.target.result;
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}
