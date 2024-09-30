DROP TABLE COURS;
DROP TABLE PONEYS;
DROP TABLE MONITEURS;
DROP TABLE ADHERENTS;

CREATE TABLE ADHERENTS (
  idA INT(10) NOT NULL primary key,
  nomA VARCHAR(42),
  prenomA VARCHAR(42),
  cotisation INT(5),
  poidsA INT(4),
  numeroTel int(10) NOT NULL,
  mail varchar(42) NOT NULL,
  dateNaiss varchar(42) NOT NULL
);

CREATE TABLE MONITEURS (
  idM INT(10) NOT NULL primary key,
  nomM VARCHAR(42),
  prenomM VARCHAR(42),
  numeroTel int(10),
  mailM VARCHAR(42)
);

CREATE TABLE PONEYS (
  idP INT(10) NOT NULL primary key,
  nom VARCHAR(42),
  poidsMax int(5)
);

CREATE TABLE COURS (
  duree INT(10) NOT NULL,
  nbPersMax INT (2) NOT NULL check (nbPersMax between 1 and 10), -- contrainte du nombre de personnes par cours
  dateC date NOT NULL,
  heureC INT(10) NOT NULL,
  idA INT(10) NOT NULL,
  idM INT(10) NOT NULL,
  idP INT(10) NOT NULL,
  PRIMARY KEY (idA,idM,idP)
);

ALTER TABLE COURS ADD FOREIGN KEY (idA) REFERENCES ADHERENTS (idA);
ALTER TABLE COURS ADD FOREIGN KEY (idM) REFERENCES MONITEURS (idM);
ALTER TABLE COURS ADD FOREIGN KEY (idP) REFERENCES PONEYS (idP);

-- Contraintes en trigger

delimiter |
CREATE OR REPLACE TRIGGER verifPoidsMax before insert on COURS for each row
begin 
  declare poidsMaxPoney int;
  declare poidsPersonne int;
  declare message VARCHAR(100);
  select poidsMax into poidsMaxPoney from PONEYS where idP = new.idP;
  select poidsA into poidsPersonne from ADHERENTS where idA = new.idA;
  if poidsPersonne > poidsMaxPoney then
    set message = concat("association impossible entre l'adhérent ", new.idA, " et le poney ", new.idP, ", Poids max dépassé");
    signal SQLSTATE '45000' set MESSAGE_TEXT = message;
  end if;
end |
delimiter ;

delimiter |
CREATE OR REPLACE TRIGGER updateVerifPoidsMax before update on COURS for each row
begin 
  declare poidsMaxPoney int;
  declare poidsPersonne int;
  declare message VARCHAR(100);
  select poidsMax into poidsMaxPoney from PONEYS where idP = new.idP;
  select poidsA into poidsPersonne from ADHERENTS where idA = new.idA;
  if poidsPersonne > poidsMaxPoney then
    set message = concat("association impossible entre l'adhérent ", new.idA, " et le poney ", new.idP, ", Poids max dépassé");
    signal SQLSTATE '45000' set MESSAGE_TEXT = message;
  end if;
end |
delimiter ;