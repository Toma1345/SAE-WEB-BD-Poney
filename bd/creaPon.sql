CREATE TABLE ADHERENTS (
  idA INT(10) NOT NULL primary key, -- numero de licence
  nomA VARCHAR(42),
  prenomA VARCHAR(42),
  cotisation boolean NOT NULL,
  poidsA INT(4),
  numeroTel int(10) NOT NULL,
  mail varchar(42) NOT NULL,
  dateNaiss varchar(42) NOT NULL
);

CREATE TABLE MONITEURS (
  idM INT(10) NOT NULL primary key, -- numero de licence
  nomM VARCHAR(42),
  prenomM VARCHAR(42),
  numeroTelM int(10),
  mailM VARCHAR(42),
  dateNaissM VARCHAR(42),
  specialite VARCHAR(42)
);

CREATE TABLE PONEYS (
  idP INT(10) NOT NULL primary key,
  nomP VARCHAR(42),
  poidsMax int(5)
);

CREATE TABLE COURS (
  idC int(10) NOT NULL primary key,
  tarif INT(10) NOT NULL,
  duree INT(1) NOT NULL check (duree between 1 and 2), -- contrainte de l'heure ( 1 heure ou 2 )
  nbPersMax INT (2) NOT NULL check (nbPersMax between 1 and 10), -- contrainte du nombre de personnes par cours
  dateC date NOT NULL,
  heureC INT(10) NOT NULL,
  idM INT(10) NOT NULL, -- le moniteur anime ce cours 
  FOREIGN KEY (idM) REFERENCES MONITEURS(idM)
);

CREATE TABLE RESERVER(
  idC int(10),
  idP int(10) unique,
  idA int(10) unique,
  paye boolean NOT NULL, 
  primary key (idC, idP, idA),
  FOREIGN KEY (idC) REFERENCES COURS(idC),
  FOREIGN KEY (idP) REFERENCES PONEYS(idP),
  FOREIGN KEY (idA) REFERENCES ADHERENTS(idA)
);

-- Modification 13/01/2025 : les alter table ne peuvent pas être fait en sqlite (sont limités) il faut donc 
-- mettre les clés étrangères directement dans les créations des tables ci-dessus.

