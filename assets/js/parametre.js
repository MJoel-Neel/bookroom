function showSection(e,sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => sec.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    document.querySelectorAll('.sidebar button').forEach((button) => {
        button.classList.remove('hover')
    })
    e.target.classList.add('hover')
}

/*MENUBARS | RESPONSIVE*/
const menubars = document.getElementById('menubars')
const sidebar = document.querySelector('.sidebar')
menubars.addEventListener('click', (e) => {
    e.stopPropagation()
    sidebar.classList.toggle('open')

    if (sidebar.classList.contains('open')) {
        document.body.addEventListener('click', () => {
           sidebar.classList.remove('open')
        })
    }
})


/*PROFILE*/
const profileButtonModif = document.querySelector('button#modif')
const profileButtonSave = document.querySelector('button#save-profil')
const profileButtonAnnul = document.querySelector('button#annul')
const profileDiv = document.querySelector('div.prohibit');
const profileForm = document.querySelector('form.allowed');

profileButtonModif.addEventListener('click', () => {
    profileDiv.classList.add('none')
    profileForm.classList.remove('none')
})
profileButtonAnnul.addEventListener('click', (e) => {
    e.preventDefault()
    profileDiv.classList.remove('none')
    profileForm.classList.add('none')
})
//Gestion de l'entré utilisateur
profileButtonSave.addEventListener('click', (e) => {
    e.preventDefault()
    const email = document.getElementById('email')
    const pseudo = document.getElementById('pseudo')
    const tel = document.getElementById('tel')

    //pseudo 
    const regExpPseudo = /^[A-Za-zÀ-ÖØ-öø-ÿ]+([ '-][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/
    if ((pseudo.value.trim().length > 2) && regExpPseudo.test(pseudo.value)) {
        pseudo.classList.remove('border-red')
    }
    else {
        pseudo.classList.add('border-red')
    }

    //mail
    const regExpEmail = /^[A-Za-z0-9]+([._%+-]?[A-Za-z0-9]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)+$/
    if (regExpEmail.test(email.value)) {
        email.classList.remove('border-red')
    }
    else {
        email.classList.add('border-red')
    }

    //tel
    const regExpTel = /^(0[1-9])([ ]?\d{2}){4}$/
    if (regExpTel.test(tel.value)) {
        tel.classList.remove('border-red')
    }
    else {
        tel.classList.add('border-red')
    }

    if (!document.querySelector('.border-red')) {
        document.querySelector('form.allowed').submit()
    }
})

/*SECURITE*/
//mdp
const buttonSaveMdp = document.getElementById('save_mdp')

buttonSaveMdp.addEventListener('click', (e) => {
    e.preventDefault()
    const oldMdp = document.getElementById('ancien_mdp')
    const newMdp = document.getElementById('nouveau_mdp')

    //old mdp verification

    if (oldMdp.value.trim().length >= 10) {
        oldMdp.classList.remove('border-red')
    }
    else {
        oldMdp.classList.add('border-red')
    }

    //new mdp verification
    if ((newMdp.value.trim().length >= 10) && (newMdp.value != oldMdp.value)) {
        newMdp.classList.remove('border-red')
    }
    else {
        newMdp.classList.add('border-red')
    }

    if (!document.querySelector('.border-red')) {
        document.querySelector('form#security').submit()
    }

})


/*ADRESSES*/
const buttonAdresse = document.getElementById('save_adresse')
buttonAdresse.addEventListener('click', (e) => {
    e.preventDefault()
    const ville = document.getElementById('ville')
    const adresse = document.getElementById('adresse')
    const postal = document.getElementById('postal')
    const regExpVille = /^[A-Za-zÀ-ÿ' -]{2,100}$/
    const regExpPostal = /^(\d){5}$/
    const regExpAdresse = /^[0-9]{1,5}\s[A-Za-zÀ-ÿ0-9'., -]{3,200}$/

    //ADRESSE
    if (regExpAdresse.test(adresse.value)) {
        adresse.classList.remove('border-red')
    }
    else {
        adresse.classList.add('border-red')

    }

    //VILLE
    if (regExpVille.test(ville.value)) {
        ville.classList.remove('border-red')
    }
    else {
        ville.classList.add('border-red')
    }

    //CODE POSTAL
    if (regExpPostal.test(postal.value)) {
        postal.classList.remove('border-red')
    }
    else {
        postal.classList.add('border-red')
    }


    if (!document.querySelector('.border-red')) {
        document.querySelector('form#address').submit()
    }
})