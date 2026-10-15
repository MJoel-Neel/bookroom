document.querySelector('.menu').addEventListener('click', () => {
    document.querySelector('.tools').classList.toggle('minify')
})

function showSection(e, id) {
    document.querySelectorAll('.container').forEach(div => {
        div.classList.remove('active')
    })
    document.getElementById(id).classList.add('active')

    document.querySelectorAll('div.tool').forEach((button) => {
        button.classList.remove('hover')
    })
    e.currentTarget.classList.add('hover')
}

/*EDIT & ADD SECTION*/
const editDiv = document.querySelector('div.edit-div');

/*CATEGORIES*/
const editCatButtons = document.querySelectorAll('.edit-cat');
const addCatForm = document.querySelector('.add-cat-form');
const editCatForm = document.querySelector('.edit-cat-form');
const addCatSvg = document.querySelector('svg.add-cat')

/*CATEGORIES*/
editCatButtons.forEach((editCatButton) => {
    editCatButton.addEventListener('click', () => {
        const categorieData = editCatButton.dataset
        document.querySelector('.edit-nom-cat').value = categorieData.nom ?? 'bob'
        document.querySelector('.edit-pref-cat').value = categorieData.prefixe ?? 'bob'
        addCatForm.classList.add('none')
        editCatForm.classList.remove('none')
        produitForm.classList.add('none')
        editDiv.classList.add('block')
    })
})
addCatSvg.addEventListener('click', () => {
    editCatForm.classList.add('none')
    addCatForm.classList.remove('none')
    produitForm.classList.add('none')
    editDiv.classList.add('block')
})

let edit_cat_id_input = document.getElementById('edit_cat_id_input')
editCatButtons.forEach((editCatButton) => {
    editCatButton.addEventListener('click', (e) => {
        edit_cat_id_input.value = e.currentTarget.nextElementSibling.value
    })
})

/*Produit*/
const produitForm = document.querySelector('.produit-form')
const produitSvg = document.querySelector('svg.produit')
const editProduitSvg = document.querySelectorAll('.edit-produit')
const hiddenInput = document.querySelector('.hidden-input')


produitSvg.addEventListener('click', () => {
    document.querySelector('.produit-actions-btn').innerHTML = 'Ajouter'
    document.getElementById('titre_produit').value = ''
    document.getElementById('resume_produit').value = ''
    document.getElementById('auteur_produit').value = ''
    document.getElementById('prix_produit').value = ''
    document.getElementById('stock_produit').value = ''
    document.getElementById('langue_produit').value = ''
    document.getElementById('public_legal_produit').value = ''
    document.getElementById('cat_produit').value = '1'
    document.getElementById('reduction_produit').value = ''
    hiddenInput.value = ''


    editCatForm.classList.add('none')
    addCatForm.classList.add('none')
    produitForm.classList.remove('none')
    editDiv.classList.add('block')
})

editProduitSvg?.forEach((editProduitButton) => {
    editProduitButton.addEventListener('click', (e) => {
        const produitData = editProduitButton.dataset
        document.getElementById('titre_produit').value = produitData.titre ?? ''
        document.getElementById('resume_produit').value = produitData.resume ?? ''
        document.getElementById('auteur_produit').value = produitData.auteur ?? ''
        document.getElementById('prix_produit').value = produitData.prix ?? ''
        document.getElementById('stock_produit').value = produitData.stock ?? ''
        document.getElementById('langue_produit').value = produitData.langue ?? ''
        document.getElementById('public_legal_produit').value = produitData.public ?? ''
        document.getElementById('cat_produit').value = produitData.categorie ?? ''
        document.getElementById('reduction_produit').value = produitData.reduction ?? ''
        hiddenInput.value = e.currentTarget.nextElementSibling.value ?? ''

        document.querySelector('.produit-actions-btn').innerHTML = 'Modifier'
        addCatForm.classList.add('none')
        editCatForm.classList.add('none')
        produitForm.classList.remove('none')
        editDiv.classList.add('block')
    })
})

/*FERMER EDIT DIV */
const closeCatButton = document.querySelectorAll('.edit-div form button.closing-form ')
closeCatButton.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault()
        editDiv.classList.remove('block')
    })
})





