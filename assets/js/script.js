//GESTION DU DEFILEMENT | SECTION HERO


function ScrollInfinite (indexMax, blocSize, unit, container) {
    let start = 1
    const timer = setInterval(() => {
        let newSize =  `translateX(-${((start % indexMax) * blocSize)}${unit})`
        container.style.transform = newSize
        start++
        }, 5000)
}

const container = document.querySelector('.hero-container')
ScrollInfinite(4, 100, 'vw', container)