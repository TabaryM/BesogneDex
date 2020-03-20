/* Insertion des Triggers a faire via l'interface phpMyAdmin. */

/* Aller sur la table, creer les deux declencheurs avec ce code source :
Declencheur 1:
  - Nom du declencheur : check_etat_notif_upd
  - Table : vue_notification
  - Moment : BEFORE
  - Evenement : UPDATE
Declencheur 2:
  - Nom du declencheur : check_etat_notif_ins
  - Table : vue_notification
  - Moment : BEFORE
  - Evenement : INSERT */
BEGIN
  IF NOT (NEW.etat = 'En attente' OR NEW.etat = 'Accepté' OR NEW.etat = 'Refusé') THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Etat invalide';
  END IF;
END

/* Aller sur la table, creer les deux declencheurs avec ce code source :
Declencheur 1:
  - Nom du declencheur : check_etat_projet_upd
  - Table : projet
  - Moment : BEFORE
  - Evenement : UPDATE
Declencheur 2:
  - Nom du declencheur : check_etat_notif_ins
  - Table : projet
  - Moment : BEFORE
  - Evenement : INSERT */
  BEGIN
		IF NOT (NEW.etat = 'En cours' OR NEW.etat = 'Terminé' OR NEW.etat = 'Archivé' OR NEW.etat = 'Expiré') THEN
			SIGNAL SQLSTATE '45000'
				SET MESSAGE_TEXT = 'Etat invalide';
		END IF;
	END
