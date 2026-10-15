document.querySelector('#conf').addEventListener('keyup', accept)
function accept() {
    const mdp = document.querySelector('#mdp').value.trim()
    const conf = document.querySelector('#conf').value.trim()
    if (conf == mdp) {
        document.getElementById('conf-i').className = 'mdpConf-val ico fa-solid fa-check'
    }
    else {
        document.getElementById('conf-i').className = 'mdpConf-inval ico fa-solid fa-ban'
    }
}

document.querySelector('.register-form').addEventListener('submit', verifUserInfo)
async function verifUserInfo(e) {
    try {

        const form = await new FormData(document.querySelector('.register-form'))
        const pseudo = form.get('pseudo')
        const mail = form.get('mail')
        const mdp = form.get('mdp')
        const conf = form.get('conf')

        e.preventDefault()
        //pseudo 
        if (pseudo.length > 2) {
            document.querySelector('#pseudo').classList.add('wrong')
            const regExpPseudo = /^[A-Za-zÀ-ÖØ-öø-ÿ]+([\s'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/
            if (regExpPseudo.test(pseudo)) {
                document.querySelector('#pseudo').classList.remove('wrong')
            }
            else {
                document.querySelector('#pseudo').classList.add('wrong')

            }
        }
        else {
            document.querySelector('#pseudo').classList.add('wrong')

        }

        //mail
        if (mail) {
            document.querySelector('#email').classList.remove('wrong')
            const regExpEmail = /^[A-Za-z0-9]+([._%+-]?[A-Za-z0-9]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)+$/
            if (regExpEmail.test(mail)) {

                document.querySelector('#email').classList.remove('wrong')
            }
            else {

                document.querySelector('#email').classList.add('wrong')
            }
        }
        else {
            document.querySelector('#email').classList.add('wrong')

        }

        //mdp
        if (mdp.trim().length >= 10) {
            document.querySelector('#mdp').classList.remove('wrong')

        }
        else {
            document.querySelector('#mdp').classList.add('wrong')

        }

        //conf
        if (mdp.trim() == conf.trim()) {

            document.querySelector('#conf').classList.remove('wrong')
        }
        else {
            document.querySelector('#conf').classList.add('wrong')

        }

        //ENVOI
        if (!document.querySelector('.wrong')) {
            document.querySelector('.register-form').submit()
        }
        
    }

    catch (e) {

    } 
}

