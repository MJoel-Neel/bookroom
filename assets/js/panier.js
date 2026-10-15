async function article() {
    const suppr = await document.querySelectorAll('.suppr')
    const sum = await document.querySelectorAll('.sum')
    const qte = await document.querySelectorAll('.qte')
    const more = await document.querySelectorAll('.more')
    const less = await document.querySelectorAll('.less')
 
    /*suppression*/
    suppr.forEach((i) => {
        i.addEventListener('click', () => {
        i.parentElement.remove()
    })
    })


    /*less*/
    less.forEach((less) => {
        less.addEventListener('click', () => {
            let qte = parseInt(less.nextElementSibling.textContent)
            if(qte > 0) {
                qte--
            }
            less.nextElementSibling.innerHTML = qte
            less.parentElement.nextElementSibling.textContent =  prixUnit * qte + '€'
        })
    })

    /*more*/

    more.forEach((more) => {
        more.addEventListener('click', () => {
            const prixUnit = parseInt(more.parentElement.nextElementSibling.textContent)
            let qte = parseInt(more.previousElementSibling.textContent)
            if(qte >= 0) {
                qte++
            }
            
            more.previousElementSibling.innerHTML = qte
            more.parentElement.nextElementSibling.textContent = prixUnit * qte + '€'
        })
    })
}
//article()
