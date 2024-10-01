
CREATE DATABASE IF NOT EXISTS PowerAndGo
CHARACTER SET utf8
COLLATE utf8_hungarian_ci;
USE PowerAndGo;

-- Adatbázis kódleírása | Balról jobbra |:
-- | Kategóriák | Autok | Felszereltség | Bérlés | Felhasználók | Személy
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
    teljesitmeny TINYINT CHECK(teljesitmeny IN (18, 36, 45, 50, 65)),
    gyorsulas Decimal(3,1) NOT NULL,
    vegsebesseg TINYINT NOT NULL,
    gumimeret VARCHAR(15) NOT NULL,
    hatotav INT CHECK (hatotav BETWEEN 150 AND 350) NOT NULL, 
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
    berles_kezd DATE,
    berles_veg DATE,
    
    kat_FK INT,
    FOREIGN KEY (kat_FK)
    REFERENCES Kategoriak(kat_id),
    
    felh_nev_FK VARCHAR(20) NOT NULL,
    CHECK (felh_nev_FK REGEXP '^[A-Za-z0-9]{8,20}$'),
    FOREIGN KEY (felh_nev_FK)
    REFERENCES Felhasznalok(felh_nev),
    
    rendszam_FK VARCHAR(9),
    FOREIGN KEY (rendszam_FK)
    REFERENCES Autok(rendszam)

    CHECK (
        (berles_kezd IS NULL AND berles_veg IS NULL) OR 
        (berles_kezd IS NOT NULL AND berles_veg IS NULL) OR 
        (berles_kezd IS NOT NULL AND berles_veg IS NOT NULL)
    )     
);
        -- 1. berles_kezd dátuma 'null' és a berles_veg része ugyanúgy 'null' - azaz még nem bérelték ki soha (Például: új autó kerül be.), VAGY
        -- 2. berles_kezd dátuma meg van adva és a berles_veg része 'null' (azaz FOLYAMATBAN van a bérlés), VAGY
        -- 3. berles_kezd dátuma meg van adva és a berles_veg része 'NOT NULL' azaz LEZÁRT bérlésről beszélünk (kezdő és záró intervallummal).
        -- Azaz:
            -- Mindkét dátum NULL.
            -- berles_kezd meg van adva, de berles_veg NULL.
            -- Mindkét dátum meg van adva.
--
CREATE TABLE Szemely (
    jogositvany_szama VARCHAR(15) PRIMARY KEY,
    jogositvany_ervenyesseg DATE NOT NULL,
    jogositvany_lejarata DATE NOT NULL,
    V_nev VARCHAR(35) NOT NULL,
    K_nev VARCHAR(35) NOT NULL,
    Szig_szam VARCHAR(15) NOT NULL,    -- Azért nincs "fixálva", mert külföldiek is regisztrálhatnak. Eltérő akkor a hossz.
    felh_jelszo VARCHAR(10) NOT NULL, -- Hash-elt jelszó (python bcrypt-tel kéne)
    szul_datum DATE NOT NULL,
    telefon VARCHAR(19) NOT NULL,   -- Azért nincs "fixálva", mert külföldiek is regisztrálhatnak. Eltérő akkor a hossz.
    email VARCHAR(50) NOT NULL,

    kor AS (YEAR(CURDATE()) - YEAR(szul_datum)) PERSISTENT,
    jogosítvány_ideje AS (YEAR(CURDATE()) - YEAR(jogositvany_ervenyesseg)) PERSISTENT

    -- Hibakezelések (születési dátumra és jogosítványra):
    CHECK (szul_datum <= CURDATE()),
    CHECK (YEAR(CURDATE()) - YEAR(szul_datum) >= 18),
    CHECK (jogositvany_ervenyesseg >= CURDATE() - INTERVAL 10 YEAR),    -- Kezdete az aktuális év -10 év MAX. (lejárat miatt)
    CHECK (jogositvany_lejarata >= GREATEST(CURDATE(), jogositvany_ervenyesseg)),

    -- Hibakezelések (REGEXP megkötések):
    CHECK (felh_jelszo REGEXP '^[0-9]{1,10}$'),
    CHECK (telefon REGEXP '^(\+36|0036|06)(20|30|70)[0-9]{7}$'),
    CHECK (Szig_szam REGEXP '^[A-Z]{2}[0-9]{6}$')
    -- Így viszont most a külföldieket leszűrjük, nem regisztrálhatnak!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

);

CREATE TABLE Felhasznalok (
    felh_id INT AUTO_INCREMENT ,
    felh_nev VARCHAR(20) PRIMARY KEY UNIQUE NOT NULL,    -- Egy f.nevből CSAK egy lehet az oszlopban!
    CHECK (felh_nev REGEXP '^[A-Za-z0-9]{8,20}$'),
    elofiz_kat ENUM('0', '1', '2', '3'),
    jogositvany_szama_FK  VARCHAR(15) NOT NULL,
    jelszo_masodik_utolso_szamjegye CHAR(2),            -- Beazonosításhoz a második és utolsó számjegyet szokták kérni.

    FOREIGN KEY (jogositvany_szama_FK)
    REFERENCES Szemely(jogositvany_szama)
);

DELIMITER // 

CREATE TRIGGER set_jelszo_masodik_utolso_szamjegye    -- A TRIGGER -t erre az 'oszlopra' alkalmazzuk.
BEFORE INSERT ON Felhasznalok                         -- Mielőtt a `Felhasznalok` tábla adatait beszúrja, AZELŐTT végezze el! 
FOR EACH ROW                                          -- Soronként mindegyikkel végezze el
BEGIN   -- NYITÓ 'tag'               -- Változókat veszünk fel, amiben letároljuk ideiglenesen az adatokat, hogy átadjuk majd később.      
    DECLARE jelszo VARCHAR(10);             -- Kell a jelszónak (személy táblából, a teljes)                     
    DECLARE masodik CHAR(1);                -- Második szám tárolására.
    DECLARE utolso CHAR(1);                 -- Uccsó szám tárolására.

    -- Le kell kérdezni a jelszót - sql-lel - a személy táblából:
    SELECT felh_jelszo INTO jelszo          -- jelszo változóba átmentjük az értékét.
    FROM Szemely
    WHERE jogositvany_szama = NEW.jogositvany_szama_FK;   --- ez határozza meg, hogy a Szemely táblában melyik 'rekordhoz' tartozó jelszót kell kiválasztanunk.

    -- Kinyerjük / kivesszük a második és utolsó számjegyet
    SET masodik = SUBSTRING(jelszo, 2, 1);          -- jelszo változónak a második karakterétől kezdve 1-et //azaz a második számát vessztük ki //
    SET utolso = SUBSTRING(jelszo, -1);             -- Same as previous btw.

    -- Egyberakjuk CONCAT-tel a 2 számot (pl 2, 8 => 28)
    SET NEW.jelszo_masodik_utolso_szamjegye = CONCAT(masodik, utolso);
END // -- ZÁRÓ 'tag'

DELIMITER ; 
