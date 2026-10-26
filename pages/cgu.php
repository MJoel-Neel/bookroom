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

  <title>Conditions Générales d'Utilisation | Bookroom</title>

  <?php include_once("../includes/header_head.php"); ?>
  <?php include_once("../includes/footer_head.php"); ?>
</head>

<body>
  <?php require_once "../includes/header.php"; ?>

  <main class="page-cgu">
    <!-- EN-TÊTE DE PAGE -->
    <div class="cgu-entete">
      <div class="cgu-fil-ariane" aria-label="Fil d'Ariane">
        <a href="../index.php">Accueil</a>
        <span aria-hidden="true">›</span>
        <span aria-current="page">CGU</span>
      </div>

      <h1 class="cgu-titre">Conditions Générales d'Utilisation</h1>
      <p class="cgu-sous-titre">
        Dernière mise à jour : <time datetime="2025-01-01">1<sup>er</sup> novembre 2026</time>
      </p>
    </div>

    <!-- LAYOUT (SIDEBAR + CONTENU) -->
    <div class="cgu-layout">

      <!-- BOUTON HAMBURGER (Mobile) -->
      <button type="button" class="cgu-menu-btn" id="menu-toggle" aria-label="Ouvrir le sommaire" aria-expanded="false">
        <i class="fa-solid fa-bars"></i> Sommaire
      </button>

      <!-- SIDEBAR / SOMMAIRE FIXE -->
      <aside class="cgu-sidebar" id="sidebar-sommaire">
        <div class="cgu-sidebar-header">
          <h2 class="cgu-sidebar-titre">Sommaire</h2>
          <button type="button" class="cgu-close-btn" id="menu-close" aria-label="Fermer le sommaire">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <ol class="cgu-sommaire-liste">
          <li><a href="#article-1" class="tab-link is-active" data-target="article-1">Objet</a></li>
          <li><a href="#article-2" class="tab-link" data-target="article-2">Mentions légales</a></li>
          <li><a href="#article-3" class="tab-link" data-target="article-3">Accès au service</a></li>
          <li><a href="#article-4" class="tab-link" data-target="article-4">Création de compte</a></li>
          <li><a href="#article-5" class="tab-link" data-target="article-5">Commandes et paiement</a></li>
          <li><a href="#article-6" class="tab-link" data-target="article-6">Livraison</a></li>
          <li><a href="#article-7" class="tab-link" data-target="article-7">Droit de rétractation</a></li>
          <li><a href="#article-8" class="tab-link" data-target="article-8">Données personnelles</a></li>
          <li><a href="#article-9" class="tab-link" data-target="article-9">Cookies</a></li>
          <li><a href="#article-10" class="tab-link" data-target="article-10">Propriété intellectuelle</a></li>
          <li><a href="#article-11" class="tab-link" data-target="article-11">Responsabilité</a></li>
          <li><a href="#article-12" class="tab-link" data-target="article-12">Modification des CGU</a></li>
          <li><a href="#article-13" class="tab-link" data-target="article-13">Droit applicable</a></li>
          <li><a href="#article-14" class="tab-link" data-target="article-14">Contact</a></li>
        </ol>
      </aside>

      <!-- CONTENU PRINCIPAL -->
      <article class="cgu-contenu" id="contenu-principal">

        <!-- ARTICLE 1 (Actif par défaut) -->
        <section class="cgu-article is-active" id="article-1">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">1.</span>
            Objet
          </h2>
          <p>
            Les présentes Conditions Générales d'Utilisation (ci-après «&nbsp;CGU&nbsp;»)
            ont pour objet de définir les modalités et conditions dans lesquelles
            l'utilisateur peut accéder au site <strong>Bookroom</strong> et utiliser
            ses services, notamment la consultation du catalogue, la création d'un compte
            et la passation de commandes.
          </p>
          <p>
            Toute utilisation du site implique l'acceptation pleine et entière des présentes
            CGU. L'utilisateur qui n'accepte pas ces conditions est invité à ne pas utiliser
            le site.
          </p>
        </section>

        <!-- ARTICLE 2 -->
        <section class="cgu-article" id="article-2">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">2.</span>
            Mentions légales
          </h2>
          <ul class="cgu-liste">
            <li><strong>Éditeur du site :</strong> Bookroom SAS</li>
            <li><strong>Siège social :</strong> 12 rue des Livres, 75000 Paris, France</li>
            <li><strong>Numéro SIRET :</strong> 123 456 789 00012</li>
            <li><strong>Capital social :</strong> 10 000 €</li>
            <li><strong>Responsable de publication :</strong> Jean Dupont</li>
            <li><strong>Hébergeur :</strong> OVHcloud, 2 rue Kellermann, 59100 Roubaix</li>
          </ul>
        </section>

        <!-- ARTICLE 3 -->
        <section class="cgu-article" id="article-3">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">3.</span>
            Accès au service
          </h2>
          <p>
            Le site est accessible gratuitement à tout utilisateur disposant d'un accès
            à Internet. Les frais d'accès (matériel, logiciel, connexion) sont à la charge
            exclusive de l'utilisateur.
          </p>
          <p>
            Bookroom met en œuvre tous les moyens raisonnables pour assurer un accès
            continu au site, sans être tenue à une obligation de résultat. L'accès peut
            être suspendu pour maintenance, mise à jour ou tout motif technique.
          </p>
        </section>

        <!-- ARTICLE 4 -->
        <section class="cgu-article" id="article-4">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">4.</span>
            Création de compte
          </h2>
          <p>
            La création d'un compte est nécessaire pour passer commande. L'utilisateur
            s'engage à fournir des informations exactes, complètes et à jour, et à les
            maintenir à jour via son espace personnel.
          </p>
          <ul class="cgu-liste">
            <li>L'utilisateur est seul responsable de la confidentialité de son mot de passe.</li>
            <li>Toute activité effectuée depuis son compte est réputée être de son fait.</li>
            <li>En cas d'utilisation non autorisée, l'utilisateur doit en informer Bookroom sans délai.</li>
            <li>L'utilisateur doit avoir au moins 16 ans pour créer un compte, ou disposer du consentement parental.
            </li>
          </ul>
        </section>

        <!-- ARTICLE 5 -->
        <section class="cgu-article" id="article-5">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">5.</span>
            Commandes et paiement
          </h2>
          <p>
            Les commandes sont passées via le panier du site. Elles sont soumises à
            disponibilité des articles en stock. Le contrat de vente est conclu au moment
            de la validation du paiement.
          </p>
          <p>
            Le paiement s'effectue par carte bancaire, PayPal ou virement. Les prix sont
            indiqués en euros, toutes taxes comprises, hors frais de livraison, précisés
            avant validation de la commande.
          </p>
          <p class="cgu-note">
            <strong>Note :</strong> les moyens de paiement proposés sont indicatifs et
            peuvent être modifiés à tout moment.
          </p>
        </section>

        <!-- ARTICLE 6 -->
        <section class="cgu-article" id="article-6">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">6.</span>
            Livraison
          </h2>
          <p>
            Les articles sont expédiés à l'adresse indiquée lors de la commande, dans un
            délai de 2 à 7 jours ouvrés pour la France métropolitaine. Les délais sont
            indicatifs et peuvent varier selon les transporteurs et les destinations.
          </p>
          <p>
            En cas de retard de livraison supérieur à 30 jours, l'utilisateur peut annuler
            sa commande et obtenir un remboursement intégral.
          </p>
        </section>

        <!-- ARTICLE 7 -->
        <section class="cgu-article" id="article-7">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">7.</span>
            Droit de rétractation
          </h2>
          <p>
            Conformément aux articles L.221-18 et suivants du Code de la consommation,
            l'utilisateur dispose d'un délai de <strong>14 jours</strong> à compter de la
            réception de sa commande pour exercer son droit de rétractation, sans avoir à
            justifier de motifs ni à payer de pénalités.
          </p>
          <p>
            Le retour s'effectue aux frais de l'utilisateur, sauf exception légale. Le
            remboursement intervient dans les 14 jours suivant la réception du retour.
          </p>
        </section>

        <!-- ARTICLE 8 -->
        <section class="cgu-article" id="article-8">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">8.</span>
            Données personnelles
          </h2>
          <p>
            Les données collectées (nom, prénom, adresse e-mail, adresse postale,
            historique de commandes) sont traitées conformément au Règlement Général sur
            la Protection des Données (RGPD — Règlement UE 2016/679).
          </p>
          <ul class="cgu-liste">
            <li><strong>Responsable de traitement :</strong> Bookroom SAS</li>
            <li><strong>Finalités :</strong> gestion des commandes, relation client, amélioration du service</li>
            <li><strong>Durée de conservation :</strong> 3 ans après la dernière activité</li>
            <li><strong>Droits :</strong> accès, rectification, effacement, opposition, portabilité</li>
          </ul>
          <p>
            Pour exercer vos droits, contactez :
            <a href="mailto:dpo@bookroom.fr">dpo@bookroom.fr</a>.
          </p>
        </section>

        <!-- ARTICLE 9 -->
        <section class="cgu-article" id="article-9">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">9.</span>
            Cookies
          </h2>
          <p>
            Le site utilise des cookies nécessaires au fonctionnement (session, panier,
            préférences de thème) et, avec votre consentement, des cookies de mesure
            d'audience. Vous pouvez à tout moment modifier vos préférences depuis le
            panneau de consentement.
          </p>
          <div class="cgu-tableau-conteneur">
            <table class="cgu-tableau">
              <caption class="cgu-tableau-legende">Catégories de cookies utilisés</caption>
              <thead>
                <tr>
                  <th scope="col">Catégorie</th>
                  <th scope="col">Finalité</th>
                  <th scope="col">Durée</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Essentiels</td>
                  <td>Session, panier, thème</td>
                  <td>Session / 1 an</td>
                </tr>
                <tr>
                  <td>Mesure d'audience</td>
                  <td>Statistiques anonymisées</td>
                  <td>13 mois</td>
                </tr>
                <tr>
                  <td>Marketing</td>
                  <td>Publicités personnalisées</td>
                  <td>Désactivé par défaut</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- ARTICLE 10 -->
        <section class="cgu-article" id="article-10">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">10.</span>
            Propriété intellectuelle
          </h2>
          <p>
            L'ensemble des éléments du site (structure, textes, images, logos, base de
            données) est protégé par le droit de la propriété intellectuelle et demeure
            la propriété exclusive de Bookroom ou de ses partenaires.
          </p>
          <p>
            Toute reproduction, représentation, adaptation ou exploitation, totale ou
            partielle, sans autorisation écrite préalable, est interdite et constitue
            une contrefaçon sanctionnée par les articles L.335-2 et suivants du Code
            de la propriété intellectuelle.
          </p>
        </section>

        <!-- ARTICLE 11 -->
        <section class="cgu-article" id="article-11">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">11.</span>
            Responsabilité
          </h2>
          <p>
            Bookroom ne saurait être tenue responsable des dommages indirects
            résultant de l'utilisation du site, notamment perte de données, perte
            d'exploitation ou préjudice commercial.
          </p>
          <p>
            L'utilisateur est responsable de l'usage qu'il fait du site et s'engage à
            ne pas porter atteinte à son bon fonctionnement (notamment par l'envoi de
            virus, l'extraction massive de données, ou toute tentative d'intrusion).
          </p>
        </section>

        <!-- ARTICLE 12 -->
        <section class="cgu-article" id="article-12">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">12.</span>
            Modification des CGU
          </h2>
          <p>
            Bookroom se réserve le droit de modifier à tout moment les présentes CGU.
            Les modifications prennent effet dès leur publication sur le site. Il est
            recommandé à l'utilisateur de consulter régulièrement cette page.
          </p>
          <p>
            En cas de modification substantielle, les utilisateurs disposant d'un compte
            seront informés par e-mail.
          </p>
        </section>

        <!-- ARTICLE 13 -->
        <section class="cgu-article" id="article-13">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">13.</span>
            Droit applicable
          </h2>
          <p>
            Les présentes CGU sont régies par le droit français. En cas de litige,
            une solution amiable sera recherchée en priorité. À défaut, les tribunaux
            français seront seuls compétents, sous réserve des règles impératives
            applicables aux consommateurs.
          </p>
        </section>

        <!-- ARTICLE 14 -->
        <section class="cgu-article" id="article-14">
          <h2 class="cgu-article-titre">
            <span class="cgu-article-numero" aria-hidden="true">14.</span>
            Contact
          </h2>
          <p>
            Pour toute question relative aux présentes CGU, vous pouvez nous contacter :
          </p>
          <ul class="cgu-liste cgu-liste-contact">
            <li>Par e-mail : <a href="mailto:contact@bookroom.fr">contact@bookroom.fr</a></li>
            <li>Par courrier : Bookroom SAS — 12 rue des Livres, 75000 Paris</li>
          </ul>
        </section>

      </article>
    </div>

    <!-- ACTIONS DE BAS DE PAGE -->
    <div class="cgu-pied">
      <a href="../index.php" class="cgu-bouton cgu-bouton-retour">← Retour à l'accueil</a>
    </div>

  </main>

  <?php require_once "../includes/footer.php"; ?>
</body>

</html>