-- ALTER TABLE COURS ADD CONSTRAINT fk_cours FOREIGN KEY (idM) REFERENCES MONITEURS(idM);
-- ALTER TABLE RESERVER ADD CONSTRAINT fk_reserver_cours FOREIGN KEY (idC) REFERENCES COURS(idC);
-- ALTER TABLE RESERVER ADD CONSTRAINT fk_reserver_poneys FOREIGN KEY (idP) REFERENCES PONEYS(idP);
-- ALTER TABLE RESERVER ADD CONSTRAINT fk_reserver_adherents FOREIGN KEY (idA) REFERENCES ADHERENTS(idA);


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
  declare poidsMaxponey int;
  declare paiement boolean;
  declare poidsAdherent int;
  declare mes varchar(100) ;
  declare heuresAvant INT DEFAULT 0;
  declare heuresApres INT DEFAULT 0;
  declare debutCours INT;
  declare dureeCours INT;
  declare finCours INT;
  declare dateCours DATE;
  declare heureCours INT;
  declare nbPersMax INT;
  declare heureCAjout INT;
  declare dateCAjout date;
  declare fini boolean default false;
  declare lesReservations cursor for
    select idC, idP, idA from RESERVER where idC = new.idC;
  declare continue handler for not found set fini = true ;

  -- vérifier qu'un adhérent ou un poney ne sont pas pris 2 fois
  open lesReservations; 
  while not fini do 
    fetch lesReservations into idCours, idPony, idAdh;
    if not fini then 
      if idPony = new.idP then 
        set mes = concat('inscription impossible', new.idP, 'est déjà réservé') ;
        signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
      elseif idAdh = new.idA then 
        set mes = concat('inscription impossible', new.idA, 'est déjà inscrit') ;
        signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
      end if;
    end if;
  end while;
  close lesReservations;
  -- Vérifie si le cours est complet, si oui, retourne une erreur
  select IFNULL(count(*), 0) into nbInscrits from RESERVER where idC = new.idC; 
  select nbPersMax into maxInscr from RESERVER natural join COURS where idC = new.idC;
  if nbInscrits = nbPersMax then 
    set mes = concat('inscription impossible', new.idC, 'est déjà complet') ;
        signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if ;

  -- vérifier qu'un poney a 1h de repos toutes les 2h de cours
  SELECT heureC, duree, dateC INTO debutCours, dureeCours, dateCours
    FROM COURS
    WHERE idC = new.idC;
    SET finCours = debutCours + dureeCours;
    SELECT SUM(duree) INTO heuresAvant FROM COURS NATURAL JOIN RESERVER WHERE idP = NEW.idP AND dateC = dateCours AND heureC >= (debutCours - 2) AND heureC < debutCours;
    SELECT SUM(duree) INTO heuresApres FROM COURS NATURAL JOIN RESERVER WHERE idP = NEW.idP AND dateC = dateCours AND heureC <= (finCours + 200) AND heureC > debutCours;
    SET heuresAvant = heuresAvant + dureeCours;
    SET heuresApres = heuresApres + dureeCours;
    IF heuresAvant > 2 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le poney doit se reposer.';
    END IF;

    IF heuresApres > 2 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le poney doit se reposer';
    END IF;

  -- vérifie que l'adhérent a bien payé sa cotisation annuelle
  select cotisation into paiement from ADHERENTS where idA = new.idA;
  if not paiement then
  set mes = " avant de vous inscrire au cours, veuillez payer la cotisation annuelle " ;
  end if ;

  -- vérifie que le poids de l'adhérent n'est pas plus élévée que le poids max du poney
  select poidsMax, poidsA into poidsMaxponey, poidsAdherent from PONEYS natural join ADHERENTS natural join RESERVER
  where idP = new.idP and idA = new.idA;
  if poidsMaxponey < poidsAdherent then
  set mes = " inscription impossible, le poney ne peut pas porter l'adhérent " ;
  signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if ;

  -- vérifie qu'il n'existe pas deux reservations ou sur les mêmes horaires qui possèdent le même adhérent
  select count(idA) into idAdh from RESERVER natural join COURS where idA = new.idA;
  if idCours >= 1 then
  set mes = " inscription impossible, le moniteur est déjà présent dans un autre cours";
  signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if; 

  -- vérifie qu'il n'existe pas deux reservations ou sur les mêmes horaires qui possèdent le même poney
  select count(idP) into idPony from RESERVER natural join COURS where idP = new.idA;
  if idCours >= 1 then
  set mes = " inscription impossible, le moniteur est déjà présent dans un autre cours";
  signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if; 

  -- vérifie qu'il n'existe pas deux cours qui se chevauchent sur les mêmes horaires qui possèdent le même adhérent
  select count(idA) into idAdh from RESERVER natural join COURS where idA = new.idA;
  if idCours >= 1 then
    select heureC, dateC into heureCours, dateCours from RESERVER natural join COURS where idA = new.idA;
    select heureC, dateC into heureCAjout, dateCAjout from RESERVER natural join COURS where idC = new.idC;
    IF heureCours > heureCAjout AND heureCAjout < (heureCours + dureeCours) THEN
      set mes = " inscription impossible, l'adhérent est déjà présent dans un autre cours";
      signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
    end if;
  end if ; 

  -- vérifie qu'il n'existe pas deux cours qui se chevauchent sur les mêmes horaires qui possèdent le même poney
  select count(idP) into idPony from RESERVER natural join COURS where idP = new.idP;
  if idCours >= 1 then
    select heureC, dateC into heureCAjout, dateCAjout from RESERVER natural join COURS where idC = new.idP;
    IF heureCours > heureCAjout AND heureCAjout < (heureCours + dureeCours) THEN
      set mes = " inscription impossible, le poney est déjà présent dans un autre cours";
      signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
    end if; 
  end if ;
  
end |

