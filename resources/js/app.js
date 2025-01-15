import './bootstrap';
import 'flowbite';
import fullpage from 'fullpage.js';
import 'fullpage.js/dist/fullpage.css';

document.addEventListener('DOMContentLoaded', () => {
    new fullpage('#fullpage', {
        autoScrolling: true,
        scrollHorizontally: true,
        scrollOverflow: true,
        anchors: ['home', 'regist',],
        menu: '#menu',
    });
});

document.addEventListener('DOMContentLoaded', function () {
    let loading = false;

    window.addEventListener('scroll', function () {
        const loadMore = document.getElementById('load-more');
        const loadingIndicator = document.getElementById('loading');

        if (loadMore && !loading) {
            const nextPageUrl = loadMore.getAttribute('data-next-page');

            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                loading = true;
                loadingIndicator.classList.remove('hidden');

                fetch(nextPageUrl)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('photo-container').insertAdjacentHTML('beforeend', data);
                        loadingIndicator.classList.add('hidden');
                        loading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingIndicator.classList.add('hidden');
                    });
            }
        }
    });
});
