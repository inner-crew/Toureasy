const menuBtn = document.querySelector('.menu-btn');
let menuOpen = false;

menuBtn.addEventListener('click', () => {
    if (!menuOpen) {
        menuBtn.classList.add('open')
        menuOpen = true;
        document.querySelector(".container").classList.remove('show')
        document.querySelector(".container").classList.add('hide')
        document.body.style.overflow = 'hidden'
    } else {
        menuBtn.classList.remove('open');
        menuOpen = false;
        document.querySelector(".container").classList.remove('hide')
        document.querySelector(".container").classList.add('show')
        document.body.style.overflow = 'visible'
    }
})