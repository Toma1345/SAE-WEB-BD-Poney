-- généré avec chatGPT

INSERT INTO ADHERENTS (idA, nomA, prenomA, cotisation, poidsA, numeroTel, mail, dateNaiss) 
VALUES 
(1, 'Dupont', 'Jean', 300, 75, 0612345678, 'jean.dupont@mail.com', '1990-05-15'),
(2, 'Martin', 'Sophie', 250, 62, 0612345679, 'sophie.martin@mail.com', '1988-03-20'),
(3, 'Leroy', 'Paul', 350, 80, 0612345680, 'paul.leroy@mail.com', '1995-07-10');

INSERT INTO MONITEURS (idM, nomM, prenomM, numeroTel, mailM) 
VALUES 
(1, 'Durand', 'Marie', 0612345671, 'marie.durand@mail.com'),
(2, 'Moreau', 'Pierre', 0612345672, 'pierre.moreau@mail.com'),
(3, 'Blanc', 'Emma', 0612345673, 'emma.blanc@mail.com');

INSERT INTO PONEYS (idP, nom, poidsMax) 
VALUES 
(1, 'Caramel', '80kg'),
(2, 'Paillette', '70kg'),
(3, 'Eclair', '90kg');

INSERT INTO COURS (duree, nbPersMax, dateC, heureC, idA, idM, idP) 
VALUES 
(60, 8, '2024-09-30', 10, 1, 1, 1),
(45, 5, '2024-10-01', 14, 2, 2, 2),
(30, 4, '2024-10-02', 16, 3, 3, 3);