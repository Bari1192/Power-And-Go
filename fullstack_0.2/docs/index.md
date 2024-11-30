# Dokumentáció




## Backend
[SORREND]
1. Egy árazási szabályzat táblát,
2. Elofizetes táblát,
3. Majd át kell írni a `lezárt_bérlések` táblát, hogy a már így generált `elofizetéseket` többé `ne array`-ből random generálja be (értéknek), hanem `FK-ként vegye át` a táblából.
4. `Factory-ban` ki kell egészítenem, hogy parkolási időt is generáljon arányosan.
5. A `folyamatban lévő bérléseket` is át kell alakítani, hogy `státusz` alapján `lezárás után` a `lezárt bérlések táblába kerüljenek` az adatok
6. `Konyvelesi táblát` is létre kell hozni, ahová a `lezárt bérlések számlák` kifizetés után `kerülnek` (hogy profilban elérhető legyen + könyvelési osztály is elérje, hogy a NAV ne szóljon be)


### Előfizetések tábla - létrehozása
1. [Entitások]-[Migráció]:
    - `elofiz_id` - Az előfizetés azonosítójának a száma. [PK],[AI].
    - `elofiz_nev` - Az előfizetésnek a megnevezése
    - `havi_dij` - Az előfizetés havi díjának összege (opcionálisan).
    - `eves_dij` - Az előfizetés éves díjának összege (opcionálisan).

2. [Seeder]:
    `elofiz_nev` - 4 előfizetési csoportot hozunk létre manuálisan.
    - Közvetlenül adatbázisba szúrjuk bele az adatokat, nem generálási folyamattal (Factory)-vel.
    - `havi_dij` összeg opció csak a `Power-Plus`,`Power-Premium`,`Power-VIP` előfizetéseknél van.
    - `havi_dij` és `eves_dij` együttese csak a `Power-VIP` -ben érhető el.

3. [Relációk]-[Model]

### Árazások tábla - létrehozása
1.  Árkategóriák & besorolások 
    - A tábla létrehozása alapvető, mivel az előfizetési csomagok adatait le kell tárolni.
    - Minden felhasználó ehhez kapcsolódik FK-n keresztül:
        - Egyszerűbb karbantartást biztosít később - árak, kedvezmények, frissítések stb.
        - Egyértelmű és egységes logika - Statikus tömb-érték (korábbi) rendszer helyett.
    [Entitások]
    - `berles_ind` - Bérlésindítási díj.
    - `vez_perc` - Vezetés (percdíj).
    - `kedv_vez` - Kedvezményes vezetés (percdíj, 6:00 - 9:00) - opcionális.
    - `parkolas_perc` - Parkolás (percdíj).
    - `foglalasi_perc` - Foglalás (percdíj, 20 perc után).
    - `kedv_parkolas_perc` Kedvezményes parkolás (percdíj).
    - `napidij` - Napidíj összege egy adott autóra vetítve.
    - `napi_km_limit`                           # A napidíjban foglalt megtehető INGYENES km-ek száma.
    - `km_dij`                      # Ingyenesen (125) megtehető km-en felüli útdíj
    - `repter_ki_felar`             # Reptéri felár transzferrel (reptérRE)
    - `repter_be_felar`             # Reptéri felár transzferrel (reptérRŐL)
    - `repter_ki_terminal`       # Reptéri felár terminálnál (reptérRE)
    - `repter_be_terminal`       # Reptéri felár terminálnál (reptérRŐL)
    - `zona_nyit_felar`               # Külső zónából való bérlés nyitási, indítási felára (nyitás)
    - `zona_zar_felar`              # Külső zónában való bérlés zárási felára (zárás)

    [Seeder]
    - Manuálisan van feltöltve mivel az előfizetési (alap) csomagok fixek.ű
    - Ezek adatai változhatnak majd, ha idővel új:
        - Előfizetési csomagokat,
        - Árakat
        - Szolgáltatásokat vezetünk be.
    [Relációk]
    - 1:N -hez | Előfizetések - Felhasználók | Mivel egy előfizetési kategóriát több felhasználó is választhat.
    - Elofizetesek | HasMany lesz a Modelben -> A Felhasznalo::class `elofiz_id` [FK], `id` [PK]-ra.
    - Felhasznalok | BelongsTo lesz a Modelben.-> Az Elofizetes::class `elofiz_id` [FK], `id` [PK]-ra.
    [Megjelenites]
    - Mivel az adatok szemléltetésekor szemantikailag hatékonyabb az előfizetés megnevezését megjeleníteni, így:
        - Frontenden a lekéréskor az `elofizetes` tábla, `elofiz_nev` entitás értékét fogjuk lekérdezni -> Ami visszaadja, hogy az aktuális felhasználó például a 'Power-VIP'előfizetéssel rendelkezik.

### A folyamatban lévő & lezárt bérlések összhangja
1.  [Logika]
    - Folyamatban lévő bérlés: 
        - `Kezdes_ido` és `status`-nak `aktív`-nak kell lennie.
    - Lezárt bérlés:
        - `Kezdes_ido`és `Vege_ido` meg kell lennie (megadva), `status`-nak `lezárt` -nak kell lennie.
