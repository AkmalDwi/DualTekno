document.addEventListener('DOMContentLoaded', function () {
    if (localStorage.getItem('darkMode') === 'enabled') {
        enableDarkMode();
    }
});

var darkModeToggle = document.getElementById('dark-mode-toggle');
darkModeToggle.addEventListener('click', function () {
    toggleDarkMode();
});

function enableDarkMode() {
    document.body.classList.add('dark-mode');
    var darkModeIcon = document.getElementById('darkModeIcon');
    darkModeIcon.classList.remove('bi-sun');
    darkModeIcon.classList.add('bi-moon-stars');
    localStorage.setItem('darkMode', 'enabled');
}

function disableDarkMode() {
    document.body.classList.remove('dark-mode');
    var darkModeIcon = document.getElementById('darkModeIcon');
    darkModeIcon.classList.remove('bi-moon-stars');
    darkModeIcon.classList.add('bi-sun');
    localStorage.setItem('darkMode', 'disabled');
}

function toggleDarkMode() {
    if (document.body.classList.contains('dark-mode')) {
        disableDarkMode();
    } else {
        enableDarkMode();
    }
}


var swiper = new Swiper('.swiper-container', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 30,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true 
    }
});

