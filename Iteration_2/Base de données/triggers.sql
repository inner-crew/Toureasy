 
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
	
	IF NEW.numeroImage IS NOT NULL
	THEN
		signal sqlstate '45000' set message_text = 'Le numero est determiné automatiquement, merci de ne rien renseigner';
	END IF;
	
	SELECT max(numeroImage)+1 INTO maxNumero 
	from Image
	where idMonument = NEW.idMonument;
	
	SET NEW.numeroImage := maxNumero;
	
END |
DELIMITER ;
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 INSERT INTO `contribution` (`idContribution`, `monumentTemporaire`, `monumentAModifier`, `contributeur`, `moderateurDemande`, `estNouveauMonument`, `date`, `statutContribution`, `description`) VALUES (NULL, '2', NULL, '1', '1', '1', now(), 'acceptée', NULL);
 
 
 