2. [Megvalositas]
    - A futo_berlesek táblának tartalmaznia kell a `status`-t, állapot mező egyikét:
        1. Aktív (folyamatban), vagy
        2. Lezárt (vége van).
    Ehhez el kell érnünk, hogy:
        A bérlés lezárásakor a `futo_berlesek` adatai átkerüljenek a `lezart_berlesek` táblába.
        A `lezart_berlesek`-ben a `status`-nak `lezart`-nak kell lennie a táblában.

### Parkolási díjak és éjszakai időszakok kezelése
1. Éjszakai parkolás szabálya (22:00-07:00) Power-Plus Power-VIP esetében
    - Ez az időszak nem számít díjkötelesnek a prémium csomagok esetén.
    - Backend szinten kell meghatározni és kiszámolni a parkolási időt, ami az időzóna között van:
        1. A parkolás `kezdetét` és a parkolás `végét` kell figyelni.
        2. Ha a `berles_kezd_ido` 22:00 `ELŐTT` van & az `előfizetési kategória` Power-PLUS/VIP, akkor:
            - `kezdete` időponttól egészen 22:00-ig a `parkolási percet` adja vissza.
            - `parkolási perc` alapján (kategória és előfizetés szerint) `normal_parkolási_percdíjat` számolja `22:00-ig` és adja vissza a(z):
                - `parkolasi_perc`
                - `parkolasi_osszeg` értékeket.
        3. Ha a `berles_kezd_ido` 22:00 `UTÁN` van && az `előfizetési kategória` Power-PLUS/VIP, akkor:
            - `kezdete` időponttól egészen 07:00-ig a --> `parkolási percet` adja vissza.
            - `parkolási perc` alapján (kategória és előfizetés szerint) `normal_parkolási_percdíjat` számolja `07:00-ig` || a `berles_vege_ido` értékig. Így adja vissza a(z):
                - `parkolasi_perc`
                - `parkolasi_osszeg` értékeket.
        [Összességében]:
        - Vizsgáljuk meg, hogy 22:00 & 07:00 között parkolt-e?
        - A teljes parkolási időből kivonjuk a KEDVEZMÉNYES időszakot,
        - Kiírjuk a teljes parkolási összeget (ha nem lenne 'Plus', vagy 'VIP') -> áthúzzuk ezt az összeget (frontend),
        - Majd visszadjuk a `teljes_parkolas` - `kedv_parkolás` idejét, 
        - Amit felszorzunk az előfizetésében meghatározott `park_percdij` értékével.
        [Példa]:
        - Parkolás kezdete: 2024-11-30 21:30
        - Parkolás vége: 2024-12-01 07:30
        - Teljes parkolási idő: 10 óra
        - Kedvezményes éjszakai parkolási idő: 9 óra
        - Standard parkolási idő: 1 óra - (60p)
        - Parkolásra fizetendő részletező: 60 perc * 41 Forint (Power-VIP tagság)
        - Vezetésre fizetendő részletező: 60 perc * 41 Forint (Power-VIP tagság)
        - `berles_inditasi_dij`: 250 Ft
        - Parkolás Összege (Kedvezmény nélkül):  22 140 Ft
        - `Parkolás` Összege (kedvezménnyel):  2 460 Ft
        - `Vezetés` összege (60*50 Ft): 3 000 Ft
        [Számla]:
        - Összesen: 5 710 Ft




### Lezárt bérlések
[parkolasi_perc]

#### E-mail kiküldés alapja
Elsőnek tesztelés céljából az e-mail kiküldéséhez szükséges adatokat összegyűjtjük a táblá(k)ból:
[LezartBerlesek]
    - berles_kezd_datum,
    - berles_kezd_ido,
    - berles_veg_datum,
    - berles_veg_ido,
[Autok]
    - Auto rendszámát reláción keresztül `$this->auto->rendszam`
[Felhasználó_e-mail_cím_kinyerese]
    1. El kell jutni a [Szemelyek] táblához, amiben az e-mail van.
        Ehhez a [LezartBerlesek]-től `szemely_id_fk`-val átmegyünk a [Felhasznalok] táblába.
        Onnan a `szemely_id`-val átmegyünk a [Szemelyek] táblába.
        A [Szemelyek] táblából meg az `email` és a `k_nev` entitásokat "kivesszük".
        `email` -> kelleni fog az automata e-mail küldéshez bérlés lezárása után.
        `k_nev` -> Címzés során az Email osztályban a content() részhez kelleni fog az automata megszólítás miatt.
        Továbbá ki kellett egészíteni az API Resource Controller-t, hogy "behúzzuk" a táblákat.

[Végezetül]
    Egyesítenünk kell 4 értéket 1-1-be, azaz:
        1. `berles_kezdete` és 
        2. `berles_vege` változókat hozunk létre ezekre.
            Carbon osztály meghívásával (metódusával) össze tudjuk fűzni a 2-2 adatot 1-1-be.
            $berlesIdotartam = $berlesKezd->diffInMinutes($berlesVeg); --> Időtartamot adja vissza percekben.
        3. berles_percek -et átadni a JSON-ben.