create or replace trigger regulationReservations before update on RESERVER for each row
begin
  declare coursAvant int ;
  declare coursApres int ;
  declare idAdh int;
  declare idPony int;
  declare idCours int;
  declare nbInscrits int;
  declare maxInscr int;
  declare poidsMaxponey int;
  declare paiement boolean;
  declare poidsAdherent int;
  declare mes varchar(100) ;
  declare heuresAvant INT DEFAULT 0;
  declare heuresApres INT DEFAULT 0;
  declare debutCours INT;
  declare dureeCours INT;
  declare finCours INT;
  declare dateCours DATE;
  declare heureCours INT;
  declare nbPersMax INT;
  declare fini boolean default false;
  declare heureCAjout INT;
  declare dateCAjout date;
  declare lesReservations cursor for
    select idC, idP, idA from RESERVER where idC = new.idC;
  declare continue handler for not found set fini = true ;

  -- vérifier qu'un adhérent ou un poney ne sont pas pris 2 fois
  open lesReservations; 
  while not fini do 
    fetch lesReservations into idCours, idPony, idAdh;
    if not fini then 
      if idPony = new.idP then 
        set mes = concat('inscription impossible', new.idP, 'est déjà réservé') ;
        signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
      elseif idAdh = new.idA then 
        set mes = concat('inscription impossible', new.idA, 'est déjà inscrit') ;
        signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
      end if;
    end if;
  end while;
  close lesReservations;

  -- Vérifie si le cours est complet, si oui, retourne une erreur
  select IFNULL(count(*), 0) into nbInscrits from RESERVER where idC = new.idC; 
  select nbPersMax into maxInscr from RESERVER natural join COURS where idC = new.idC;
  if nbInscrits = nbPersMax then 
    set mes = concat('inscription impossible', new.idC, 'est déjà complet') ;
        signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if ;

  -- vérifier qu'un poney a 1h de repos toutes les 2h de cours
  SELECT heureC, duree, dateC INTO debutCours, dureeCours, dateCours
    FROM COURS
    WHERE idC = NEW.idC;
    SET finCours = debutCours + dureeCours;
    SELECT SUM(duree) INTO heuresAvant FROM COURS NATURAL JOIN RESERVER WHERE idP = NEW.idP AND dateC = dateCours AND heureC >= (debutCours - 2) AND heureC < debutCours;
    SELECT SUM(duree) INTO heuresApres FROM COURS NATURAL JOIN RESERVER WHERE idP = NEW.idP AND dateC = dateCours AND heureC <= (finCours + 200) AND heureC > debutCours;

    SET heuresAvant = heuresAvant + dureeCours;
    SET heuresApres = heuresApres + dureeCours;
    IF heuresAvant > 2 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le poney doit se reposer.';
    END IF;

    IF heuresApres > 2 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le poney doit se reposer';
    END IF;

  -- vérifie que l'adhérent a bien payé sa cotisation annuelle
  select cotisation into paiement from ADHERENTS where idA = new.idA;
  if not paiement then
  set mes = " avant de vous inscrire au cours, veuillez payer la cotisation annuelle " ;
  end if ;

  -- vérifie que le poids de l'adhérent n'est pas plus élévée que le poids max du poney
  select poidsMax, poidsA into poidsMaxponey, poidsAdherent from PONEYS natural join ADHERENTS natural join RESERVER
  where idP = new.idP and idA = new.idA;
  if poidsMaxponey < poidsAdherent then
  set mes = " inscription impossible, le poney ne peut pas porter l'adhérent " ;
  signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if ;

  -- vérifie qu'il n'existe pas deux reservations ou sur les mêmes horaires qui possèdent le même adhérent
  select count(idA) into idAdh from RESERVER natural join COURS where idA = new.idA;
  if idCours >= 1 then
  set mes = " inscription impossible, l'adhérent est déjà présent dans un autre cours";
  signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if; 

  -- vérifie qu'il n'existe pas deux reservations ou sur les mêmes horaires qui possèdent le même poney
  select count(idP) into idPony from RESERVER natural join COURS where idP = new.idA;
  if idCours >= 1 then
  set mes = " inscription impossible, le poney est déjà présent dans un autre cours";
  signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if; 
  
  -- vérifie qu'il n'existe pas deux cours qui se chevauchent sur les mêmes horaires qui possèdent le même adhérent
  select count(idA) into idAdh from RESERVER natural join COURS where idA = new.idA;
  if idCours >= 1 then
    select heureC, dateC into heureCours, dateCours from RESERVER natural join COURS where idA = new.idA;
    select heureC, dateC into heureCAjout, dateCAjout from RESERVER natural join COURS where idC = new.idC;
    IF heureCours > heureCAjout AND heureCAjout < (heureCours + dureeCours) THEN
      set mes = " inscription impossible, l'adhérent est déjà présent dans un autre cours";
      signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
    end if;
  end if ; 

  -- vérifie qu'il n'existe pas deux cours qui se chevauchent sur les mêmes horaires qui possèdent le même poney
  select count(idP) into idPony from RESERVER natural join COURS where idP = new.idP;
  if idCours >= 1 then
    select heureC, dateC into heureCAjout, dateCAjout from RESERVER natural join COURS where idC = new.idP;
    IF heureCours > heureCAjout AND heureCAjout < (heureCours + dureeCours) THEN
      set mes = " inscription impossible, le poney est déjà présent dans un autre cours";
      signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
    end if; 
  end if ;

