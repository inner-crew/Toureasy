#--mysql -u root -p toureasy < /var/www/site/S3B_S16_BRANCATTI_FRACHE_MOITRIER_ZAPP/Base_de_donnees/Creation_base.sql

#-------------------------------------------------------------------------------
#---------------------------	                        ------------------------ 
#---------------------------	 CREATION DES TABLES    ------------------------
#---------------------------	                        ------------------------
#------------------------------------------------------------------------------- 

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
 `dateCreation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `idcreateur` INT UNSIGNED NOT NULL ,
 `token` VARCHAR(20) DEFAULT NULL,
 PRIMARY KEY (`idListe`)
) ENGINE = InnoDB;

CREATE TABLE `demandeAmi` (
 `idDemandeAmi` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `idDemandeur`  INT UNSIGNED NOT NULL ,
 `dateExpiration` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `token` VARCHAR(20) DEFAULT NULL,
 `disponible` BOOLEAN NOT NULL DEFAULT TRUE ,
 PRIMARY KEY (`idDemandeAmi`)
) ENGINE = InnoDB;

CREATE TABLE `membre` (
 `idMembre` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
 `nom` VARCHAR(30) NULL DEFAULT NULL ,
 `prenom` VARCHAR(30) NULL DEFAULT NULL ,
 `sexe` ENUM('homme','femme','autre','non-renseigné') NOT NULL DEFAULT 'non-renseigné' ,
 `email` VARCHAR(256) NULL DEFAULT NULL ,
 `dateNaissance` DATE NULL DEFAULT NULL ,
 `username` VARCHAR(30) NULL DEFAULT NULL ,
 `dateInscription` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `role` TINYINT UNSIGNED NOT NULL DEFAULT '1' ,
 `password` varchar(100) DEFAULT NULL,
 `token` varchar(20) DEFAULT NULL,

 
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
 `estPrive` BOOLEAN NOT NULL DEFAULT FALSE ,
 `token` VARCHAR(20) DEFAULT NULL,
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

CREATE TABLE `auteurMonumentPrive` (
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
 `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
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

CREATE TABLE `amis` (
 `amis1` INT UNSIGNED NOT NULL COMMENT 'on convient arbitrairement que amis1 est celui qui a l id le plus petit',
 `amis2` INT UNSIGNED NOT NULL ,
 PRIMARY KEY (`amis1`, `amis2`)
) ENGINE = InnoDB;








#-----------------------------------------------------------------------------
#---------------------------	                      		------------------
#---------------------------	 		FOREIGN KEYS  		------------------
#---------------------------	                      		------------------
#----------------------------------------------------------------------------- 


ALTER TABLE image
    ADD CONSTRAINT fk_image_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);


	
ALTER TABLE favoris
    ADD CONSTRAINT fk_favoris_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);

ALTER TABLE favoris
    ADD CONSTRAINT fk_favoris_membre
    FOREIGN KEY (idMembre)
    REFERENCES membre(idMembre);
	
	
	
ALTER TABLE visite
    ADD CONSTRAINT fk_visite_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);

ALTER TABLE visite
    ADD CONSTRAINT fk_visite_membre
    FOREIGN KEY (idMembre)
    REFERENCES membre(idMembre);


ALTER TABLE demandeAmi
    ADD CONSTRAINT fk_demandeAmi_demandeur
    FOREIGN KEY (idDemandeur)
    REFERENCES membre(idMembre);


ALTER TABLE source
    ADD CONSTRAINT fk_source_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);


	
ALTER TABLE appartenanceliste
    ADD CONSTRAINT fk_appartenanceListe_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);	
	
ALTER TABLE appartenanceliste
    ADD CONSTRAINT fk_appartenanceListe_listeMonument
    FOREIGN KEY (idListe)
    REFERENCES listemonument(idListe);	


	
ALTER TABLE membre
    ADD CONSTRAINT fk_membre_role
    FOREIGN KEY (role)
    REFERENCES role(idRole);		
	
	

ALTER TABLE contribution
    ADD CONSTRAINT fk_contribution_monumentTemporaire
    FOREIGN KEY (monumentTemporaire)
    REFERENCES monument(idMonument);
	
ALTER TABLE contribution
    ADD CONSTRAINT fk_contribution_monumentAModifier
    FOREIGN KEY (monumentAModifier)
    REFERENCES monument(idMonument);	
	
ALTER TABLE contribution
    ADD CONSTRAINT fk_contribution_idContributeur
	FOREIGN KEY (contributeur)
    REFERENCES membre(idMembre);	
	
ALTER TABLE contribution
    ADD CONSTRAINT fk_contribution_idModerateur
	FOREIGN KEY (moderateurDemande)
    REFERENCES membre(idMembre);		



ALTER TABLE listeMonument
    ADD CONSTRAINT fk_listeMonument_idMembre
	FOREIGN KEY (idCreateur)
    REFERENCES membre(idMembre);

ALTER TABLE amis
    ADD CONSTRAINT fk_amis_membre1
    FOREIGN KEY (amis1)
    REFERENCES membre(idMembre);

ALTER TABLE amis
    ADD CONSTRAINT fk_amis_membre2
    FOREIGN KEY (amis2)
    REFERENCES membre(idMembre);




	



