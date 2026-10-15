const menuGeneral = document.querySelector('.menu')
const listLink = document.querySelector('.list-link')
const hamburgers = document.querySelectorAll('.hamburger')
const liList = document.querySelectorAll('.li-list')

menuGeneral.addEventListener('click', (e) => {
    e.stopPropagation() // empêche le body de fermer immédiatement
    hamburgers[0].classList.toggle('hamburger1')
    hamburgers[1].classList.toggle('hamburger2')
    hamburgers[2].classList.toggle('hamburger3')
    listLink.classList.toggle('display-block')
})

// fermer quand on clique sur un lien
liList.forEach((li) => {
    li.addEventListener('click', () => {
        closeMenu()
    })
})

// fermer quand on clique ailleurs
document.addEventListener('click', () => {
    closeMenu()
})

function closeMenu() {
    hamburgers[0].classList.remove('hamburger1')
    hamburgers[1].classList.remove('hamburger2')
    hamburgers[2].classList.remove('hamburger3')
    listLink.classList.remove('display-block')
}


/*Button search fake*/
/*li.search-raccourci*/
const buttonSearch = document.querySelector('li.search-raccourci');
if (buttonSearch) {
    buttonSearch.addEventListener('click', () => {
        document.querySelector('div.search').classList.toggle('block');
    })
}



/*BLOQUER INPUT SEARCH SI RIEN N'EST ENTRé*/
document.getElementById('search-form').addEventListener('submit', (e) => {
    const input = document.getElementById('search-product').value.trim().length
    if(input < 1) {
        e.preventDefault()
    }
})


//GERER LE CURSEUR DANS LA BARRE DE RECHERCHE SEARCH APRES UNE REQUETE
const searchProduct = document.getElementById('search-product')
if(searchProduct) {
    searchProduct.focus()
    searchProduct.setSelectionRange(searchProduct.value.length, searchProduct.value.length)
}