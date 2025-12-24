const menuBtn = document.querySelector('#menu-btn')
const menuCloseBtn = document.querySelector('#menu-close-btn')
const menu = document.querySelector('#menu')

menuBtn.addEventListener('click', () => {
    menu.classList.remove('hidden')
    document.body.style.overflowY = 'hidden'
})

menuCloseBtn.addEventListener('click', () => {
    menu.classList.add('hidden')
    document.body.style.overflowY = 'auto'
})