document.addEventListener('DOMContentLoaded', () => {
    const headers = document.querySelectorAll('.commande-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            // On récupère le bloc parent
            const block = header.closest('.commande-block');
            
            // Optionnel : Ferme les autres accordéons ouverts quand on en ouvre un
            document.querySelectorAll('.commande-block').forEach(b => {
                if (b !== block && b.classList.contains('active')) {
                    b.classList.remove('active');
                }
            });

            // Ouvre/Ferme celui sur lequel on a cliqué
            block.classList.toggle('active');
        });
    });
});