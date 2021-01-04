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



ALTER TABLE source
    ADD CONSTRAINT fk_source_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);


	
ALTER TABLE appartenanceListe
    ADD CONSTRAINT fk_appartenanceListe_monument
    FOREIGN KEY (idMonument)
    REFERENCES monument(idMonument);	
	
ALTER TABLE appartenanceListe
    ADD CONSTRAINT fk_appartenanceListe_listeMonument
    FOREIGN KEY (idListe)
    REFERENCES listeMonument(idListe);	


	
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
	FOREIGN KEY (idMembre)
    REFERENCES membre(idMembre);		
