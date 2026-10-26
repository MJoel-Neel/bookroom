<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" sizes="32x32" href="../favicon32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon16.png">

    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/fonts/font.css">
    
    <link rel="stylesheet" href="../assets/css/pages-legales.css">
    <script src="../assets/js/pages-legales.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    <title>Politique de Confidentialité | Bookroom</title>

    <?php include_once("../includes/header_head.php"); ?>
    <?php include_once("../includes/footer_head.php"); ?>
</head>

<body>
<?php require_once "../includes/header.php"; ?>

<main class="page-confidentialite">

  <!-- EN-TÊTE DE PAGE -->
  <div class="confidentialite-entete">
    <div class="confidentialite-fil-ariane" aria-label="Fil d'Ariane">
      <a href="../index.php">Accueil</a>
      <span aria-hidden="true">›</span>
      <span aria-current="page">Politique de Confidentialité</span>
    </div>

    <h1 class="confidentialite-titre">Politique de Confidentialité</h1>
    <p class="confidentialite-sous-titre">
      Dernière mise à jour : <time datetime="2025-01-01">1<sup>er</sup> novembre 2026</time>
    </p>
  </div>

  <!-- LAYOUT (SIDEBAR + CONTENU) -->
  <div class="confidentialite-layout">

    <!-- BOUTON HAMBURGER (Mobile) -->
    <button type="button" class="confidentialite-menu-btn" id="menu-toggle" aria-label="Ouvrir le sommaire" aria-expanded="false">
      <i class="fa-solid fa-bars"></i> Sommaire
    </button>

    <!-- SIDEBAR / SOMMAIRE FIXE -->
    <aside class="confidentialite-sidebar" id="sidebar-sommaire">
      <div class="confidentialite-sidebar-header">
        <h2 class="confidentialite-sidebar-titre">Sommaire</h2>
        <button type="button" class="confidentialite-close-btn" id="menu-close" aria-label="Fermer le sommaire">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <ol class="confidentialite-sommaire-liste">
        <!-- La classe .tab-link et l'attribut data-target sont requis par ton JS -->
        <li><a href="#intro" class="tab-link is-active" data-target="intro">Introduction</a></li>
        <li><a href="#responsable" class="tab-link" data-target="responsable">Responsable</a></li>
        <li><a href="#collecte" class="tab-link" data-target="collecte">Données collectées</a></li>
        <li><a href="#finalites" class="tab-link" data-target="finalites">Finalités</a></li>
        <li><a href="#conservation" class="tab-link" data-target="conservation">Conservation</a></li>
        <li><a href="#destinataires" class="tab-link" data-target="destinataires">Destinataires</a></li>
        <li><a href="#droits" class="tab-link" data-target="droits">Vos droits</a></li>
        <li><a href="#cookies" class="tab-link" data-target="cookies">Cookies</a></li>
        <li><a href="#securite" class="tab-link" data-target="securite">Sécurité</a></li>
      </ol>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <article class="confidentialite-contenu" id="contenu-principal">
      
      <!-- 1. INTRODUCTION (Actif par défaut) -->
      <section class="confidentialite-section is-active" id="intro">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">1.</span>
          Introduction
        </h2>
        <p class="confidentialite-texte">
          Chez <strong>Bookroom</strong>, la protection de vos données personnelles est une priorité. 
          Cette politique de confidentialité vous explique quelles données nous collectons, 
          pourquoi nous les collectons et comment nous les utilisons.
        </p>
        <p class="confidentialite-texte">
          En utilisant notre site et nos services, vous acceptez les pratiques décrites dans la présente politique. 
          Celle-ci s'applique à toutes les informations collectées sur le site Bookroom.
        </p>
      </section>

      <!-- 2. RESPONSABLE -->
      <section class="confidentialite-section" id="responsable">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">2.</span>
          Responsable du traitement
        </h2>
        <p class="confidentialite-texte">
          Le responsable du traitement des données est la société Bookroom SAS.
        </p>
        <div class="confidentialite-fiche">
          <dl class="confidentialite-liste-definition">
            <div class="confidentialite-ligne">
              <dt>Dénomination</dt>
              <dd>Bookroom SAS</dd>
            </div>
            <div class="confidentialite-ligne">
              <dt>Adresse</dt>
              <dd>12 rue des Livres, 75000 Paris, France</dd>
            </div>
            <div class="confidentialite-ligne">
              <dt>Contact DPO</dt>
              <dd><a href="mailto:dpo@bookroom.fr">dpo@bookroom.fr</a></dd>
            </div>
          </dl>
        </div>
      </section>

      <!-- 3. COLLECTE -->
      <section class="confidentialite-section" id="collecte">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">3.</span>
          Données collectées
        </h2>
        <p class="confidentialite-texte">
          Nous collectons uniquement les données nécessaires au fonctionnement de la boutique en ligne. 
          Ces données sont collectées lors de la création de votre compte, de la passation de commande 
          ou de votre navigation sur le site.
        </p>
        <ul class="confidentialite-liste">
          <li><strong>Données d'identité :</strong> Civilité, nom, prénom.</li>
          <li><strong>Données de contact :</strong> Adresse e-mail, numéro de téléphone, adresse postale.</li>
          <li><strong>Données de connexion :</strong> Identifiant, mot de passe chiffré, adresse IP.</li>
          <li><strong>Données de commande :</strong> Historique d'achats, factures.</li>
          <li><strong>Données de navigation :</strong> Pages consultées, préférences de thème (clair/sombre).</li>
        </ul>
        <p class="confidentialite-texte">
          <em>Note : Aucune donnée bancaire sensible n'est stockée sur nos serveurs. Les paiements sont sécurisés 
          via des prestataires agréés (Stripe, PayPal).</em>
        </p>
      </section>

      <!-- 4. FINALITÉS -->
      <section class="confidentialite-section" id="finalites">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">4.</span>
          Pourquoi nous collectons ces données ?
        </h2>
        <p class="confidentialite-texte">
          Vos données sont utilisées pour les finalités suivantes :
        </p>
        <ul class="confidentialite-liste">
          <li><strong>Gestion de la relation client :</strong> Traitement des commandes, livraisons, retours et service après-vente.</li>
          <li><strong>Gestion de votre compte :</strong> Permettre la connexion et la modification de vos informations.</li>
          <li><strong>Amélioration de nos services :</strong> Analyse statistique de l'utilisation du site (de manière anonymisée).</li>
          <li><strong>Communication :</strong> Envoi de newsletters ou d'offres promotionnelles (uniquement avec votre accord).</li>
          <li><strong>Sécurité :</strong> Prévention des fraudes et respect de nos obligations légales.</li>
        </ul>
      </section>

      <!-- 5. CONSERVATION -->
      <section class="confidentialite-section" id="conservation">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">5.</span>
          Durée de conservation
        </h2>
        <p class="confidentialite-texte">
          Vos données personnelles sont conservées pour la durée strictement nécessaire aux finalités décrites ci-dessus, 
          conformément aux obligations légales.
        </p>
        <div class="confidentialite-fiche">
          <dl class="confidentialite-liste-definition">
            <div class="confidentialite-ligne">
              <dt>Compte client</dt>
              <dd>Jusqu'à 3 ans après la dernière activité (achat ou connexion).</dd>
            </div>
            <div class="confidentialite-ligne">
              <dt>Factures</dt>
              <dd>10 ans (obligation comptable légale).</dd>
            </div>
            <div class="confidentialite-ligne">
              <dt>Cookies analytiques</dt>
              <dd>13 mois maximum.</dd>
            </div>
          </dl>
        </div>
      </section>

      <!-- 6. DESTINATAIRES -->
      <section class="confidentialite-section" id="destinataires">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">6.</span>
          Destinataires des données
        </h2>
        <p class="confidentialite-texte">
          Vos données sont confidentielles et ne sont jamais vendues à des tiers. 
          Elles peuvent toutefois être transmises à des prestataires de confiance 
          agissant pour notre compte :
        </p>
        <ul class="confidentialite-liste">
          <li><strong>Hébergeur :</strong> OVHcloud (pour le stockage sécurisé du site).</li>
          <li><strong>Transporteurs :</strong> La Poste, Mondial Relay (pour la livraison de vos colis).</li>
          <li><strong>Prestataires de paiement :</strong> Stripe, PayPal (pour sécuriser vos transactions).</li>
        </ul>
        <p class="confidentialite-texte">
          Tous nos partenaires sont soumis à des engagements de confidentialité stricts.
        </p>
      </section>

      <!-- 7. DROITS -->
      <section class="confidentialite-section" id="droits">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">7.</span>
          Vos droits (RGPD)
        </h2>
        <p class="confidentialite-texte">
          Conformément au Règlement Général sur la Protection des Données (RGPD), 
          vous disposez des droits suivants concernant vos données personnelles :
        </p>
        <ul class="confidentialite-liste">
          <li><strong>Droit d'accès :</strong> Obtenir une copie des données que nous détenons sur vous.</li>
          <li><strong>Droit de rectification :</strong> Corriger des informations inexactes ou incomplètes.</li>
          <li><strong>Droit à l'effacement (droit à l'oubli) :</strong> Demander la suppression de vos données.</li>
          <li><strong>Droit à la limitation :</strong> Restreindre temporairement l'utilisation de vos données.</li>
          <li><strong>Droit d'opposition :</strong> Refuser le traitement de vos données pour des motifs légitimes.</li>
          <li><strong>Droit à la portabilité :</strong> Récupérer vos données dans un format lisible par machine.</li>
        </ul>
        <p class="confidentialite-texte">
          Pour exercer ces droits, contactez notre DPO à l'adresse : 
          <a href="mailto:dpo@bookroom.fr">dpo@bookroom.fr</a>. 
          Une réponse vous sera apportée dans un délai maximum de 30 jours.
        </p>
      </section>

      <!-- 8. COOKIES -->
      <section class="confidentialite-section" id="cookies">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">8.</span>
          Gestion des Cookies
        </h2>
        <p class="confidentialite-texte">
          Un cookie est un petit fichier texte déposé sur votre terminal lors de la visite d'un site. 
          Nous utilisons différents types de cookies :
        </p>
        <div class="confidentialite-tableau-conteneur">
          <table class="confidentialite-tableau">
            <thead>
              <tr>
                <th scope="col">Type de cookie</th>
                <th scope="col">Utilité</th>
                <th scope="col">Consentement</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Strictement nécessaires</td>
                <td>Panier, session de connexion, préférences d'affichage.</td>
                <td>Non requis</td>
              </tr>
              <tr>
                <td>Mesure d'audience</td>
                <td>Statistiques anonymes de visites (Google Analytics).</td>
                <td>Requis</td>
              </tr>
              <tr>
                <td>Réseaux sociaux</td>
                <td>Boutons de partage Facebook, X (Twitter).</td>
                <td>Requis</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="confidentialite-texte">
          Vous pouvez à tout moment paramétrer vos choix via le bandeau cookies ou les options de votre navigateur.
        </p>
      </section>

      <!-- 9. SÉCURITÉ -->
      <section class="confidentialite-section" id="securite">
        <h2 class="confidentialite-section-titre">
          <span class="confidentialite-section-numero" aria-hidden="true">9.</span>
          Sécurité des données
        </h2>
        <p class="confidentialite-texte">
          Bookroom met en œuvre toutes les mesures techniques et organisationnelles appropriées 
          pour garantir la sécurité et la confidentialité de vos données personnelles :
        </p>
        <ul class="confidentialite-liste">
          <li>Chiffrement des mots de passe (algorithme de hachage sécurisé).</li>
          <li>Connexion sécurisée au site via le protocole HTTPS (SSL/TLS).</li>
          <li>Accès aux bases de données strictement limité aux personnes habilitées.</li>
          <li>Sauvegardes régulières et sécurisées des données.</li>
        </ul>
      </section>

    </article>
  </div>

  <!-- PIED DE PAGE -->
  <div class="confidentialite-pied">
    <a href="../index.php" class="confidentialite-bouton confidentialite-bouton-retour">← Retour à l'accueil</a>
  </div>

</main>

<?php require_once "../includes/footer.php"; ?>
</body>
</html>