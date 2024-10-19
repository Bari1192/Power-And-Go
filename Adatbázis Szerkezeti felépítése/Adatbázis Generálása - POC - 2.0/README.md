# A FakerPHP(c) segítségével történő adatgenerálás .php kiterjesztésből 
# A generált adatsorok automatikusan .csv / .txt / .json formátumokban generáltathatóak. 
# A generált adatsorokat '[fajl_neve].[kiterjesztés]' formátumban az './out' mappába generálódik.

# A fájl futtatásához rendelkeznie kell DOCKER-rel (Docker Desktoppal)!
# Letöltés --> https://www.docker.com/products/docker-desktop/






## Powershell | VS Code | Bashes ##
## git clone https://github.com/ <!-- username/repository.git -->
## cd <!--repository-->
## docker compose up --build


# [Random autók adatainak generálása - Tulajdonságok]
#################################### RENDSZÁM ####################################
# Magyarországon 2022 júliusától 4 betű + 3 számmal kerülnek kiadásra új rendszámok
# Alapértelmezetten random régi és új rendszámok kerülnek generálásra.
# A generátor régi és új rendszámokat az alábbi feltételek szerint generálja:
################################# RENDSZÁM | 2022-ig #################################
# 2020 és 2022 közötti magyar rendszámokat [M, N, P, R, S, T] betűkkel KEZDŐDŐ rendszámokkal.
## 'M'-es: 2019 vége, 2020 eleje || 'N'-es: 2020 közepe || [P, R, S]: 2020 második felétől és 2021-ben ###
### A rendszám regex-szel a fentiekben meghatározott feltételek alapján generál.
################################# RENDSZÁM | 2022 után #################################
# 2022 júliusától kiadott magyar rendszámok AAAA-000-val indultak. Ebből kifolyólag (2024.10.03-ig) 'AA'-val kezdődő rendszámokból fordul elő a legtöbb.
## A generátor a BETÜKET ebből kifolyólag 'AA'-val kezdi a rendszámot és utána 'A' és 'C' között generál egy betüt, majd 'A' és 'O' közötti betü követi.
### Az utána következő számok mindkettő rendszám esetében random 0 és 9 közötti számok, ismétlődési lehetőséggel.


# [Random személy adatainak generálása - Tulajdonságok]
jogositvany_szama:              Véletlenszerűen generált jogosítvány száma.
jogositvany_ervenyesseg:        Véletlenszerűen generált dátum, amely a jogosítvány érvényességi időpontját jelzi.
jogositvany_lejarata:           A jogosítvány lejárati dátuma.
V_nev:                          Véletlenszerűen generált vezetéknév.
K_nev:                          Véletlenszerűen generált keresztnév.
Szig_szam:                      Véletlenszerűen generált személyi igazolvány szám.
felh_jelszo:                    Véletlenszerűen generált jelszó.
szul_datum:                     Véletlenszerűen generált születési dátum.
telefon:                        Véletlenszerűen generált telefonszám.
email:                          Véletlenszerűen generált e-mail cím.