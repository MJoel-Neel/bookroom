//THEME
(function () {
    try {
        var saved = localStorage.getItem('theme');
        var theme = saved || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    } catch (e) { localStorage.setItem('theme', 'light') }
})();

const documentHtml = document.documentElement;
function setTheme(theme) {
    documentHtml.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}



document.querySelector('#theme-select, #dash-theming').addEventListener('click', () => {
    const currentTheme = localStorage.getItem('theme')

    switch (currentTheme) {
        case 'light':
            setTheme('dark')
            break;
        case 'dark':
            setTheme('night');
            break;
        case 'night':
            setTheme('light')
            break
        default:
            setTheme('light')
    }
});

