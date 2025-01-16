-- Insertion des adhérents
INSERT INTO ADHERENT (idA, nomA, prenomA, cotisation, poids, numeroTel, mailA, dateNaissA)
VALUES 
(1, 'Dupont', 'Marie', 1, 60, 1234567890, 'marie.dupont@example.com', '1990-05-15'),
(2, 'Durand', 'Paul', 0, 75, 1234567891, 'paul.durand@example.com', '1985-07-20');

-- Insertion des moniteurs
INSERT INTO MONITEUR (idM, nomM, prenomM, numeroTelM, mailM, dateNaissM, specialite)
VALUES 
(1, 'Lemoine', 'Jean', 9876543210, 'jean.lemoine@example.com', '1978-03-22', 'Dressage'),
(2, 'Moreau', 'Sophie', 9876543211, 'sophie.moreau@example.com', '1982-09-10', 'Obstacle');

-- Insertion des poneys
INSERT INTO PONEY (idP, nomP, poidsMax)
VALUES 
(1, 'Tornado', 120),
(2, 'Éclair', 100),
(3, 'Bubble', 150),
(4, 'California', 80);

-- Insertion des cours
INSERT INTO COURS (idC, nomC, tarif, duree, nbPersMax, dateC, heureC, idM)
VALUES 
(1, 'Découverte',30, 1, 1, '2025-01-16', 10, 1),
(2, 'Petite balade en foret', 50, 10, 8, '2025-01-16', 14, 2);
(3, 'Grande balade en foret', 120, 5, 8, '2025-01-16', 14, 2);


-- Insertion des réservations
INSERT INTO RESERVER (idC, idP, idA, paye)
VALUES 
(1, 1, 1, 1),
(2, 2, 2, 0);


INSERT INTO ADMIN (idA, nomA, prenomA, numeroTelA, mailA, dateNaissA)
VALUES 
(1, 'Deschamp', 'Monique', 9876543210, 'moniquedu83@example.com', '1956-03-12');