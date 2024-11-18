-- généré avec chatGPT

INSERT INTO ADHERENTS (idA, nomA, prenomA, cotisation, poidsA, numeroTel, mail, dateNaiss) VALUES
(1, 'Dupont', 'Jean', TRUE, 60, 1234567890, 'jean.dupont@example.com', '1990-01-01'),
(2, 'Martin', 'Sophie', TRUE, 55, 1234567891, 'sophie.martin@example.com', '1992-03-15'),
(3, 'Durand', 'Paul', FALSE, 70, 1234567892, 'paul.durand@example.com', '1985-07-12'),
(4, 'Bernard', 'Claire', TRUE, 50, 1234567893, 'claire.bernard@example.com', '2000-09-25'),
(5, 'Petit', 'Luc', TRUE, 80, 1234567894, 'luc.petit@example.com', '1995-11-30');

INSERT INTO MONITEURS (idM, nomM, prenomM, numeroTelM, mailM, dateNaissM, specialite) VALUES
(1, 'Lemoine', 'Julie', 1234567895, 'julie.lemoine@example.com', '1980-05-20', 'Dressage'),
(2, 'Morel', 'Pierre', 1234567896, 'pierre.morel@example.com', '1975-10-15', 'Obstacle'),
(3, 'Rousseau', 'Marie', 1234567897, 'marie.rousseau@example.com', '1982-02-10', 'Endurance'),
(4, 'Faure', 'Nicolas', 1234567898, 'nicolas.faure@example.com', '1988-08-22', 'Pony Games'),
(5, 'Girard', 'Emma', 1234567899, 'emma.girard@example.com', '1990-12-05', 'Voltige');

INSERT INTO PONEYS (idP, nomP, poidsMax) VALUES
(1, 'Bella', 65),
(2, 'Spirit', 60),
(3, 'Rainbow', 50),
(4, 'Tornado', 70),
(5, 'Luna', 55);

INSERT INTO COURS (idC, tarif, duree, nbPersMax, dateC, heureC, idM) VALUES
(1, 20, 1, 5, '2024-11-19', 10, 1),
(2, 30, 2, 4, '2024-11-19', 14, 2),
(3, 25, 1, 6, '2024-11-20', 9, 3),
(4, 35, 2, 3, '2024-11-20', 15, 4),
(5, 40, 1, 8, '2024-11-21', 10, 5);


INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 1, 3, TRUE);
-- ERREUR ATTENDUE : "Avant de vous inscrire au cours, veuillez payer la cotisation annuelle"

INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 3, 5, TRUE);
-- ERREUR ATTENDUE : "Inscription impossible, le poney ne peut pas porter l'adhérent"

INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 2, 1, TRUE);
INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 3, 1, TRUE);
-- ERREUR ATTENDUE : "Inscription impossible, l'adhérent est déjà inscrit"

INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 1, 2, TRUE);
INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 2, 4, TRUE);
INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 3, 5, TRUE);
INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 4, 2, TRUE);
INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 5, 1, TRUE);
-- ERREUR ATTENDUE : "Inscription impossible, le cours est déjà complet"

INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (1, 1, 1, TRUE);
INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (2, 1, 1, TRUE);
-- ERREUR ATTENDUE : "Inscription impossible, le poney ou l'adhérent est déjà présent dans un autre cours"
