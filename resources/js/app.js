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


