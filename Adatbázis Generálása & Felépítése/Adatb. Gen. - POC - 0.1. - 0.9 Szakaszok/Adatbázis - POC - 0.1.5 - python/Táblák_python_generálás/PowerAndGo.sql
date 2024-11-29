-- 1. Autok Tábla
CREATE TABLE Autok (
    rendszam VARCHAR(10) PRIMARY KEY,
    gyartmany VARCHAR(50),
    tipus VARCHAR(50),
    teljesitmeny INT,
    gyorsulas DECIMAL(5,2),
    vegsebesseg INT,
    gumimeret VARCHAR(20),
    hatotav INT,
    gyartasi_ev INT,
    kategoria VARCHAR(20)
);

-- 2. Személy Tábla
CREATE TABLE Szemely (
    szig_szam VARCHAR(20) PRIMARY KEY,
    jogositvany_szama VARCHAR(20),
    jogositvany_ervenyesseg DATE,
    jogositvany_lejarata DATE,
    V_nev VARCHAR(50),
    K_nev VARCHAR(50),
    jelszo CHAR(4) CHECK (felh_jelszo ~ '^[0-9]{4}$'),
    szul_datum DATE,
    telefon VARCHAR(20),
    email VARCHAR(50)
);

-- 3. Felhasznalo Tábla
CREATE TABLE Felhasznalo (
    felhasznalonev VARCHAR(15) PRIMARY KEY,
    elo_fizetoi_kategoria INT CHECK (elo_fizetoi_kategoria IN (0, 1, 2, 3)),
    jelszo_masodik_negyedik CHAR(2)
    szig_szam VARCHAR(20),
    FOREIGN KEY (szig_szam) REFERENCES Szemely(szig_szam)
);
-- 4. Berlesek Tábla
CREATE TABLE Berlesek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    felhasznalonev VARCHAR(50),
    berles_kezdete DATE,
    berles_vege DATE,
    rendszam VARCHAR(10),
    elo_fizetoi_kategoria INT,
    FOREIGN KEY (felhasznalonev) REFERENCES Felhasznalo(felhasznalonev),
    FOREIGN KEY (rendszam) REFERENCES Autok(rendszam)
);

-- JELSZÓ TRIGGER
DELIMITER //
CREATE TRIGGER update_felhasznalo_jelszo
BEFORE INSERT ON Felhasznalo
FOR EACH ROW
BEGIN
    DECLARE full_password CHAR(4);
    SELECT jelszo INTO full_password FROM Szemely WHERE szig_szam = NEW.szig_szam;
    SET NEW.jelszo_masodik_negyedik = CONCAT(SUBSTRING(full_password, 2, 1), SUBSTRING(full_password, 4, 1));
END//
DELIMITER ;

