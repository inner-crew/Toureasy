 
DELIMITER |
CREATE OR REPLACE TRIGGER `trigger_amis_ajoutOrdre` 
BEFORE INSERT ON `amis` 
FOR EACH ROW 
BEGIN
	DECLARE tmp INTEGER;
	IF NEW.amis1 >= NEW.amis2 
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


-- PAS AU POINT
 CREATE OR REPLACE TRIGGER trigger_contrib_moderateur
 BEFORE INSERT OR UPDATE ON contribution
 FOR EACH ROW
 WHEN (new.moderateurDemande > 0)
 DECLARE
     V_ROLE_MODO MEMBRE.ROLE%type;
	 V_PERM_Moderer ROLE.permModererContrib%type;
 BEGIN
     SELECT ROLE 
	 INTO V_ROLE_MODO
	 FROM MEMBRE
	 WHERE IdMembre = NEW.ModerateurDemande;
	 
	 SELECT permModererContrib 
	 INTO V_PERM_Moderer 
	 FROM ROLE
	 WHERE idRole = V_ROLE_MOD;
	 
	 IF (  V_PERM_Moderer = FALSE )
	 THEN
		RAISE_APPLICATION_ERROR(-20010, 'L attribut moderateurDemande correspond à un membre qui n a pas la permission de moderer les contributions');
	 END IF;
	 

 END;

 
 
 
 
 
 
 
 