drop database PowerAndGo;
CREATE DATABASE IF NOT EXISTS PowerAndGo
CHARACTER SET utf8
COLLATE utf8_hungarian_ci;
USE PowerAndGo;

CREATE TABLE Autok (
  autok_id INT AUTO_INCREMENT PRIMARY KEY,
  gyartmany ENUM('Skoda', 'VW', 'KIA', 'Opel', 'Renault'),
  tipus VARCHAR(10) NOT NULL,
  rendszam VARCHAR(9) NOT NULL UNIQUE,
  teljesitmeny TINYINT NOT NULL,
  gyorsulas DECIMAL(3,1) NOT NULL,
  vegsebesseg TINYINT NOT NULL,
  gumimeret VARCHAR(15) NOT NULL,
  hatotav INT NOT NULL,
  gyartasi_ev ENUM('2020', '2021', '2022', '2023')
);

CREATE TABLE Kategoriak (
   kat_id INT AUTO_INCREMENT PRIMARY KEY,
   kat_besorolas ENUM('0', '1', '2', '3', '4'),
   kat_modell_nev VARCHAR(10),
   autok_id_FK INT,
   FOREIGN KEY (autok_id_FK) REFERENCES Autok(autok_id)
);

CREATE TABLE Szemely (
    Szig_szam VARCHAR(15) PRIMARY KEY,
    jogositvany_szama VARCHAR(20) NOT NULL UNIQUE,
    jogositvany_ervenyesseg DATE NOT NULL,
    jogositvany_lejarata DATE NOT NULL,
    V_nev VARCHAR(35) NOT NULL,
    K_nev VARCHAR(35) NOT NULL,
    felh_nev VARCHAR(20) NOT NULL UNIQUE,
    felh_jelszo VARCHAR(20) NOT NULL,
    szul_datum DATE NOT NULL,
    telefon VARCHAR(19) NOT NULL,
    email VARCHAR(50) NOT NULL
);

DELIMITER //
CREATE TRIGGER trg_szemely_insert
BEFORE INSERT ON Szemely
FOR EACH ROW
BEGIN
    IF NEW.szul_datum > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A születési dátum nem lehet a jövőben.';
    END IF;
    IF YEAR(CURDATE()) - YEAR(NEW.szul_datum) < 18 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A személynek legalább 18 évesnek kell lennie.';
    END IF;
    IF NEW.jogositvany_ervenyesseg < CURDATE() - INTERVAL 10 YEAR THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A jogosítvány érvényességi dátuma nem lehet több mint 10 év.';
    END IF;
    IF NEW.jogositvany_lejarata < GREATEST(CURDATE(), NEW.jogositvany_ervenyesseg) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A jogosítvány lejárati dátuma nem lehet korábbi az érvényességi dátumnál.';
    END IF;
END//
DELIMITER ;

CREATE TABLE Felhasznalok (
    felh_id INT AUTO_INCREMENT PRIMARY KEY,
    felh_nev VARCHAR(20) NOT NULL UNIQUE,
    CHECK (felh_nev REGEXP '^[A-Za-z0-9]{8,20}$'),
    elofiz_kat ENUM('0', '1', '2', '3'),
    jogositvany_szama_FK  VARCHAR(20) NOT NULL,
    jelszo_masodik_utolso_szamjegye CHAR(2),
    FOREIGN KEY (jogositvany_szama_FK) REFERENCES Szemely(jogositvany_szama)
);

DELIMITER //
CREATE TRIGGER set_jelszo_masodik_utolso_szamjegye
BEFORE INSERT ON Felhasznalok
FOR EACH ROW
BEGIN
    DECLARE jelszo VARCHAR(10);
    DECLARE masodik CHAR(1);
    DECLARE utolso CHAR(1);
    SELECT felh_jelszo INTO jelszo
    FROM Szemely
    WHERE felh_nev = NEW.felh_nev;
    SET masodik = SUBSTRING(jelszo, 2, 1);
    SET utolso = SUBSTRING(jelszo, -1);
    SET NEW.jelszo_masodik_utolso_szamjegye = CONCAT(masodik, utolso);
END //
DELIMITER ;

CREATE TABLE Felszereltseg (
    felszereltseg_id INT AUTO_INCREMENT PRIMARY KEY,
    tolatokamera ENUM('igen', 'nem'),
    m_kormany ENUM('igen', 'nem'),
    savtarto ENUM('igen', 'nem'),
    tempomat ENUM('igen', 'nem'),
    auto_id_FK INT,
    FOREIGN KEY (auto_id_FK) REFERENCES Autok(autok_id)
);

CREATE TABLE Berles (
  berles_azon INT AUTO_INCREMENT PRIMARY KEY,
  berles_kezd DATE,
  berles_veg DATE,
  kat_FK INT,
  felh_nev_FK VARCHAR(20) NOT NULL,
  rendszam_FK VARCHAR(9),
  FOREIGN KEY (kat_FK) REFERENCES Kategoriak(kat_id),
  FOREIGN KEY (felh_nev_FK) REFERENCES Felhasznalok(felh_nev),
  FOREIGN KEY (rendszam_FK) REFERENCES Autok(rendszam)
);

DELIMITER //
CREATE TRIGGER chk_berles_kezd_veg
BEFORE INSERT ON Berles
FOR EACH ROW
BEGIN
    IF (NEW.berles_kezd IS NULL AND NEW.berles_veg IS NOT NULL) OR 
       (NEW.berles_kezd IS NOT NULL AND NEW.berles_veg IS NOT NULL AND NEW.berles_kezd > NEW.berles_veg) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A bérlés dátumaiban hiba van!';
    END IF;
END //
DELIMITER ;