#-----------------------------------------------------------------------------
#---------------------------	                     -------------------------
#---------------------------	 		INDEX  		 -------------------------
#---------------------------	                     -------------------------
#-----------------------------------------------------------------------------

ALTER TABLE `membre` 
	ADD UNIQUE membre_unique_username(`username`),
	ADD UNIQUE membre_unique_email(`email`),
	ADD INDEX  membre_index_email_password(`email`,`password`),
	ADD INDEX  membre_index_username_password(`username`,`password`);

ALTER TABLE monument
	ADD INDEX monument_index_coordonnées(estTemporaire, latitude, longitude),
	ADD INDEX monument_index_nom(estTemporaire, nomMonum);

ALTER TABLE contribution
	ADD INDEX contribution_unique_statut_date(statutContribution, date),
	ADD INDEX contribution_unique_contributeur(contributeur, date);
	
ALTER TABLE listemonument
	ADD INDEX listeMonument_unique_nom(nom),
	ADD INDEX listeMonument_unique_createur(idcreateur);
	



#-----------------------------------------------------------------------------
#---------------------------	                         ---------------------
#---------------------------			 TRIGGERS        ---------------------
#---------------------------	                         ---------------------
#----------------------------------------------------------------------------- 


DELIMITER |
CREATE OR REPLACE TRIGGER `trigger_amis_ajoutOrdre` 
BEFORE INSERT ON `amis` 
FOR EACH ROW 
BEGIN
	DECLARE tmp INTEGER;
	IF NEW.amis1 > NEW.amis2 
	THEN 
		SET tmp := NEW.amis1;
		SET NEW.amis1 = NEW.amis2;
		SET NEW.amis2 = tmp;
	END IF;
	IF NEW.amis1 = NEW.amis2
	THEN
		signal sqlstate '45000' set message_text = 'On ne peut être amis avec soit-même (ce serait triste)';
	END IF;
	
END |
DELIMITER ;



DELIMITER |
CREATE OR REPLACE TRIGGER `trigger_contrib_moderateur`
BEFORE UPDATE ON `contribution`
FOR EACH ROW
BEGIN
	
	DECLARE V_ROLE_MODO tinyint(3) ;
	DECLARE V_PERM_Moderer tinyint(1) ;
	

	IF ( NEW.statutContribution = 'acceptée' ) THEN
		IF NEW.moderateurDemande IS NULL THEN
			signal sqlstate '45000' set message_text = 'La contribution ne peut-être acceptée avec le champ moderateurDemande  à null';

		ELSE 
		
			SELECT ROLE 
				INTO V_ROLE_MODO
				FROM MEMBRE
				WHERE IdMembre = NEW.ModerateurDemande;
			
			SELECT permModererContrib 
				INTO V_PERM_Moderer 
				FROM ROLE
				WHERE idRole = V_ROLE_MODO;
			
			IF (  V_PERM_Moderer = FALSE )
			THEN
				signal sqlstate '45000' set message_text = 'L attribut moderateurDemande correspond à un membre qui n a pas la permission de moderer les contributions';
			END IF;
			 
		END IF;
	END IF;
END |
DELIMITER ;
 
 
DELIMITER |
CREATE OR REPLACE TRIGGER `trigger_images_ajoutNumero` 
BEFORE INSERT ON `image` 
FOR EACH ROW 
BEGIN
	DECLARE maxNumero INTEGER;
	
	IF NEW.numeroImage != 0
	THEN
		signal sqlstate '45000' set message_text = 'Le numero est determiné automatiquement, merci de lui donner la valeur 0 à l insertion';
	END IF;


	SELECT max(numeroImage)+1 INTO maxNumero 
	from Image
	where idMonument = NEW.idMonument;

	IF maxNumero IS NULL
	THEN
	    SET NEW.numeroImage := 1;
	ELSE
	    SET NEW.numeroImage := maxNumero;
	END IF;
END |
DELIMITER ;
 

#-- CALL p_Image_decalageNumero(deleted_monumentID, deleted_numeroImage);

DELIMITER |
CREATE OR REPLACE PROCEDURE p_Image_decalageNumero(IN old_idMonument INT, IN old_numeroImage TINYINT )
BEGIN
	-- éxecuter la ligne suivante 
	-- CALL p_Image_decalageNumero(deleted_monumentID, deleted_numeroImage);
    UPDATE Image
	SET numeroImage = numeroImage -1
	WHERE idMonument = old_idMonument AND numeroImage > old_numeroImage;	
END | 
DELIMITER ; 


DELIMITER |
CREATE OR REPLACE TRIGGER `trigger_Source_ajoutNumero` 
BEFORE INSERT ON `source` 
FOR EACH ROW 
BEGIN
	DECLARE maxNumero INTEGER;
	
	IF NEW.numeroSource != 0
	THEN
		signal sqlstate '45000' set message_text = 'Le numero est determiné automatiquement, merci de lui donner la valeur 0 à l insertion';
	END IF;
	
	SELECT max(numeroSource)+1 INTO maxNumero 
	from Source
	where idMonument = NEW.idMonument;

	IF maxNumero IS NULL
	THEN
	    SET NEW.numeroSource := 1;
	ELSE
	    SET NEW.numeroSource := maxNumero;
	END IF;

END |
DELIMITER ;










