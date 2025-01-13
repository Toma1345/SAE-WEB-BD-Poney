-- Insertion des adhérents
INSERT INTO ADHERENTS (idA, nomA, prenomA, cotisation, poidsA, numeroTel, mail, dateNaiss)
VALUES 
(1, 'Dupont', 'Marie', 1, 60, 1234567890, 'marie.dupont@example.com', '1990-05-15'),
(2, 'Durand', 'Paul', 0, 75, 1234567891, 'paul.durand@example.com', '1985-07-20');

-- Insertion des moniteurs
INSERT INTO MONITEURS (idM, nomM, prenomM, numeroTelM, mailM, dateNaissM, specialite)
VALUES 
(1, 'Lemoine', 'Jean', 9876543210, 'jean.lemoine@example.com', '1978-03-22', 'Dressage'),
(2, 'Moreau', 'Sophie', 9876543211, 'sophie.moreau@example.com', '1982-09-10', 'Obstacle');

-- Insertion des poneys
INSERT INTO PONEYS (idP, nomP, poidsMax)
VALUES 
(1, 'Tornado', 120),
(2, 'Éclair', 100),
(3, 'Bubble', 150),
(4, 'California', 80);

-- Insertion des cours
INSERT INTO COURS (idC, tarif, duree, nbPersMax, dateC, heureC, idM)
VALUES 
(1, 30, 1, 5, '2025-01-15', 10, 1),
(2, 50, 2, 8, '2025-01-16', 14, 2);


-- Insertion des réservations
INSERT INTO RESERVER (idC, idP, idA, paye)
VALUES 
(1, 1, 1, 1),
(2, 2, 2, 0);
