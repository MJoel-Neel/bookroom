document.querySelector('.login-form').addEventListener('submit', verifUserInfo)
async function verifUserInfo(e) {
    try {
        const form = await new FormData(document.querySelector('.login-form'))
        const mail = form.get('mail')
        const mdp = form.get('mdp')

        e.preventDefault()

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
        if (mdp.trim().length >= 12) {
            document.querySelector('#mdp').classList.remove('wrong')
        }
        else {
            document.querySelector('#mdp').classList.add('wrong')
        }

/*ENVOI*/
    if(!document.querySelector('.wrong')) {
            document.querySelector('.login-form').submit()
        }
    }


    catch (e) {

    }
}

function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }