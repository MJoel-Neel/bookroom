/**
 * SCRIPT UNIFIÉ POUR LES PAGES LÉGALES (CGU & MENTIONS LÉGALES)
 * Gère :
 * 1. Le système d'onglets (Tabs)
 * 2. L'ouverture / fermeture du menu mobile (Hamburger)
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ============================================================
       1. SYSTÈME D'ONGLETS (TABS)
       ============================================================ */
    const tabLinks = document.querySelectorAll('.tab-link');
    // On cible à la fois les classes CGU et Mentions Légales
    const sections = document.querySelectorAll('.cgu-article, .mentions-section');

    tabLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault(); // Empêche le saut de page (scroll) natif

            // 1. Retirer la classe active de tous les liens et sections
            tabLinks.forEach(tab => tab.classList.remove('is-active'));
            sections.forEach(sec => sec.classList.remove('is-active'));

            // 2. Ajouter la classe active au lien cliqué
            link.classList.add('is-active');

            // 3. Afficher la section correspondante
            const targetId = link.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.add('is-active');
            }
        });
    });

    /* ============================================================
       2. GESTION DU MENU MOBILE (HAMBURGER)
       ============================================================ */
    const btnOpen = document.getElementById('menu-toggle');
    const btnClose = document.getElementById('menu-close');
    // On cible la sidebar peu importe la page (CGU ou ML)
    const sidebar = document.querySelector('.cgu-sidebar, .mentions-sidebar');

    if (btnOpen && btnClose && sidebar) {
        
        // Ouvrir le menu
        btnOpen.addEventListener('click', () => {
            sidebar.classList.add('is-open');
            btnOpen.setAttribute('aria-expanded', 'true');
        });

        // Fermer le menu avec le bouton X
        btnClose.addEventListener('click', () => {
            sidebar.classList.remove('is-open');
            btnOpen.setAttribute('aria-expanded', 'false');
        });

        // Fermer le menu automatiquement si on clique sur un lien (sur mobile)
        tabLinks.forEach(link => {
            link.addEventListener('click', () => {
                sidebar.classList.remove('is-open');
                btnOpen.setAttribute('aria-expanded', 'false');
            });
        });
    }
});