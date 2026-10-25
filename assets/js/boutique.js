/*MENU*/
document.querySelector('i.menu').addEventListener('click', () => {
    document.querySelector('form.filtres').classList.toggle('block')
})

/*GESTION DES FILTRES*/
const filtresForm = document.querySelector('form.filtres')
const formInputs = document.querySelectorAll('form.filtres input:not(input#min, input#max)')
formInputs.forEach((input) => {
    input.addEventListener('change', (e) => {
        filtresForm.submit()
    })
})

/*PRIX*/
const prixMin = document.getElementById('min')
const prixMax = document.getElementById('max')
//submit avec délai
let timeout;
function autoSubmit() {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        filtresForm.submit()
    }, 500)
}
//Prix minimum
prixMin.addEventListener('input', () => {
    const min = Number(prixMin.value)
    const max = Number(prixMax.value)

    if (min < max && min >= 0) {
        prixMin.classList.remove('border-danger')
        autoSubmit()
    } else {
        prixMin.classList.add('border-danger')
    }
})
//Prix maximum
prixMax.addEventListener('input', () => {
    const min = Number(prixMin.value)
    const max = Number(prixMax.value)

    if (max > min && max >= 0) {
        prixMax.classList.remove('border-danger')
        autoSubmit()
    } else {
        prixMax.classList.add('border-danger')
    }
})

/*GESTION TRI*/
const selectOption = document.querySelector('select#trier')
selectOption.addEventListener('change', (e) => {
    filtresForm.submit()
})

/*SI AUCUN ARTICLE TROUVER*/
const articleTrouver = document.querySelectorAll('.article')
let div = ''
if (articleTrouver.length == 0) {
    div = document.createElement('div')
    div.classList.add('aucun-produit-trouve')
    div.innerHTML = `
    <div class="img-container" > 
        <img loading='lazy' src='../assets/images/ico/background/section/cart.svg' /> 
    </div>
    <div class="message-container" > 
        <p class="no-produit-text" > <strong> Aucun article trouvé </strong> </p>
        <p> Désolé, nous n'avons trouvé aucun article correspondant à votre recherche.</p>
    </div>
    `
    document.querySelector('.articles').appendChild(div)
}
else {
    if (div) {
        div.remove()
    }
}



//PAGINATION
let pageNumber = 1
const tousProduitsTrouves = document.querySelectorAll('.article')
const NBtousProduitsTrouves = tousProduitsTrouves.length
const NBpagesDispos = Math.ceil(NBtousProduitsTrouves / 12)

//AFFICHER LE NOMBRE DE PAGE
const pagination = document.querySelector('.pagination')
pagination.innerHTML = ''
for (i = 0; i <= NBpagesDispos + 1; i++) {
    let button = document.createElement('button')
    button.classList = 'pageBtn'
    if (i === 0) {
        button.innerHTML = 'Prec'
        button.dataset.page = 0
        button.classList.add('prec')
    }
    else if (i === NBpagesDispos + 1) {
        button.innerHTML = 'Suiv'
        button.dataset.page = -1
        button.classList.add('suiv')
    }
    else {
        if (i == 1) {
            button.classList.add('paginationButton')
        }
        button.innerHTML = `${i}`
        button.dataset.page = i
    }

    pagination.appendChild(button)
}
const paginationButtons = document.querySelectorAll('.pageBtn')
//GERER L'AFFICHAGE
function showPages(buttonValue, value = null) {
    if (value == 0) {
        if (buttonValue == 1) {
            pageNumber = 1
            showPages(pageNumber)
            value = null
        }
        else {
            pageNumber -= 1
            showPages(pageNumber)
            value = null
        }
        document.querySelector(`div.pagination button[data-page = '${pageNumber}']`).classList.add('paginationButton')
    }
    else if (value == -1) {
        if (buttonValue == NBpagesDispos) {
            pageNumber = NBpagesDispos
            showPages(pageNumber)
            value = null
        }
        else {
            pageNumber += 1
            showPages(pageNumber)
            value = null
        }
        document.querySelector(`div.pagination button[data-page = '${pageNumber}']`).classList.add('paginationButton')
    }
    else {
        pageNumber = buttonValue
        let start = (buttonValue - 1) * 12
        let end = start + 11
        tousProduitsTrouves.forEach((article) => {
            article.classList.add('not-show')
        })


        for (i = start; i <= end; i++) {
            if (tousProduitsTrouves[i]) {
                tousProduitsTrouves[i].classList.remove('not-show')
            }
        }
        document.querySelector('#recherche').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    handleSuivPrec(pageNumber)
}

function handleDisplay(event) {
    let value = event.currentTarget.dataset.page
    paginationButtons.forEach((button) => {
        button.classList.remove('paginationButton')
    })
    if (value != 0 && value != -1) {
        event.currentTarget.classList.add('paginationButton')
        pageNumber = Number(value)
    }

    showPages(pageNumber, value)
}

paginationButtons.forEach((button) => {
    button.addEventListener('click', handleDisplay)
})

function handleSuivPrec(pageNumber) {
    if (pageNumber === 1) {
        document.querySelector('div.pagination button.prec').classList.add('gris')
    } else {
        document.querySelector('div.pagination button.prec').classList.remove('gris')
    }
    if (pageNumber === NBpagesDispos) {
        document.querySelector('div.pagination button.suiv').classList.add('gris')
    } else {
        document.querySelector('div.pagination button.suiv').classList.remove('gris')
    }
}

handleSuivPrec(pageNumber)
showPages(pageNumber)

//GESTION FILTRE | VOIR PLUS - VOIR MOINS
const allFiltres = document.querySelectorAll('.filtre')
allFiltres.forEach((filterDiv) => {
    if (filterDiv.clientHeight > 290) {
        filterDiv.classList.add('more')
        const button = document.createElement('button')
        button.className = 'button-filtre'
        button.dataset.status = 'more'
        button.type = 'button'
        button.textContent = 'Voir plus'
        filterDiv.appendChild(button)

        button.addEventListener('click', (e) => {
            if (button.dataset.status === 'more') {
                button.textContent = 'Voir moins'
                button.dataset.status = 'less'
                filterDiv.classList.remove('more')
                console.log(button.textContent, button.dataset.status)
            }
            else  if(button.dataset.status === 'less') {
                button.textContent = 'Voir plus'
                button.dataset.status = 'more'
                filterDiv.classList.add('more')
                console.log(button.textContent, button.dataset.status)
            }
            else {
                button.textContent = "error"
            }
           
        })
    }

})
