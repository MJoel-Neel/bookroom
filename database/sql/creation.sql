-- ----------------------------------------------------------
-- Script MYSQL pour mcd 
-- ----------------------------------------------------------
-- ----------------------------
-- Database
-- ----------------------------
CREATE DATABASE IF NOT EXISTS bookroom
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bookroom;

-- ----------------------------
-- Table: CLIENT
-- ----------------------------
CREATE TABLE IF NOT EXISTS client (
  id_client            INT NOT NULL AUTO_INCREMENT,
  nom_prenom_client    VARCHAR(60) NOT NULL,
  adresse_client       VARCHAR(100),
  email_client         VARCHAR(50) NOT NULL,
  tel_client           VARCHAR(20) DEFAULT NULL,
  mot_de_passe         TEXT NOT NULL,
  role_client          VARCHAR(20) NOT NULL DEFAULT 'user',
  genre_client         CHAR(5) NOT NULL,
  date_inscription     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT chk_role_client CHECK (role_client IN ('user','admin')),
  CONSTRAINT CLIENT_PK PRIMARY KEY (id_client),
  CONSTRAINT email_client_UNQ UNIQUE (email_client),
  CONSTRAINT tel_client_UNQ UNIQUE (tel_client)
) ENGINE=InnoDB;


-- ----------------------------
-- Table: PANIER
-- ----------------------------
CREATE TABLE IF NOT EXISTS panier (
  id_panier        INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_client        INT NOT NULL,
  statut           ENUM('actif','valide','annule') NOT NULL DEFAULT 'actif',
  date_creation    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_validation  DATETIME NULL,
  CONSTRAINT PANIER_CLIENT_FK
    FOREIGN KEY (id_client) REFERENCES client(id_client) ON DELETE CASCADE,
  INDEX idx_client_statut (id_client, statut)
) ENGINE=InnoDB;



-- ----------------------------
-- Table: CATEGORIE
-- ----------------------------
CREATE TABLE IF NOT EXISTS categorie (
  id_categorie      INT NOT NULL AUTO_INCREMENT,
  nom_categorie     VARCHAR(50) NOT NULL,
  prefixe_categorie VARCHAR(50) NOT NULL UNIQUE,
  CONSTRAINT CATEGORIE_PK PRIMARY KEY (id_categorie)
) ENGINE=InnoDB;


-- ----------------------------
-- Table: LIVRE
-- ----------------------------
CREATE TABLE IF NOT EXISTS livre (
  id_livre         INT NOT NULL AUTO_INCREMENT,
  titre_livre      VARCHAR(100) NOT NULL,
  resume_livre     TEXT NOT NULL,
  auteur_livre     VARCHAR(50) NOT NULL,
  prix             DECIMAL(10,2) NOT NULL,
  stock            INT NOT NULL,
  image_livre      VARCHAR(100) NOT NULL,
  id_categorie     INT,
  reduction_livre  INT NOT NULL DEFAULT 0,
  langue           VARCHAR(5) NOT NULL DEFAULT 'FR',
  public_legal     VARCHAR(30) NOT NULL,
  date_ajout       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT LIVRE_PK PRIMARY KEY (id_livre),
  CONSTRAINT image_livre_UNQ UNIQUE (image_livre),
  CONSTRAINT LIVRE_id_categorie_FK
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE SET NULL
) ENGINE=InnoDB;


-- ----------------------------
-- Table: PRODUIT_PANIER
-- ----------------------------
CREATE TABLE IF NOT EXISTS produit_panier (
  id_livre  INT NOT NULL,
  id_panier INT NOT NULL,
  quantite  INT NOT NULL,
  CONSTRAINT PRODUIT_PANIER_PK PRIMARY KEY (id_livre, id_panier),
  CONSTRAINT PRODUIT_PANIER_id_livre_FK
    FOREIGN KEY (id_livre) REFERENCES livre(id_livre) ON DELETE CASCADE,
  CONSTRAINT PRODUIT_PANIER_id_panier_FK
    FOREIGN KEY (id_panier) REFERENCES panier(id_panier) ON DELETE CASCADE
) ENGINE=InnoDB;



