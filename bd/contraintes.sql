-- Trigger : Vérifie que la cotisation de l'adhérent est réglée avant de participer à un cours
CREATE TRIGGER verif_cotisation_avant_cours
BEFORE INSERT ON cours
FOR EACH ROW
BEGIN
    SELECT CASE
        WHEN (SELECT cotisation_reglee FROM adherents WHERE id = NEW.adherent_id) = 0 THEN
            RAISE(ABORT, "La cotisation annuelle de l'adhérent n'est pas réglée.")
    END;
END;

-- Trigger : Vérifie que le cours est réglé avant la participation
CREATE TRIGGER verif_paiement_cours
BEFORE UPDATE OF est_regle ON cours
FOR EACH ROW
BEGIN
    SELECT CASE
        WHEN NEW.est_regle = 0 THEN
            RAISE(ABORT, "Le cours doit être réglé avant la participation.")
    END;
END;

-- Trigger : Vérifie que le poids de l'adhérent ne dépasse pas le poids supporté par le poney
CREATE TRIGGER verif_poids_adhérent_poney
BEFORE INSERT ON cours
FOR EACH ROW
BEGIN
    SELECT CASE
        WHEN (SELECT poids FROM adherents WHERE id = NEW.adherent_id) >
             (SELECT poids_max_supporte FROM poneys WHERE id = NEW.poney_id) THEN
            RAISE(ABORT, "Le poids de l'adhérent dépasse la capacité maximale du poney.")
    END;
END;

-- Trigger : Vérifie qu'un poney a 1h de repos toutes les 2h de cours
CREATE TRIGGER verif_repos_poney
BEFORE INSERT ON cours
FOR EACH ROW
BEGIN
    SELECT CASE
        WHEN (SELECT heures_travail_cumulees FROM poneys WHERE id = NEW.poney_id) >= 2 THEN
            RAISE(ABORT, "Le poney doit avoir 1h de repos après 2h de cours consécutives.")
    END;
END;

-- Trigger : Vérifie qu'un moniteur, un poney ou un adhérent ne sont pas sollicités pour deux cours simultanés
CREATE TRIGGER verif_cours_chevauchant
BEFORE INSERT ON cours
FOR EACH ROW
BEGIN
    -- Vérifie que le moniteur n'a pas deux cours qui se chevauchent
    SELECT CASE
        WHEN EXISTS (
            SELECT 1
            FROM cours
            WHERE moniteur_id = NEW.moniteur_id
              AND date = NEW.date
              AND (
                (heure BETWEEN heure AND heure + duree) OR
                (heure + duree BETWEEN heure AND heure + duree)
              )
        ) THEN
            RAISE(ABORT, "Le moniteur est déjà assigné à un autre cours à ce moment.")
    END;

    -- Vérifie que le poney n'a pas deux cours qui se chevauchent
    SELECT CASE
        WHEN EXISTS (
            SELECT 1
            FROM cours
            WHERE poney_id = NEW.poney_id
              AND date = NEW.date
              AND (
                (heure BETWEEN heure AND heure + duree) OR
                (heure + duree BETWEEN heure AND heure + duree)
              )
        ) THEN
            RAISE(ABORT, "Le poney est déjà assigné à un autre cours à ce moment.")
    END;

    -- Vérifie que l'adhérent n'a pas deux cours qui se chevauchent
    SELECT CASE
        WHEN EXISTS (
            SELECT 1
            FROM cours
            WHERE adherent_id = NEW.adherent_id
              AND date = NEW.date
              AND (
                (heure BETWEEN heure AND heure + duree) OR
                (heure + duree BETWEEN heure AND heure + duree)
              )
        ) THEN
            RAISE(ABORT, "L'adhérent est déjà assigné à un autre cours à ce moment.")
    END;
END;

-- Check : Le nombre de personnes dans un cours ne doit pas dépasser 10 et doit être au moins égal à 1
CREATE TRIGGER verif_nombre_participants
BEFORE INSERT ON cours
FOR EACH ROW
BEGIN
    SELECT CASE
        WHEN (SELECT COUNT(*) FROM cours WHERE date = NEW.date AND heure = NEW.heure AND collectif = 1) >= 10 THEN
            RAISE(ABORT, "Le nombre maximum de participants pour ce cours est atteint.")
        WHEN NEW.collectif = 1 AND (SELECT COUNT(*) FROM cours WHERE date = NEW.date AND heure = NEW.heure) < 1 THEN
            RAISE(ABORT, "Un cours collectif doit comporter au moins un participant.")
    END;
END;

-- Check : La durée d'un cours est soit de 1h, soit de 2h
-- ALTER TABLE cours
-- ADD CONSTRAINT check_duree CHECK (duree IN (1, 2));
