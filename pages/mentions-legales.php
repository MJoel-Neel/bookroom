<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" sizes="32x32" href="favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon16.png">

    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    <link rel="stylesheet" href="../assets/css/pages-legales.css">
    <script src="../assets/js/pages-legales.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    <title>Mentions légales | Bookroom</title>

    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>
</head>

<body>
<?php require_once "../includes/header.php"; ?>

<main class="page-mentions">

  <!-- ============ EN-TÊTE DE PAGE ============ -->
  <div class="mentions-entete">
    <div class="mentions-fil-ariane" aria-label="Fil d'Ariane">
      <a href="/index.php">Accueil</a>
      <span aria-hidden="true">›</span>
      <span aria-current="page">Mentions légales</span>
    </div>

    <h1 class="mentions-titre">Mentions légales</h1>
    <p class="mentions-sous-titre">
      Dernière mise à jour : <time datetime="2025-01-01">1<sup>er</sup> janvier 2025</time>
    </p>
  </div>

  <!-- ============ LAYOUT (SIDEBAR + CONTENU) ============ -->
  <div class="mentions-layout">

    <!-- BOUTON HAMBURGER (Mobile) -->
    <button type="button" class="mentions-menu-btn" id="menu-toggle" aria-label="Ouvrir le sommaire" aria-expanded="false">
      <i class="fa-solid fa-bars"></i> Sommaire
    </button>

    <!-- SIDEBAR / SOMMAIRE FIXE -->
    <aside class="mentions-sidebar" id="sidebar-sommaire">
      <div class="mentions-sidebar-header">
        <h2 class="mentions-sidebar-titre">Sommaire</h2>
        <button type="button" class="mentions-close-btn" id="menu-close" aria-label="Fermer le sommaire">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <ol class="mentions-sommaire-liste">
        <li><a href="#editeur" class="tab-link is-active" data-target="editeur">Éditeur du site</a></li>
        <li><a href="#hebergeur" class="tab-link" data-target="hebergeur">Hébergeur</a></li>
        <li><a href="#propriete" class="tab-link" data-target="propriete">Propriété intellectuelle</a></li>
        <li><a href="#donnees" class="tab-link" data-target="donnees">Données personnelles</a></li>
        <li><a href="#cookies" class="tab-link" data-target="cookies">Cookies</a></li>
        <li><a href="#accessibilite" class="tab-link" data-target="accessibilite">Accessibilité</a></li>
        <li><a href="#liens" class="tab-link" data-target="liens">Liens hypertextes</a></li>
        <li><a href="#droit" class="tab-link" data-target="droit">Droit applicable</a></li>
        <li><a href="#contact" class="tab-link" data-target="contact">Contact</a></li>
      </ol>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <article class="mentions-contenu" id="contenu-principal">
      
      <!-- 1. ÉDITEUR (Actif par défaut) -->
      <section class="mentions-section is-active" id="editeur">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">1.</span>
          Éditeur du site
        </h2>

        <div class="mentions-fiche">
          <dl class="mentions-liste-definition">
            <div class="mentions-ligne">
              <dt>Raison sociale</dt>
              <dd>Ma Librairie SAS</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Forme juridique</dt>
              <dd>Société par actions simplifiée (SAS)</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Capital social</dt>
              <dd>10 000 €</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Siège social</dt>
              <dd>12 rue des Livres, 75000 Paris, France</dd>
            </div>
            <div class="mentions-ligne">
              <dt>SIRET</dt>
              <dd>123 456 789 00012</dd>
            </div>
            <div class="mentions-ligne">
              <dt>RCS</dt>
              <dd>Paris B 123 456 789</dd>
            </div>
            <div class="mentions-ligne">
              <dt>N° TVA intracommunautaire</dt>
              <dd>FR 12 123456789</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Responsable de publication</dt>
              <dd>Jean Dupont</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Téléphone</dt>
              <dd><a href="tel:+33123456789">01 23 45 67 89</a></dd>
            </div>
            <div class="mentions-ligne">
              <dt>E-mail</dt>
              <dd><a href="mailto:contact@malibrairie.fr">contact@malibrairie.fr</a></dd>
            </div>
          </dl>
        </div>
      </section>

      <!-- 2. HÉBERGEUR -->
      <section class="mentions-section" id="hebergeur">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">2.</span>
          Hébergeur
        </h2>

        <div class="mentions-fiche">
          <dl class="mentions-liste-definition">
            <div class="mentions-ligne">
              <dt>Nom</dt>
              <dd>OVHcloud SAS</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Adresse</dt>
              <dd>2 rue Kellermann, 59100 Roubaix, France</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Téléphone</dt>
              <dd><a href="tel:+33972101007">09 72 10 10 07</a></dd>
            </div>
            <div class="mentions-ligne">
              <dt>Site web</dt>
              <dd><a href="https://www.ovhcloud.com" target="_blank" rel="noopener noreferrer">www.ovhcloud.com</a></dd>
            </div>
          </dl>
        </div>

        <p class="mentions-texte">
          Les serveurs hébergeant le site sont situés en France (Roubaix et Gravelines)
          et respectent les normes de sécurité ISO/IEC 27001.
        </p>
      </section>

      <!-- 3. PROPRIÉTÉ INTELLECTUELLE -->
      <section class="mentions-section" id="propriete">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">3.</span>
          Propriété intellectuelle
        </h2>

        <p class="mentions-texte">
          L'ensemble du site <strong>Bookroom</strong> (structure, textes, images,
          logos, vidéos, bases de données, éléments graphiques) est protégé par le droit
          d'auteur et le droit des bases de données, conformément aux articles L.111-1
          et suivants du Code de la propriété intellectuelle.
        </p>
        <p class="mentions-texte">
          Toute reproduction, représentation, adaptation, modification ou exploitation,
          totale ou partielle, par quelque procédé que ce soit, sans autorisation écrite
          préalable de Bookroom, est strictement interdite et constitue une
          contrefaçon sanctionnée par les articles L.335-2 et suivants du même Code.
        </p>
      </section>

      <!-- 4. DONNÉES PERSONNELLES -->
      <section class="mentions-section" id="donnees">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">4.</span>
          Données personnelles
        </h2>

        <p class="mentions-texte">
          Les données personnelles collectées sur le site sont traitées conformément
          au Règlement Général sur la Protection des Données (RGPD — Règlement UE
          2016/679) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée.
        </p>

        <div class="mentions-fiche">
          <dl class="mentions-liste-definition">
            <div class="mentions-ligne">
              <dt>Responsable de traitement</dt>
              <dd>Bookroom</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Finalités</dt>
              <dd>Gestion des commandes, relation client, amélioration du service</dd>
            </div>
            <div class="mentions-ligne">
              <dt>Durée de conservation</dt>
              <dd>3 ans après la dernière activité (5 ans pour les comptabilités)</dd>
            </div>
          </dl>
        </div>
      </section>

      <!-- 5. COOKIES -->
      <section class="mentions-section" id="cookies">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">5.</span>
          Cookies
        </h2>

        <p class="mentions-texte">
          Le site utilise des cookies pour assurer son bon fonctionnement (session,
          panier, thème) et, avec votre consentement, pour mesurer l'audience et
          améliorer vos parcours.
        </p>

        <div class="mentions-tableau-conteneur">
          <table class="mentions-tableau">
            <caption class="mentions-tableau-legende">Catégories de cookies utilisés</caption>
            <thead>
              <tr>
                <th scope="col">Catégorie</th>
                <th scope="col">Exemples</th>
                <th scope="col">Durée</th>
                <th scope="col">Consentement</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Essentiels</td>
                <td>Session, panier, thème</td>
                <td>Session / 1 an</td>
                <td>Non requis</td>
              </tr>
              <tr>
                <td>Mesure d'audience</td>
                <td>Statistiques anonymisées</td>
                <td>13 mois</td>
                <td>Requis</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 6. ACCESSIBILITÉ -->
      <section class="mentions-section" id="accessibilite">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">6.</span>
          Accessibilité
        </h2>

        <p class="mentions-texte">
          Bookroom s'engage à rendre son site accessible au plus grand nombre,
          conformément aux recommandations du RGAA.
        </p>
      </section>

      <!-- 7. LIENS HYPERTEXTES -->
      <section class="mentions-section" id="liens">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">7.</span>
          Liens hypertextes
        </h2>

        <p class="mentions-texte">
          Le site peut contenir des liens vers des sites tiers. Bookroom n'exerce
          aucun contrôle sur ces sites et décline toute responsabilité quant à leur
          contenu.
        </p>
      </section>

      <!-- 8. DROIT APPLICABLE -->
      <section class="mentions-section" id="droit">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">8.</span>
          Droit applicable
        </h2>

        <p class="mentions-texte">
          Le site et les présentes mentions légales sont soumis au droit français.
        </p>
      </section>

      <!-- 9. CONTACT -->
      <section class="mentions-section" id="contact">
        <h2 class="mentions-section-titre">
          <span class="mentions-section-numero" aria-hidden="true">9.</span>
          Contact
        </h2>

        <ul class="mentions-liste mentions-liste-contact">
          <li>Par e-mail : <a href="mailto:contact@bookroom.fr">contact@bookroom.fr</a></li>
          <li>Par téléphone : <a href="tel:+33123456789">01 23 45 67 89</a></li>
        </ul>
      </section>

    </article>
  </div>

  <!-- ============ PIED DE PAGE ============ -->
  <footer class="mentions-pied">
    <a href="/index.php" class="mentions-bouton mentions-bouton-retour">← Retour à l'accueil</a>
    <button type="button" class="mentions-bouton mentions-bouton-imprimer" onclick="window.print()">
      Imprimer cette page
    </button>
  </footer>

</main>

<?php require_once "../includes/footer.php"; ?>
</body>
</html>