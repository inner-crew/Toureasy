const menuBtn = document.querySelector('.menu-btn');
let menuOpen = false;

menuBtn.addEventListener('click', () => {
    if (!menuOpen) {
        menuBtn.classList.add('open')
        menuOpen = true;
        document.querySelector(".container").classList.remove('show')
        document.querySelector(".container").classList.add('hide')
    } else {
        menuBtn.classList.remove('open');
        menuOpen = false;
        document.querySelector(".container").classList.remove('hide')
        document.querySelector(".container").classList.add('show')
    }
})