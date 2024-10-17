DROP TABLE COURS;
DROP TABLE PONEYS;
DROP TABLE MONITEURS;
DROP TABLE ADHERENTS;

CREATE TABLE ADHERENTS (
  idA INT(10) NOT NULL primary key, -- numero de licence
  nomA VARCHAR(42),
  prenomA VARCHAR(42),
  cotisation INT(5),
  poidsA INT(4),
  numeroTel int(10) NOT NULL,
  mail varchar(42) NOT NULL,
  dateNaiss varchar(42) NOT NULL
);

CREATE TABLE MONITEURS (
  idM INT(10) NOT NULL primary key, -- numero de licence
  nomM VARCHAR(42),
  prenomM VARCHAR(42),
  numeroTel int(10),
  mailM VARCHAR(42),
  specialite VARCHAR(42)
);

CREATE TABLE PONEYS (
  idP INT(10) NOT NULL primary key,
  nom VARCHAR(42),
  poidsMax int(5)
);

CREATE TABLE COURS (
  idC int(10) NOT NULL,
  duree INT(10) NOT NULL,
  nbPersMax INT (2) NOT NULL check (nbPersMax between 1 and 10), -- contrainte du nombre de personnes par cours
  dateC date NOT NULL,
  heureC INT(10) NOT NULL,
  idM INT(10) NOT NULL, -- animer
  primary key (idCours, idM)
);

CREATE TABLE RESERVER(
  idC int(10),
  idP int(10),
  idA int(10),
  primary key (idC, idP, idA)
)

ALTER TABLE COURS ADD FOREIGN KEY (idM) REFERENCES MONITEURS (idM);

ALTER TABLE RESERVER ADD FOREIGN KEY (idC) REFERENCES COURS (idC);
ALTER TABLE RESERVER ADD FOREIGN KEY (idP) REFERENCES PONEYS (idP);
ALTER TABLE RESERVER ADD FOREIGN KEY (idA) REFERENCES ADHERENTS (idA);


-- Contraintes en trigger

delimiter |
create or replace trigger regulationReservations before insert on RESERVER for each row
begin
declare coursAvant int ;
declare coursApres int ;
declare idAdh int;
declare idPony int;
declare idCours int;
declare nbInscrits int;
declare maxInscr int;
declare mes varchar(100) ;
declare lesReservations cursor for
  select *  from COURS where idC = new.idC;
declare continue handler for not found set fini = true ;

open lesReservations;
while not fini do 
  fetch lesReservations into idCours, idPony, idAdh;
  if not fini then 
    if idPony = new.idP then 
      set mes = concat(’inscription impossible’, new.idP, ’est déjà réservé’) ;
      signal SQLSTATE ’45000’ set MESSAGE_TEXT = mes ;
    else if idAdh = new.idA then 
      set mes = concat(’inscription impossible’, new.idA, ’est déjà inscrit’) ;
      signal SQLSTATE ’45000’ set MESSAGE_TEXT = mes ;
    end if;
  end if;
end while;

select IFNULL(count(*), 0) into nbInscrits from RESERVER where idC = new.idC;
select nbPersMax into maxInscr from RESERVER natural join COURS where idC = new.idC;
if nbInscrits = nbPersMax then 
  set mes = concat(’inscription impossible’, new.idC, ’est déjà complet) ;
      signal SQLSTATE ’45000’ set MESSAGE_TEXT = mes ;

select heureC into coursAvant from COURS where idP = new.idP and dateC = new.dateC and (heureC + duree) <= new.heureC; -- argh
select heureC into coursApres from COURS where idP = new.idP and dateC = new.dateC and heureC >= (new.heureC + new.duree); -- argh
if duree >= 2 then
set mes = concat(’inscription impossible’, new.idP, ’est au repos’) ;
signal SQLSTATE ’45000’ set MESSAGE_TEXT = mes ;
end if ;
end |
delimiter ;