//CONTROLE DES CHAMPS D'AJOUT ET D' EDITION
//AJOUT DE NOUVEAU PRODUITS
produitForm.addEventListener('submit', handleProductAdding)
function handleProductAdding(e) {
    e.preventDefault()
    const produitField = document.getElementById('titre_produit')
    const addAuteurProduitField = document.getElementById('auteur_produit')
    const addResumeProduitField = document.getElementById('resume_produit')
    const addStockProduitField = document.getElementById('stock_produit')
    const imageProduitField = document.getElementById('image_produit')
    const addLangueProduitField = document.getElementById('langue_produit')
    const addReductionProduitField = document.getElementById('reduction_produit')
    const addPrixProduitField = document.getElementById('prix_produit')


    const form = new FormData(produitForm)
    let produit = form.get('titre_produit')?.trim()
    let addAuteurProduit = form.get('auteur_produit')?.trim()
    let addResumeProduit = form.get('resume_produit')?.trim()
    let addStockProduit = form.get('stock_produit')?.trim()
    let imageProduit = imageProduitField.files[0]
    let addLangueProduit = form.get('langue_produit')?.trim()
    let addReductionProduit = form.get('reduction_produit')?.trim()
    let addPrixProduit = form.get('prix_produit')?.trim()
    //Nom produit
    if (produit.length < 3) {
        produitField.classList.add('border')
    }
    else {
        produitField.classList.remove('border')
    }

    //Nom Auteur Produit
    if (addAuteurProduit.length < 3) {
        addAuteurProduitField.classList.add('border')
    }
    else {
        addAuteurProduitField.classList.remove('border')
    }

    //Resume produit
    if (addResumeProduit.length < 20) {
        addResumeProduitField.classList.add('border')
    }
    else {
        addResumeProduitField.classList.remove('border')
    }

    //Stock produit
    if (addStockProduit < 0) {
        addStockProduitField.classList.add('border')
    }
    else {
        addStockProduitField.classList.remove('border')
    }


    //Langue produit
    if (addLangueProduit.length > 3) {
        addLangueProduitField.classList.add('border')
    }
    else {
        addLangueProduitField.classList.remove('border')
    }

    //Reduction produit
    if (addReductionProduit < 0) {
        addReductionProduitField.classList.add('border')
    }
    else {
        addReductionProduitField.classList.remove('border')
    }

    //Prix produit
    if (addPrixProduit < 1) {
        addPrixProduitField.classList.add('border')
    }
    else {
        addPrixProduitField.classList.remove('border')
    }

    //Image Produit
    const maxSize = 1024 * 1024 //1Mo
    const typeAutorise = ['image/jpg', 'image/jpeg', 'image/webp', 'image/png']

    //SEULEMENT SI UN FICHIER A ETE UPLOADE
    if (imageProduitField && imageProduitField.files.length > 0) {
        //VERIFIER LE FORMAT DU FICHIER
        if (!typeAutorise.includes(imageProduit.type)) {
            alert("Format d'image non accepté");
            imageProduitField.classList.add('border')
        }
        //VERIFIER LA TAILLE DU FICHIER
        else if (imageProduit.size > maxSize) {
            alert("Image trop lourde (max 1 Mo)");
            imageProduitField.classList.add('border')
        }
        else {
            imageProduitField.classList.remove('border')
        }
    }
    //DANS LE CAS DE ADD L'IMAGE EST OBLIGATOIRE
    if ((hiddenInput.value === '') && imageProduitField.files.length <= 0) {
        imageProduitField.classList.add('border')
    }
    else {
        imageProduitField.classList.remove('border')
    }


    if (!produitForm.querySelector('.border')) {
        produitForm.submit()
    }
}

//AJOUT DE NOUVELLE CATEGORIE
addCatForm.addEventListener('submit', handleCategoryAdding)
function handleCategoryAdding(e) {
    e.preventDefault()
    const addPrefField = document.getElementById('add_pref')
    const addNomCatField = document.getElementById('add_nom_cat')

    const form = new FormData(addCatForm)
    const addPref = form.get('add_pref')
    const addNomCat = form.get('add_nom_cat')

    if (addPref.length < 3 || addPref.length > 10) {
        addPrefField.classList.add('border')
    }
    else {
        addPrefField.classList.remove('border')
    }

    if (addNomCat.length < 3) {
        addNomCatField.classList.add('border')
    }
    else {
        addNomCatField.classList.remove('border')
    }

    if (!addCatForm.querySelector('.border')) {
        addCatForm.submit()
    }
}
//EDITER CATEGORIE
editCatForm.addEventListener('submit', handleCategoryEditing)
function handleCategoryEditing(e) {
    e.preventDefault()

    const edit_nom_catField = document.getElementById('edit_nom_cat')
    const edit_prefField = document.getElementById('edit_pref')

    const form = new FormData(editCatForm)
    const edit_nom_cat = form.get('edit_nom_cat')
    const edit_pref = form.get('edit_pref')

    if (edit_pref.length < 3 || edit_pref.length > 10) {
        edit_prefField.classList.add('border')
    }
    else {
        edit_prefField.classList.remove('border')
    }

    if (edit_nom_cat.length < 3) {
        edit_nom_catField.classList.add('border')
    }
    else {
        edit_nom_catField.classList.remove('border')
    }

    if (!editCatForm.querySelector('.border')) {
        editCatForm.submit()
    }

}