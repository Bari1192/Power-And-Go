# Adatgeneráló Projekt – PowerAndGo - Útmutató

Ez a projekt egy autómegosztó alkalmazás háttér-adatbázisát generálja le. Az adatok generálása Docker Compose segítségével történik és az elkészült fájlokat [alapértelmezetten] CSV formátumban menti el.
A PHP szkriptek automatikusan végrehajtják az adatgenerálást a megfelelő sorrendben.

**Futtatás**
   Futtasd a következő parancsot (terminal-ban):
   docker compose up --build
Ez létrehozza a szükséges környezetet és fájlokat, majd a komponenseket a megfelelő sorrendben futtatja.

**A projekt a következő fájlokat/rekordokat hozza létre:**

1. ***auto_generator.php***: 450 rekord a szkript alapján:
    - Autok: Gyarto, Tipus, Teljesitmeny, Vegsebesseg, Gumimeret, Hatótav, Rendszam, Gyartasi_ev, Km_ora_allas fejléccel és hozzá tartozó adatsorokkal.
    - Felszereltsegek: Rendszam, Tolatokamera, Tolatoradar, Multifunkcionalis_Kormany, Savtarto, Tempomat fejléccel és hozzá tartozó adatsorokkal.
    - Kategoriak: Rendszam, Tipus, Besorolas fejléccel és hozzá tartozó adatsorokkal. 

2. ***szemely_generator.php***: 1000 rekord a szkript alapján:
    - Szemelyek: ID, V_nev, K_nev, Szul_datum, Tel, E-mail, Szig_szam, Jogos_szam, Jogos_erv_kezdete, jogos_erv_vege, Felh_jelszo fejléccel és hozzá tartozó adatsorokkal.
    - Felhasznalok: ID, Felh_nev, Jelszo, Elofizetesi_Kat fejléccel és hozzá tartozó adatsorokkal.

3. ***lezart_berlesek_generator.php***: 2750 rekord a  szkript alapján.
    - Lezart_berlesek: Berles_id, Rendszam, Kat_besorolas, Berles_kezd_ev_ho_nap, Berles_kezd_ora_perc_mp, Berles_vege_ev_ho_nap, Berles_vege_ora_perc_mp,Felh_nev fejléccel és hozzá tartozó adatsorokkal.

***Az adatok a(z) src/output mappába kerülnek ***

## Hogyan módosítd a generált adatok mennyiségét / fájlkiterjesztését:

A ***docker-compose.yml*** fájlban változtathatod meg az egyes parancssorok végén lévő szám módosításával, a ***command*** részben, az alábbiak szerint:
    **Autók számának módosítása:** php auto_generator.php [mennyiség] [csv / json]
    **Személyek számának módosítása:** php szemely_generator.php [mennyiség] [csv / json]
    **Lezárt bérlések számának módosítása:** php lezart_berlesek_generator.php  [mennyiség] [csv / json]
    **FIGYELEM!** Az adatmennyiség **megváltoztatásakor is** a program **hozzáfűzi** az új adatmennyiséget a meglévő adatsorokhoz, függetlenül a fájlkiterjesztés módosításától!

## Licenc és Felhasználási feltételek
Ez a projekt az MIT licenc alatt érhető el. Ha a kódot nyilvánosan felhasználod vagy kereskedelmi célra értékesíted, kérjük, tüntesd fel az eredeti készítőt. További részletekért lásd a [LICENSE] fájlt.

Továbbá a program által generált adatsorokban Primary Key és Foreign Key alapú összeköttetésekből áll. Mindebből eredően az adott kulcsok egyediek, így adatbázis felhasználásra optimalizált a használatuk.

[További fejlesztések várhatóak.]

