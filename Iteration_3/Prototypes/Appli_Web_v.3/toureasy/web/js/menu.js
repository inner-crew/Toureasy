const menuBtn = document.querySelector('.menu-btn');
let menuOpen = false;

menuBtn.addEventListener('click', () => {
    if (!menuOpen) {
        menuBtn.classList.add('open')
        menuOpen = true;
        document.querySelector(".container").classList.remove('show')
        document.querySelector(".container").classList.add('hide')
        document.body.style.overflow = 'hidden'
        document.body.classList.remove('out')
        document.body.classList.add('in')
    } else {
        menuBtn.classList.remove('open');
        menuOpen = false;
        document.querySelector(".container").classList.remove('hide')
        document.querySelector(".container").classList.add('show')
        document.body.style.overflow = 'visible'
        document.body.classList.remove('in')
        document.body.classList.add('out')
    }
})