end |

-- Trigger insert / update sur les horaires des moniteurs

create or replace trigger ajoutCours before insert on COURS for each row
begin
  declare mes varchar(100);
  declare heureCours INT;
  declare dateCours date;
  declare idCours INT;
  declare dureeCours INT;
  -- vérifie qu'il n'existe pas deux cours qui débutent sur les mêmes horaires qui possèdent le même moniteur
  select count(idC) into idCours from COURS where dateC = new.dateC and heureC = new.heureC and idM = new.idM;
  if idCours >= 1 then
    set mes = "inscription impossible, le moniteur est déjà présent dans un autre cours";
    signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if; 

  -- vérifie qu'il n'existe pas deux cours qui se chevauchent sur les mêmes horaires qui possèdent le même moniteur
  select count(idC) into idCours from COURS where dateC = new.dateC and heureC = new.heureC and idM = new.idM;
  if idCours >= 1 then
    select heureC, dateC into heureCours, dateCours from COURS where dateC = new.dateC and heureC = new.heureC and idM = new.idM;
    IF heureCours > new.heureC AND new.heureC < (heureCours + dureeCours) THEN
      set mes = " inscription impossible, le moniteur est déjà présent dans un autre cours";
      signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
    end if; 
  end if ;
end |
delimiter |

create or replace trigger ajoutCours before update on COURS for each row
begin
  declare dateCours DATE;
  declare heureCours INT;
  declare mes varchar(100);
  declare idCours INT;
  declare dureeCours INT;
  -- vérifie qu'il n'existe pas deux cours qui débutent sur les mêmes horaires qui possèdent le même moniteur
  select count(idC) into idCours from COURS where dateC = new.dateC and heureC = new.heureC and idM = new.idM;
  if idCours >= 1 then
    set mes = " inscription impossible, le moniteur est déjà présent dans un autre cours";
    signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
  end if; 

  -- vérifie qu'il n'existe pas deux cours qui se chevauchent sur les mêmes horaires qui possèdent le même moniteur
  select count(idC) into idCours from COURS where dateC = new.dateC and heureC = new.heureC and idM = new.idM;
  if idCours >= 1 then
    select heureC, dateC into heureCours, dateCours from COURS where dateC = new.dateC and heureC = new.heureC and idM = new.idM;
    IF heureCours > new.heureC AND new.heureC < (heureCours + dureeCours) THEN
      set mes = " inscription impossible, le moniteur est déjà présent dans un autre cours";
      signal SQLSTATE '45000' set MESSAGE_TEXT = mes ;
    end if;
  end if; 
end |

delimiter ;
