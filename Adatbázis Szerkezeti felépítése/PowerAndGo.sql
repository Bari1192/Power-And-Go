
CREATE DATABASE PowerAndGo
CHARACTER SET utf8
COLLATE utf8_hungarian_ci;
USE PowerAndGo;

-- Adatbázis kódleírása:
-- | Balról jobbra | Kategóriák | Felszereltség | Autok | Bérlés | Felhasználók | Személy
-- Rendszám (AA-CO-123 | ABC-123) elfogadható.
-- CHECK-kel biztosítom, hogy az oszlopban csak a meghatározott feltételeknek [CSAK SZÁMOKNÁL] megfelelően szerepelhessenek adatok. Helytelen adatot 'ellenőrzi'.
-- PERSISTENT-tel biztosítom, hogy nem kell újra és újra lekérdezni és kiszámítania az értéket, hanem letárolódik az adatbázisban. Gyorsabb lesz az adatlekérdezés. Aktuálisan frissül is.
-- Memória sprólás végett INT helyett TINYINT (0-255) tárolunk olyan adatokat, amik ezen a tartományon biztosan nem esnek kívül. (kor, végseb., teljesítmény)
-- Bérlések táblában ahol a kezd. dátuma meg van adva, de a bérlés végének dátuma üres, ott folytatólagos a bérlés. [Külön szűrhető]

CREATE TABLE Kategoriak (
   kat_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
   kat_besorolas ENUM('0', '1', '2', '3', '4'),
   kat_modell_nev VARCHAR(10),
   
   autok_id_FK INT,
   FOREIGN KEY (autok_id_FK)
   REFERENCES Autok(autok_id)

);

CREATE TABLE Autok (
    autok_id INT AUTO_INCREMENT PRIMARY KEY,
    gyartmany ENUM('Skoda', 'VW', 'KIA', 'Opel', 'Renault'),
    tipus VARCHAR(10) NOT NULL,
    rendszam VARCHAR(9) NOT NULL,
    teljesitmeny TINYINT CHECK(teljesitmeny IN (18,36,65,50,45)),
    gyorsulas Decimal(3,1) NOT NULL,
    vegsebesseg TINYINT NOT NULL,
    gumimeret VARCHAR(15) NOT NULL,
    hatotav INT CHECK (hatotav BETWEEN 150 AND 350) NOT NULL, -- CSEKKOLNI!
    gyartasi_ev ENUM('2020', '2021', '2022', '2023')
);

CREATE TABLE Felszereltseg (
    felszereltseg_id INT AUTO_INCREMENT PRIMARY KEY,
    tolatokamera ENUM('igen', 'nem'),
    m_kormany ENUM('igen', 'nem'),
    savtarto ENUM('igen', 'nem'),
    tempomat ENUM('igen', 'nem'),
    
    auto_id_FK INT,
    FOREIGN KEY (auto_id_FK)
    REFERENCES Autok(autok_id)
);

CREATE TABLE Berles (
    berles_azon INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    berles_kezd DATE, -- 'NOT NULL' nem kerül ide, mert nem minden regisztrált felhasználó bérel egyszerre autót.
    berles_veg DATE,
    
    kat_FK INT,
    FOREIGN KEY (kat_FK)
    REFERENCES Kategoriak(kat_id),
    
    felh_nev_FK INT NOT NULL,
    FOREIGN KEY (felh_nev_FK)
    REFERENCES Felhasznalok(felh_id),
    
    rendszam_FK VARCHAR(9),
    FOREIGN KEY (rendszam_FK)
    REFERENCES Autok(rendszam)

    CHECK (berles_veg IS NULL OR berles_veg <= CURDATE()),-- A bérlés vége mezőt ellenőrzi, hogy üres-e vagy nem kisebb-e az aktuális dátumnál.
    CHECK (berles_kezd IS NULL OR (berles_veg IS NULL OR berles_kezd IS NOT NULL)) 

);

CREATE TABLE Felhasznalok (
    felh_id INT AUTO_INCREMENT PRIMARY KEY,
    felh_nev VARCHAR(20) UNIQUE NOT NULL, -- Egy f.nevből CSAK egy lehet az oszlopban!
    elofiz_kat ENUM('0', '1', '2', '3'),
    jogositvany_szama_FK  VARCHAR(15) NOT NULL,
    jelszo_masodik_utolso_szamjegye CHAR(2),

    FOREIGN KEY (jogositvany_szama_FK)
    REFERENCES Szemely(jogositvany_szama)
);

CREATE TABLE Szemely (
    jogositvany_szama VARCHAR(15) PRIMARY KEY,
    jogositvany_ervenyesseg DATE NOT NULL,
    jogositvany_lejarata DATE NOT NULL,
    V_nev VARCHAR(35) NOT NULL,
    K_nev VARCHAR(35) NOT NULL,
    Szig_szam VARCHAR(15) NOT NULL,
    felh_jelszo VARCHAR(10) NOT NULL, -- Hash-elt jelszó (python bcrypt-tel kéne)
    szul_datum DATE NOT NULL,
    telefon VARCHAR(19) NOT NULL,
    email VARCHAR(50) NOT NULL,

    kor AS (YEAR(CURDATE()) - YEAR(szul_datum)) PERSISTENT,
    jogosítvány_ideje AS (YEAR(CURDATE()) - YEAR(jogositvany_ervenyesseg)) PERSISTENT

    -- Hibakezelések (születési dátumra és jogosítványra):
    CHECK (szul_datum <= CURDATE()),
    CHECK (YEAR(CURDATE()) - YEAR(szul_datum) >= 18),
    CHECK (jogositvany_ervenyesseg >= CURDATE() + INTERVAL 1 YEAR),
    CHECK (jogositvany_lejarata >= GREATEST(CURDATE(), jogositvany_ervenyesseg)),
);