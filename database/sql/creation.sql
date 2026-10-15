-- ----------------------------------------------------------
-- Script MYSQL pour mcd 
-- ----------------------------------------------------------


-- ----------------------------
-- Table: CLIENT
-- ----------------------------
CREATE TABLE client (
  id_client INT NOT NULL AUTO_INCREMENT ,
  nom_prenom_client VARCHAR(60) NOT NULL,
  adresse_client VARCHAR(100),
  email_client VARCHAR(50) NOT NULL,
  tel_client VARCHAR(20) DEFAULT NULL,
  mot_de_passe TEXT NOT NULL,

  `role_client` VARCHAR(20) NOT NULL DEFAULT 'user',
  CONSTRAINT `chk_role_client` CHECK (`role_client` IN ('user', 'admin')),
  CONSTRAINT CLIENT_PK PRIMARY KEY (id_client),
  CONSTRAINT email_client_UNQ UNIQUE (email_client),
  CONSTRAINT tel_client_UNQ UNIQUE (tel_client)
)ENGINE=InnoDB;
ALTER TABLE CLIENT ADD (genre_client CHAR(5) NOT NULL);


-- ----------------------------
-- Table: PANIER
-- ----------------------------
CREATE TABLE panier (
    id_panier      INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_client      INT NOT NULL,
    statut         ENUM('actif','valide','annule') NOT NULL DEFAULT 'actif',
    date_creation  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    /* date_validation DATETIME NULL, */
    
    CONSTRAINT PANIER_CLIENT_FK
        FOREIGN KEY (id_client) REFERENCES client (id_client)
        ON DELETE CASCADE,
    
    INDEX idx_client_statut (id_client, statut)
) ENGINE=InnoDB;



-- ----------------------------
-- Table: CATEGORIE
-- ----------------------------
CREATE TABLE categorie (
  id_categorie INT NOT NULL AUTO_INCREMENT,
  nom_categorie VARCHAR(50) NOT NULL,
prefixe_categorie VARCHAR(50) NOT NULL UNIQUE,
  CONSTRAINT CATEGORIE_PK PRIMARY KEY (id_categorie)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: LIVRE
-- ----------------------------
CREATE TABLE livre (
  id_livre INT NOT NULL AUTO_INCREMENT,
  titre_livre VARCHAR(100) NOT NULL,
  resume_livre TEXT NOT NULL,
  auteur_livre VARCHAR(50) NOT NULL,
  prix DECIMAL NOT NULL,
  stock INT NOT NULL,
  image_livre VARCHAR(100) NOT NULL,
  id_categorie INT ,
reduction_livre INT NOT NULL DEFAULT 0,
  CONSTRAINT LIVRE_PK PRIMARY KEY (id_livre),
  CONSTRAINT image_livre_UNQ UNIQUE (image_livre),
  CONSTRAINT LIVRE_id_categorie_FK FOREIGN KEY (id_categorie) REFERENCES CATEGORIE (id_categorie)
ON DELETE SET NULL 
)ENGINE=InnoDB;

ALTER TABLE LIVRE ADD (langue VARCHAR(5) NOT NULL DEFAULT "FR");
ALTER TABLE LIVRE ADD (public_legal VARCHAR(30) NOT NULL);



-- ----------------------------
-- Table: PRODUIT_PANIER
-- ----------------------------
CREATE TABLE produit_panier (
  id_livre INT NOT NULL,
  id_panier INT NOT NULL,
  quantite INT NOT NULL,
  CONSTRAINT PRODUIT_PANIER_PK PRIMARY KEY (id_livre, id_panier),
  CONSTRAINT PRODUIT_PANIER_id_livre_FK FOREIGN KEY (id_livre) REFERENCES LIVRE (id_livre) ON DELETE CASCADE,
  CONSTRAINT PRODUIT_PANIER_id_panier_FK FOREIGN KEY (id_panier) REFERENCES PANIER (id_panier)  ON DELETE  CASCADE
)ENGINE=InnoDB;



-- ----------------------------
-- Table: AVIS
-- ----------------------------
CREATE TABLE avis (
  id_avis INT NOT NULL AUTO_INCREMENT,
  note INT NOT NULL,
  commentaire TEXT NOT NULL,
  date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  id_livre_concerner INT NOT NULL,
  id_client_faire INT,
  email_client VARCHAR(50),
  pseudo_client VARCHAR(50) DEFAULT "Anonymous",
  CONSTRAINT AVIS_PK PRIMARY KEY (id_avis),
  CONSTRAINT AVIS_id_livre_Concerner_FK FOREIGN KEY (id_livre_concerner) REFERENCES LIVRE (id_livre) ON DELETE CASCADE,
  CONSTRAINT AVIS_id_client_FAIRE_FK FOREIGN KEY (id_client_faire) REFERENCES CLIENT (id_client) ON DELETE SET NULL 
)ENGINE=InnoDB;



-- ----------------------------
-- Table: COMMANDE
-- ----------------------------
CREATE TABLE commande (
  id_commande INT NOT NULL AUTO_INCREMENT,
  id_client_Effectuer INT NOT NULL,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  statut VARCHAR(20) NOT NULL,
  prix_total INT NOT NULL,
  CONSTRAINT COMMANDE_PK PRIMARY KEY (id_commande),
  CONSTRAINT COMMANDE_id_client_Effectuer_FK FOREIGN KEY (id_client_Effectuer) REFERENCES CLIENT (id_client)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: COMMANDE_PRODUIT
-- ----------------------------
CREATE TABLE commande_produit (
  id_commande INT NOT NULL,
  id_livre INT NOT NULL,
actif BOOLEAN DEFAULT TRUE,
  quantite_commander INT NOT NULL,
  prix_unitaire INT NOT NULL,
  CONSTRAINT COMMANDE_PRODUIT_PK PRIMARY KEY (id_commande, id_livre),
  CONSTRAINT COMMANDE_PRODUIT_id_commande_FK FOREIGN KEY (id_commande) REFERENCES COMMANDE (id_commande)  ON DELETE CASCADE,
  CONSTRAINT COMMANDE_PRODUIT_id_livre_FK FOREIGN KEY (id_livre) REFERENCES LIVRE (id_livre)  ON DELETE RESTRICT 
)ENGINE=InnoDB;


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