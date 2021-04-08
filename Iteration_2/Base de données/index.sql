ALTER TABLE `membre` 
	ADD UNIQUE membre_unique_username(`username`),
	ADD UNIQUE membre_unique_email(`email`),
	ADD INDEX  membre_index_email_password(`email`,`password`),
	ADD INDEX  membre_index_username_password(`username`,`password`);

ALTER TABLE MONUMENT
	ADD INDEX monument_index_coordonnées(estTemporaire, latitude, longitude),
	ADD INDEX monument_index_nom(estTemporaire, nomMonum);

ALTER TABLE Contribution
	ADD INDEX contribution_unique_statut_date(statutContribution, date),
	ADD INDEX contribution_unique_contributeur(contributeur, date);
	
ALTER TABLE ListeMonument
	ADD INDEX listeMonument_unique_nom(nom),
	ADD INDEX listeMonument_unique_createur(idcreateur);
	
