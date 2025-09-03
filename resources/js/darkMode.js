const page = document.getElementById('mainPage')
const darkModeCheckbox = document.getElementById('dark-mode')

function setTheme(mode){
    page.setAttribute('data-bs-theme', mode)
    darkModeCheckbox.checked = (mode === 'dark')
    localStorage.setItem('theme', mode)
}

const savedTheme = localStorage.getItem('theme')
if (savedTheme){
    setTheme(savedTheme)
} else {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    setTheme(prefersDark ? 'dark' : 'light')
}

darkModeCheckbox.addEventListener('change', () => {
    setTheme(darkModeCheckbox.checked ? 'dark' : 'light')
})
