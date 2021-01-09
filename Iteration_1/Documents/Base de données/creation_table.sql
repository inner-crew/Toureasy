
CREATE TABLE `role` (
 `idRole` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `nomRole` VARCHAR(30) NOT NULL ,
 `permModererContrib` BOOLEAN NOT NULL DEFAULT FALSE ,
 `permProposerContrib` BOOLEAN NOT NULL DEFAULT FALSE ,
 `permAdministration` BOOLEAN NOT NULL DEFAULT FALSE , 
 PRIMARY KEY (`idRole`)
) ENGINE = InnoDB;

CREATE TABLE `listemonument` (
`nom` VARCHAR(40) NOT NULL ,
 `idListe` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `description` VARCHAR(200) NOT NULL DEFAULT 'Description absente' ,
 `visibilite` SET('toutLeMonde','moiUniquement','UtilisateurAvecLien') NOT NULL DEFAULT 'moiUniquement' ,
 `dateCreation` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `idcreateur` INT UNSIGNED NOT NULL ,
 PRIMARY KEY (`idListe`)
) ENGINE = InnoDB;

CREATE TABLE `membre` (
 `idMembre` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `nom` VARCHAR(30) NULL DEFAULT NULL ,
 `prenom` VARCHAR(30) NULL DEFAULT NULL ,
 `sexe` ENUM('homme','femme','autre','non-renseigné') NOT NULL DEFAULT 'non-renseigné' ,
 `email` VARCHAR(256) NULL DEFAULT NULL ,
 `dateNaissance` DATE NULL DEFAULT NULL ,
 `username` VARCHAR(30) NULL DEFAULT NULL ,
 `dateInscription` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `role` TINYINT UNSIGNED NOT NULL DEFAULT '1' ,
 `password` VARCHAR(100) NOT NULL, 
 PRIMARY KEY (`idMembre`)
) ENGINE = InnoDB;

CREATE TABLE `monument` (
 `idMonument` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `nomMonum` VARCHAR(100) NULL DEFAULT NULL ,
 `descCourte` VARCHAR(80) NULL DEFAULT NULL ,
 `descLongue` VARCHAR(2000) NULL DEFAULT NULL ,
 `longitude` DECIMAL(9,6) NULL DEFAULT NULL ,
 `latitude` DECIMAL(9,6) NULL DEFAULT NULL ,
 `estTemporaire` BOOLEAN NOT NULL DEFAULT FALSE , 
 PRIMARY KEY (`idMonument`)
) ENGINE = InnoDB;

CREATE TABLE `visite` (
 `idMonument` INT UNSIGNED NOT NULL ,
 `idMembre` INT UNSIGNED NOT NULL ,
 `dateVisite` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 PRIMARY KEY (`idMonument`, `idMembre`)
) ENGINE = InnoDB;

CREATE TABLE `favoris` (
 `idMonument` INT UNSIGNED NOT NULL ,
 `idMembre` INT UNSIGNED NOT NULL ,
 PRIMARY KEY (`idMonument`, `idMembre`)
) ENGINE = InnoDB;

CREATE TABLE `image` (
 `numeroImage` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Numéro de l\'image parmis les images illustrant le monument' ,
 `idMonument` INT UNSIGNED NOT NULL ,
 `urlImage` VARCHAR(200) NOT NULL COMMENT 'Chemin géneré automatiquement permettant de retrouver l\'image sur le serveur' ,
 PRIMARY KEY (`numeroImage`, `idMonument`)
) ENGINE = InnoDB;

CREATE TABLE `source` (
 `numeroSource` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Numéro de la source parmis les sources accompagnant la description du monument' ,
 `idMonument` INT UNSIGNED NOT NULL ,
 `lienSource` VARCHAR(400) NOT NULL ,
 PRIMARY KEY (`numeroSource`, `idMonument`)
) ENGINE = InnoDB;

CREATE TABLE `contribution` (
 `idContribution` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `monumentTemporaire` INT UNSIGNED NOT NULL ,
 `monumentAModifier` INT UNSIGNED NULL DEFAULT NULL ,
 `contributeur` INT UNSIGNED NOT NULL ,
 `moderateurDemande` INT UNSIGNED NULL DEFAULT NULL ,
 `estNouveauMonument` BOOLEAN NOT NULL ,
 `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `statutContribution` ENUM('enAttenteDeTraitement','acceptée','refusée') NOT NULL ,
 `description` VARCHAR(400) NULL DEFAULT NULL COMMENT 'sert au contributeur à synthétiser et expliquer les modifications qu il a apportées.\r\nNULL dans le cas d un nouveau monument.' ,
 PRIMARY KEY (`idContribution`)
) ENGINE = InnoDB;

CREATE TABLE `appartenanceliste` (
 `numeroDansListe` SMALLINT UNSIGNED NOT NULL ,
 `idListe` INT UNSIGNED NOT NULL ,
 `idMonument` INT UNSIGNED NOT NULL ,
 PRIMARY KEY (`idListe`, `idMonument`)
) ENGINE = InnoDB;