-- ----------------------------
-- Table: AVIS
-- ----------------------------
CREATE TABLE IF NOT EXISTS avis (
  id_avis              INT NOT NULL AUTO_INCREMENT,
  note                 INT NOT NULL,
  commentaire          TEXT NOT NULL,
  date_avis            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_livre_concerner   INT NOT NULL,
  id_client_faire      INT,
  email_client         VARCHAR(50),
  pseudo_client        VARCHAR(50) DEFAULT 'Anonymous',
  CONSTRAINT AVIS_PK PRIMARY KEY (id_avis),
  CONSTRAINT AVIS_id_livre_Concerner_FK
    FOREIGN KEY (id_livre_concerner) REFERENCES livre(id_livre) ON DELETE CASCADE,
  CONSTRAINT AVIS_id_client_FAIRE_FK
    FOREIGN KEY (id_client_faire) REFERENCES client(id_client) ON DELETE SET NULL
) ENGINE=InnoDB;




-- ----------------------------
-- Table: COMMANDE
-- ----------------------------
CREATE TABLE IF NOT EXISTS commande (
  id_commande          INT NOT NULL AUTO_INCREMENT,
  id_client_Effectuer  INT NOT NULL,
  date_commande        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_paiement        DATETIME NULL,
  statut               ENUM('en_attente','payee','annulee') NOT NULL DEFAULT 'en_attente',
  prix_total           DECIMAL(10,2) NOT NULL,
  CONSTRAINT COMMANDE_PK PRIMARY KEY (id_commande),
  CONSTRAINT COMMANDE_id_client_Effectuer_FK
    FOREIGN KEY (id_client_Effectuer) REFERENCES client(id_client) ON DELETE RESTRICT,
  INDEX idx_client_date (id_client_Effectuer, date_commande)
) ENGINE=InnoDB;


-- ----------------------------
-- Table: COMMANDE_PRODUIT
-- ----------------------------
CREATE TABLE IF NOT EXISTS commande_produit (
  id_commande         INT NOT NULL,
  id_livre            INT NOT NULL,
  quantite_commandee  INT NOT NULL,
  prix_unitaire       DECIMAL(10,2) NOT NULL,
  actif               BOOLEAN DEFAULT TRUE,
  CONSTRAINT COMMANDE_PRODUIT_PK PRIMARY KEY (id_commande, id_livre),
  CONSTRAINT COMMANDE_PRODUIT_id_commande_FK
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
  CONSTRAINT COMMANDE_PRODUIT_id_livre_FK
    FOREIGN KEY (id_livre) REFERENCES livre(id_livre) ON DELETE RESTRICT
) ENGINE=InnoDB;

/*
-- ----------------------------
-- Table: LIVRAISON
-- ----------------------------
CREATE TABLE livraison (
  id_livraison INT NOT NULL AUTO_INCREMENT,
  id_commande_Concerner INT NOT NULL,
  adresse VARCHAR(50) NOT NULL,
  date_estime DATE NOT NULL,
  date_effective DATE NOT NULL,
  statut VARCHAR(50) NOT NULL,
  CONSTRAINT LIVRAISON_PK PRIMARY KEY (id_livraison),
  CONSTRAINT id_commande_Concerner_UNQ UNIQUE (id_commande_Concerner),
  CONSTRAINT LIVRAISON_id_commande_Concerner_FK FOREIGN KEY (id_commande_Concerner) REFERENCES COMMANDE (id_commande)  ON DELETE CASCADE
)ENGINE=InnoDB;


-- ----------------------------
-- Table: PAIEMENT
-- ----------------------------
CREATE TABLE paiement (
  id_paiement INT NOT NULL AUTO_INCREMENT,
  id_commande_Concerner INT NOT NULL,
  montant INT NOT NULL,
  statut VARCHAR(50) NOT NULL,
  mode_paiement VARCHAR(50) NOT NULL,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  reduction INT NOT NULL,
  CONSTRAINT PAIEMENT_PK PRIMARY KEY (id_paiement),
  CONSTRAINT id_commande_Concerner_UNQ UNIQUE (id_commande_Concerner),
  CONSTRAINT PAIEMENT_id_commande_Concerner_FK FOREIGN KEY (id_commande_Concerner) REFERENCES COMMANDE (id_commande)  ON DELETE CASCADE
)ENGINE=InnoDB;



-- ----------------------------
-- Table: ADMIN
-- ----------------------------
CREATE TABLE administrer(id_admin INT PRIMARY KEY AUTO_INCREMENT, email_admin VARCHAR(50) NOT NULL UNIQUE, mdp_admin TEXT NOT NULL);


-- ----------------------------------
-- Table: REDUCTION - Examen Blanc
-- ----------------------------------

CREATE TABLE reduction (
  `id_reduction` int NOT NULL,
  `debut_reduction` date NOT NULL,
  `fin_reduction` date NOT NULL,
  `taux_reduction` int NOT NULL,
  PRIMARY KEY (`id_reduction`)
) ENGINE=InnoDB;

*/