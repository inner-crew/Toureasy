

 CREATE OR REPLACE TRIGGER trigger_contrib_moderateur
 AFTER UPDATE OF moderateurDemande ON contribution
 FOR EACH ROW
 WHEN (new.moderateurDemande > 0)
 DECLARE
     V_ROLE_MODO MEMBRE.ROLE%type;
	 V_PERM_Moderer ROLE.permModererContrib%type;
 BEGIN
     SELECT ROLE 
	 INTO V_ROLE_MODO
	 FROM MEMBRE
	 WHERE IdMembre = :NEW.ModerateurDemande;
	 
	 SELECT permModererContrib 
	 INTO V_PERM_Moderer 
	 FROM ROLE
	 WHERE idRole = V_ROLE_MOD;
	 
	 IF (  V_PERM_Moderer = FALSE )
	 THEN
		RAISE_APPLICATION_ERROR(-20010, 'L attribut moderateurDemande correspond à un membre qui n a pas la permission de moderer les contributions');
	 END IF;
	 

 END;