const inputQte = document.getElementById('qte-value')
const formButtons = document.querySelectorAll(' div.ajouter-panier button')
const less = document.getElementById('less')
const more = document.getElementById('more')

less.addEventListener('click', (e) => {
    e.preventDefault()
    if (inputQte.value > 1) {
        inputQte.value--
    }
})

more.addEventListener('click', (e) => {
    e.preventDefault()
    inputQte.value++
})


inputQte.addEventListener('input', () => {
    if (inputQte.value < 1) {
        formButtons.forEach(button => {
            button.classList.add('opacity')
        })
    }
    else {
        formButtons.forEach(button => {
            button.classList.remove('opacity')
        })
    }
})

//AFFICHAGE DES AVIS (GESTION DE NOTE)
const note = document.querySelectorAll('.user-note')
note.forEach(bloc => {
    let myNote = ''
    for (i = 0; i < bloc.dataset.note; i++) {
        myNote += "<span class='myNote'>★<span>"
    }
    bloc.innerHTML+= myNote;

    let reste = ''
    for (i = 0; i < 5-bloc.dataset.note; i++) {
        reste += "<span>★<span>"
    }
    bloc.innerHTML+= reste;
});


/*FORMULAIRE AVIS*/
const formAvis = document.querySelector('.avis-form')
formAvis.addEventListener('submit', (e) => {
    e.preventDefault()
    const textarea = document.getElementById('avis')
    const mail = document.getElementById('mail')

    const form = new FormData(formAvis)

    //TEXTAREA 
    if (form.get('avis').trim().length > 5) {
        textarea.classList.remove('border-red')
    }
    else {
        textarea.classList.add('border-red')
    }






    //CONSETEMENT
    if (form.get('accord') != 'on') {
        document.querySelector('.consentement').classList.add('border-red')
    }
    else {
        document.querySelector('.consentement').classList.remove('border-red')
    }


    if (!document.querySelector('.border-red')) {
        formAvis.submit()
    }

})
