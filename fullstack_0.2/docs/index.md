# Programspecifikációs Dokumentum

## Bevezetés és Áttekintés

A rendszer célja egy átfogó, elektromos járműflottából álló autómegosztó-alkalmazás `Dashboard / Admin felületének` megvalósítása. Mindezeket hiteles és a valóságot mélyen tükröző adatok, adatmodellek nyújtanak átfogó képet.

Funkcionalitása átível az egyszerűbb adatkezelések ösvényén, hiszen egy kézből nyújt átfogó megvalósítási képet:

- A járművek flottakezelési mechanikájáról,
- Járművek részletes felszereltségéről,
- Dinamikus Kategóricsoportokba osztásukól,
- Bérlések, meghibásodások, bejelentések kezeléséről,
- Felhasználók és dolgozók kezeléséről, jogosultságaikról és adatvédelmeikről,
- Az automata számlázási, büntetés kiállítási és jármű forgalomba helyezés/kivonási rendszerről,
- Ügyfél-előfizetési rendszerről és azok adaptív kezeléséről,(_kedvezmények, árazások_)
- Továbbá mindezeket kibővítve még átfogóbb beépített és frissülő statisztikákkal, kimutatásokkal implementálva támogatja a maximális hatékonyság kifejtését.

Az adatbázis-kapcsolat `RESTful API`-n keresztül biztosított, mely hozzáférést nyújt az adatokhoz. Az adatlekérési pontok kialakításának elsődleges célja a `Frontend`-en való megjelenítés és vizualizáció. Ennek köszönhetően felhasználó-és fejlesztőbarát szemlélettel kerül kialakításra az elkészült alkalmazás.

## Telepítés és Konfiguráció

A rendszer inicializálását és az első indítását a `start.sh` fájl végzi el.

```bash
sh start.sh
```

**_Ez a szkript biztosítja az alábbi lépések automatikus futtatását:_**

1. Adatbázis táblák létrehozását, a **migrációk lefutnak**, és az adatbázis a legfrissebb struktúrával jön létre.

2. Adatok inicializálását az adatbázisba **seeder**-ek segítségével végzi a rendszer. Előre definiált **struktúra alapján** feltöltésre kerülnek az adatokkal.

3. Tesztesetek, az előre definiált tesztesetek lefutnak, biztosítva a rendszer stabilitását, annak kifogástalan működését.

4. Konténerek ellenőrzését. A Docker konténerek elindulnak és működésükről / futásukról visszajelzést kapunk.

## Adatbázis struktúra és Modellek

### Fleet Modul

A **Fleet modul** felelős a flotta adatok kezeléséért. Ez a modul tartalmazza az elektromos járműflották adatait, beleértve a:

- **Gyártót**,
- **Típust**,
- **Teljesítményt**,
- **Végsebességet**,
- A **gumiabroncsok** méretét és sorozatszámát
- A gépjármű **hatótávolságát** kilóméterbe vetítve.

A Fleet modul `kapcsolódik` továbbá a **Cars** és a **Users** modulokhoz is, biztosítva az adatkapcsolatot a flotta-jármű-bérlő hierarchia rendszerben.

---
### Categories Modul

#### Áttekintés
A **Categories modul** felelős a járműkategóriák kezeléséért. A kategóriák a flották teljesítménye alapján kerülnek besorolásra, és kulcsfontosságú kapcsolatot biztosítanak az árképzés és a napi bérlési funkciók számára.

---

### Cars Modul

A **Cars modul** felelős az egyedi járművek kezeléséért. Ez a modul tartalmazza az egyes autók:

- **Rendszámát**,
- **Töltöttségét (százalék és kW)**,
- **Becsült hatótávját**,
- **Kilométeróra állását**,
- **Gyártási évét**,
- Valamint kapcsolódik a **flottákhoz**, **kategóriákhoz**, **felszereltségekhez** és az aktuális **állapothoz**.

A Cars modul szoros **kapcsolatban** áll a **Fleet** és **Users** modulokkal, biztosítva az adatkapcsolatot a járművek, flották és bérlők között.

---

### Migrációk | Nézetek (Views)

Az alábbi nézeteket (views) az adatbázis migrációk automatikusan létrehozzák. A nézetek célja az adatok aggregálása és egyszerűsített lekérdezések biztosítása a program üzleti logikájában.

#### Nézetek (Views)

1.  `most_rented_cars`
    - **Cél**: A legtöbbet bérelt járműtípusok listázása.
    - **Forrás táblák**:
      - `car_user_rents`
      - `cars`
      - `fleets`
    - **Törlés**: Migrációs folyamat befejezte után az adatbázisba `admin` / `root` felhasználóként belépve, az alábbi parancs kiadásával:
      ```sql
      DROP VIEW IF EXISTS most_rented_cars;
      ```

---

2. `toltes_buntetesek`
   - **Cél**: Töltési büntetésekhez kapcsolódó járművek listázása.
   - **Forrás táblák**:
     - `cars`
     - `bills`
   - **Törlés**: Migrációs folyamat befejezte után az adatbázisba `admin` / `root` felhasználóként belépve, az alábbi parancs kiadásával:
     ```sql
     DROP VIEW IF EXISTS most_rented_cars;
     ```

---

3. `user_rents_db`
   - **Cél**: Felhasználók bérléseinek összesítése.
   - **Forrás táblák**:
     - `car_user_rents`
     - `persons`
   - **Törlés**: Migrációs folyamat befejezte után az adatbázisba `admin` / `root` felhasználóként belépve, az alábbi parancs kiadásával:
     ```sql
     DROP VIEW IF EXISTS user_rents_db;
     ```

---

4. `SzamlakCsoportositva`
   - **Cél**: Számlák típuscsoportok alapján való rendezése, darabszám szerint csoportosítva.
   - **Forrás táblák**:
     - `bills`
   - **Törlés**: Migrációs folyamat befejezte után az adatbázisba `admin` / `root` felhasználóként belépve, az alábbi parancs kiadásával:
     ```sql
     DROP VIEW IF EXISTS SzamlakCsoportositva;
     ```

---

Ez a szerkezet logikusan elrendezi a nézetfájlokat. Elkülöníti őket a többi migrációtól, ugyanakkor kiemeli a használatuk előnyeit, üzleti követelményeiket.

---

#### **Migrációk** | Táblák

---

- **Fleets**
  - `id` (int) – Elsődleges kulcs.
  - `gyarto` (string) – Gyártó neve (max. 30 karakter).
  - `tipus` (string) – Jármű típusa (max. 30 karakter).
  - `teljesitmeny` (int) – Teljesítmény (kW).
  - `vegsebesseg` (int) – Maximális sebesség (km/h).
  - `gumimeret` (string) – Gumi mérete (pl. 165|65-R15).
  - `hatotav` (int) – Hatótávolság (km).

---
- **categories**:
  - `id` (unsigned integer): Egyedi azonosító (elsődleges kulcs).
  - `kat_besorolas` (unsigned tiny integer): Kategória besorolása (egyedi).
  - `teljesitmeny` (unsigned tiny integer): A kategóriához tartozó teljesítmény kW-ban.

---

- **Cars**:
  - `id` (int) – Elsődleges kulcs.
  - `rendszam` (string) – Egyedi rendszám (max. 10 karakter).
  - `toltes_szaz` (float) – Töltöttség százalékban (0–100%).
  - `toltes_kw` (float) – Töltöttség kW-ban.
  - `becs_tav` (float) – Becsült hatótáv (km).
  - `status` (foreign key) – Kapcsolat a **carstatus** táblával.
  - `kategoria` (foreign key) – Kapcsolat a **categories** táblával.
  - `felszereltseg` (foreign key) – Kapcsolat az **equipments** táblával.
  - `flotta_azon` (foreign key) – Kapcsolat a **fleets** táblával.
  - `kilometerora` (int) – Kilométeróra állása.
  - `gyartasi_ev` (year) – Gyártási év.

---

## Modell

### Fleet Modell

- **Főbb attribútumok**:
  - `protected $fillable`:
    - `gyarto`, `tipus`, `teljesitmeny`, `vegsebesseg`, `gumimeret`, `hatotav`.
- **Relációk / kapcsolatok**:
  - `cars`: **Egy** flotta **típus** _(rekord)_ **több autóhoz** kapcsolódik (`HasMany` kapcsolat).

---
### Category Modell

- **Főbb attribútumok**:
  - `protected $fillable`:
    - A `Category` modell nem rendelkezik kitöltött `$fillable` attribútummal, mivel az adatokat az adatsorai **dinamikusan generálódnak**, felhasználva a `fleets` modult. 

- **Relációk / kapcsolatok**:
  - **`autok`**: **Egy** kategóriához **több** autó tartozhat (`HasMany` kapcsolat).  
  - **`arazasok`**: **Egy** kategóriához **több** árképzési adat tartozhat (`HasMany` kapcsolat).  
  - **`napiBerlesek`**: **Egy** kategóriához **több** napi bérlés kapcsolódhat (`HasMany` kapcsolat).  

---

### Car Modell

- **Főbb attribútumok**:
  - `protected $fillable`:
    - `rendszam`, `toltes_szaz`, `toltes_kw`, `becs_tav`, `status`, `kategoria`, `felszereltseg`, `flotta_azon`, `kilometerora`, `gyartasi_ev`.
- **Relációk / kapcsolatok**:

  - **`fleet`**: Egy autó tartozik egy flottához (`BelongsTo` kapcsolat).

  - **`kategoria`**: Az autók egy kategóriához tartoznak (`BelongsTo` kapcsolat).

  - **`carstatus`**: Az autók aktuális állapotát tárolja (`BelongsTo` kapcsolat).

  - **`lezartberlesek`**: Egy autóhoz több lezárt bérlés tartozhat (`HasMany`  
    kapcsolat).

  - **`szamlak`**: Egy autóhoz több számla kapcsolódhat (`HasMany` kapcsolat).

  - **`tickets`**: Egy autóhoz több jegy kapcsolódhat (`HasMany` kapcsolat).

  - **`users`**: Az autók és felhasználók között sok-a-sok kapcsolat van, bérlési
    részletekkel kiegészítve (`BelongsToMany` kapcsolat).

## Factory

### CarFactory

A **CarFactory** felelős a járművek automatikus generálásáért. Ez különösen hasznos a tesztek és seed-ek során, ahol nagyszámú valósághű adatot kell az adatbázisba hatékonyan _- és redundancia mentesen -_ kell előállítani.

#### Generált Adatok

- **Flotta adatok**: A járművek generálása során a flotta véletlenszerűen kerül kiválasztásra, de bizonyos arányok betartásával:

  - **Flotta ID kiválasztás arányai**:
    - **85%** - Gyakoribb flotta típusok (pl. VW e-up! vagy Skoda Citigo-e-iV).
    - **10%** - Ritkább, vagy specifikusabb flotta típusok (pl. Opel Vivaro-e).
    - **5%** - Exkluzív flotta típusok (pl. KIA Niro-EV).

- **Töltöttségi állapot (`toltes_szaz`) és kW érték (`toltes_kw`)**
  - **Százalékos töltöttség (`toltes_szaz`)**:
    - Véletlenszerűen generált érték 15% és 100% között, két tizedesjegy pontossággal.
  - **Teljesítmény alapján számított töltöttség kW-ban (`toltes_kw`)**:
    - Formula: `teljesitmeny * (toltes_szaz / 100)`, egy tizedesjegy pontossággal.
  - **Vonatkozó kód részlete**:
    ```php
    <?php
    $toltes_szazalek = fake()->randomFloat(2, 15, 100);
    $toltes_kw = round($flottaTipus->teljesitmeny * ($toltes_szazalek / 100), 1);
    ```
- **Becsült hatótáv**:
- A hatótáv a flottán belüli jármű hivatalosan meghatározott hatótáv és töltöttségi teljesítmény alapján számítódik:

  - Számítás mechanikája: (`hatotav` / `teljesitmeny`) \* `toltes_kw`.
  - **Egy tizedesjegy** pontossággal generálódik.

  - **Kód részlete**:
    ```php
    <?php
    $becsultHatotav = round(($flottaTipus->hatotav / $flottaTipus->teljesitmeny) * $toltes_kw, 1);
    ```

- **Egyedi rendszám**:
  A rendszámot a **magyar szabvány** szerint generálja beépített **regex** kód használatával. Erre építve véletlenszerűen **új** vagy **régi** formátumú rendszámok jönnek létre:

  - **Új formátum**: `AA[A-C][A-O]-[0-9]{3}` _(pl. "AACO-123")_.
  - **Régi formátum**: `(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}` _(pl. "SSA-456")_.

  - **A generált rendszámok egyedisége biztosított**:
    - Minden generált rendszámot egy tömbben tárolunk el, mely minden futási _(generálási)_ ciklusban ellenőrzi, hogy az adott rendszám legenerálása megtörtént-e.
    - Amennyiben a generált rendszám korábban már létrejött, a ciklus újra generál egyet, ezzel megőrizve az ismétlődés elkerülését, az egyediség garanciáját.
  - **Kód inspekció**:
    ```php
    <?php
    private function rendszamGeneralasUjRegi(): string
    {
        static $generaltRendszamok = [];
        do {
            $rendszamUjRegi = random_int(0, 1);
            $rendszam = $rendszamUjRegi > 0
                ? strtoupper(fake()->regexify('AA[A-C][A-O]-[0-9]{3}'))
                : strtoupper(fake()->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
        } while (in_array($rendszam, $generaltRendszamok));
        $generaltRendszamok[] = $rendszam;
        return $rendszam;
    }
    ```

- **Kilométeróra állás (`kilometerora`)**  
   A gyártási év funkciójának magja, az évszám szerinti becsülés a már megtett futásteljesítmény megállapítására:

  - **Például**:
    - 2019: 50,000–60,000 km.
    - 2023: 20,000–30,000 km.
  - **Kód inspekció**:

    ```php
    <?php

    private function kmOraAllasGeneralas(int $gyartasiEv): int
    {
        return match ($gyartasiEv) {
            2019 => random_int(50000, 60000),
            2020 => random_int(40000, 60000),
            2021 => random_int(30000, 40000),
            2022 => random_int(25000, 35000),
            2023 => random_int(20000, 30000),
            default => 0,
        };
    }
    ```

## Seeder

### FleetSeeder

- **Reprezentatív létrehozott adatok**:

  ```json
  [
    {
      "gyarto": "VW",
      "tipus": "e-up!",
      "teljesitmeny": 18,
      "vegsebesseg": 130,
      "gumimeret": "165|65-R15",
      "hatotav": 135
    }
  ]
  ```

---
### CategorySeeder
- **Reprezentatív létrehozott adatok**:

  ```json
  [
    {
    "id": 1,
    "kat_besorolas": 1,
    "teljesitmeny": 18
    },
    {
    "id": 2,
    "kat_besorolas": 2,
    "teljesitmeny": 33
    },
  ]
  ```
---
### CarSeeder

- **Cél**: 500 autó generálása a `CarFactory`-ben deklarált egyedi mechanizmus segítségével.
- A **CarFactory** a következőképpen használható a Seeder fájlban:
  ```php
  <?php
  $cars = Car::factory(500)->make()->toArray();
  DB::table('cars')->insert($cars);
  ```

---

## API Végpontok

### Fleets Modul Végpontok

1. **GET** `/api/fleets`

   - **Leírás**: A teljes flotta tábla adatainak megjelenítése, lekérése.
   - **Controller metódus**: `FleetController@index`
   - **Válasz _reprezentatív_ eredménye**:

     ```json
     [
       {
         "flotta_id": 1,
         "gyarto": "VW",
         "tipus": "e-up!",
         "teljesitmeny": 18,
         "vegsebesseg": 130,
         "gumimeret": "165|65-R15",
         "hatotav": 135
       }
     ]
     ```

2. **POST** `/api/fleets`

   - **Leírás**: Új flotta létrehozása.
   - **Validáció**: `StoreFleetRequest` fájl végzi.
   - **Példa _reprezentatív_ adatbeszúrás eredménye**:
     ```json
     {
       "gyarto": "Skoda",
       "tipus": "Citigo-e-iV",
       "teljesitmeny": 36,
       "vegsebesseg": 130,
       "gumimeret": "165|65-R16",
       "hatotav": 265
     }
     ```

3. **PUT** `/api/fleets/{id}`

   - **Leírás**: Létező flotta frissítése.
   - **Validáció**: `UpdateFleetRequest`.

4. **DELETE** `/api/fleets/{id}`
   - **Leírás**: Flotta törlése.
   - **Validáció**: `FleetController`-ben, amennyiben az adatrekord nem létezik, hibaüzenettel tér vissza.

---
### Categories Modul Végpontok

1. **GET** `/api/categories`

   - **Leírás**: A teljes kategória tábla adatainak lekérése és adatsorainak megjelenítése.
   - **Controller metódus**: `CategoryController@index`
   - **Válasz _reprezentatív_ eredménye**:

     ```json
     [
       {
         "id": 1,
         "kat_besorolas": 1,
         "teljesitmeny": 18
       },
       {
         "id": 2,
         "kat_besorolas": 2,
         "teljesitmeny": 33
       },
       {
         "id": 3,
         "kat_besorolas": 3,
         "teljesitmeny": 65
       }
     ]
     ```

2. **POST** `/api/categories`

   - **Leírás**: Új kategória létrehozása.
   - **Validáció**: `StoreCategoryRequest` fájl végzi.
   - **Példa _reprezentatív_ adatbeszúrás**:

     ```json
     {
       "kat_besorolas": 4,
       "teljesitmeny": 75
     }
     ```

3. **PUT** `/api/categories/{id}`

   - **Leírás**: Meglévő kategória adatainak frissítése.
   - **Validáció**: `UpdateCategoryRequest` végzi el.

4. **DELETE** `/api/categories/{id}`

   - **Leírás**: Egy adott kategória - *adatsor* - törlése.
   - **Validáció**: `CategoryController`-ben:

      - **HTTP** válasz státusz: `204 No Content`.
        - Adatbázis nem tartalmazza a törölt rekordot.
      - **HTTP** válasz státusz: `500`.
        - Amennyiben nem létező adatot kíván törölni.
---

### Cars Modul Végpontok

1. **GET /api/cars**

   - **Leírás**: Az összes autó adatainak listázása, beleértve a hozzá tartozó `fleet` adatokat, illetve a `carstatus` értékét _(állapotát)_.

   - **Controller metódus**: `CarController@index`

   - **Válasz formátum**:
     ```json
     [
       {
         "car_id": 1,
         "rendszam": "AAA-123",
         "toltes_szaz": 85.0,
         "toltes_kw": 30.5,
         "becs_tav": 250,
         "status": 1,
         "kategoria": 3,
         "felszereltseg": 2,
         "kilometerora": 45000,
         "gyartasi_ev": 2020,
         "flotta_azon": 1,
         "gyarto": "VW",
         "tipus": "e-up!",
         "teljesitmeny": 36,
         "vegsebesseg": 130,
         "gumimeret": "165|65-R15",
         "hatotav": 265
       }
     ]
     ```

2. **POST /api/cars**

   - **Leírás**: Új autó létrehozása, hozzáadása.

   - **Validáció**: `StoreCarRequest`.

   - **Reprezentatív kód**:
     ```json
     {
       "rendszam": "BBB-456",
       "kategoria": 2,
       "felszereltseg": 1,
       "flotta_azon": 1,
       "kilometerora": 10000,
       "gyartasi_ev": 2023,
       "toltes_szaz": 90.5,
       "toltes_kw": 35.0,
       "becs_tav": 300,
       "status": 1
     }
     ```

3. **PUT /api/cars/{id}**

   - **Leírás**: Már létező autó adatainak módosítása.
   - **Validáció**: `UpdateCarRequest`.

4. **DELETE /api/cars/{id}**
   - **Leírás**: Már létező autó törlése az adatbázisból.

---

## Kapcsolódások

### Relációk

- **CarResource** fájlban:
  - A flotta adatai (`gyarto`, `tipus`, `teljesitmeny` _és további adatok)_ a járművekhez kapcsolódnak.
- **CarWithUsersResource**:
  - A flotta adatai járműveken keresztül megjelennek a bérlői adatokhoz kapcsolva.

---

## Validáció

### `StoreFleetRequest`

- **Szabályok**:
  - `gyarto`: **Kötelező**, max. 30 **karakter**.
  - `tipus`: **Kötelező**, max. 30 **karakter**.
  - `teljesitmeny`: **Kötelező**, 18 és 500 közötti **egész** szám.
  - `vegsebesseg`: **Kötelező**, 100 és 300 közötti **egész** szám.
  - `gumimeret`: **Kötelező**, max. 30 **karakter**.
  - `hatotav`: **Kötelező**, 100 és 1000 közötti **egész** szám.

### `UpdateFleetRequest`

- Ugyanaz, mint a `StoreFleetRequest`, az `id` mező kötelező.

---
### `StoreCategoryRequest`

- **Szabályok**:
  - `id`: **Kötelező**, léteznie kell a `categories` táblában.
  - `kat_besorolas`: **Kötelező**, 1 és 10 közötti **egész** szám.
  - `teljesitmeny`: **Kötelező**, 18 és 200 közötti **egész** szám.

### `UpdateCategoryRequest`

- Ugyanaz, mint a `StoreCategoryRequest`, azzal a különbséggel, hogy a frissítéshez az `id` mező szintén kötelező.

---
### `StoreCarRequest`

- **Szabályok**:
  - `rendszam`: **Kötelező**, 7-10 karakter hosszú **szöveg**.
  - `kategoria`: **Opcionális**, léteznie kell a `categories` táblában, 1-10 közötti értékkel.
  - `felszereltseg`: **Opcionális**, léteznie kell az `equipments` táblában.
  - `flotta_azon`: **Opcionális**, léteznie kell a `fleets` táblában.
  - `kilometerora`: **Opcionális**, 0 és 300.000 közötti **egész** szám.
  - `gyartasi_ev`: **Opcionális**, négy számjegyből álló **évszám**.
  - `toltes_szaz`: **Kötelező**, 0-100 közötti **két** **tizedesjegyű** érték.
  - `toltes_kw`: **Kötelező**, 0-500 közötti **egy** **tizedesjegyű** érték.
  - `becs_tav`: **Kötelező**, 0-1000 közötti **egy** **tizedesjegyű** érték.
  - `status`: **Opcionális**, léteznie kell a `carstatus` táblában.

### `UpdateCarRequest`

- Ugyanaz, mint a `StoreCarRequest`, azzal a különbséggel, hogy a frissítéshez az `id` mező szintén kötelező.

---

## Funkcionalitások (Use Case-ek)

## Frontend integrációk

## Bővítések és Testreszabások

## Hibakezelés és Hibaüzenetek

---

## Tesztelés

### Automatikus tesztek

#### Fleet Modul Tesztelése

1. **GET /api/fleets**

   - **Cél**: A flotta adatok sikeres lekérése.
   - **Teszt metódus**: `test_get_all_fleet_types`
   - **Elvárt eredmény**:
     - HTTP válasz státusz: `200 OK`.

2. **POST /api/fleets**

   - **Cél**: Új flotta típus létrehozása az adatbázisban.
   - **Teszt metódus**: `test_post_fake_fleet_type_to_db`
   - **Adat**:
     ```json
     {
       "gyarto": "Renault",
       "tipus": "UI-UX-ULTRA",
       "teljesitmeny": 100,
       "vegsebesseg": 300,
       "gumimeret": "165|65-R15",
       "hatotav": 445
     }
     ```
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `201 Created`.
     - Adatbázis tartalmazza az új rekordot.

3. **PUT /api/fleets/{id}**

   - **Cél**: Létező flotta adatainak módosítása.
   - **Teszt metódus**: `test_put_previous_fake_fleet_modifing`
   - **Adat**:
     ```json
     {
       "gyarto": "Renault",
       "tipus": "MODIFIED-ULTRA-SUPER",
       "teljesitmeny": 100,
       "vegsebesseg": 300,
       "gumimeret": "165|65-R15",
       "hatotav": 445
     }
     ```
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `200 OK`.
     - Adatbázis tartalmazza a frissített rekordot.

4. **DELETE /api/fleets/{id}**
   - **Cél**: Létező flotta rekord törlése az adatbázisból.
   - **Teszt metódus**: `test_delete_fake_fleet_type_from_db`
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `204 No Content`.
     - Adatbázis nem tartalmazza a törölt rekordot.

---
#### Category Modul Tesztelése

1. **GET** `/api/categories`
   - **Cél**: Az összes kategória adatának lekérése.
   - **Teszt metódus**: `test_can_get_all_categories`
   - **Elvárt eredmény**:
     - HTTP válasz státusz: `200 OK`.
     - A visszaadott adatok (`data`) nem üresek.

2. **GET** `/api/categories/{id}`
   - **Cél**: Egy adott kategória adatainak lekérése.
   - **Teszt metódusok**:
     
    2.1 | **ID mező ellenőrzése**  
     - **Metódus**: `test_can_get_category_id`
     - **Elvárt eredmény**:
       - HTTP válasz státusz: `200 OK`.
       - Az objektumban szerepel az `id` mező.

    2.2 | **Kategória besorolás ellenőrzése**  
     - **Metódus**: `test_can_get_category_category_type`
     - **Elvárt eredmény**:
       - HTTP válasz státusz: `200 OK`.
       - Az objektumban szerepel a `kat_besorolas` mező.

     2.3 | **Teljesítmény ellenőrzése**  
     - **Metódus**: `test_can_get_category_with_power`
     - **Elvárt eredmény**:
       - HTTP válasz státusz: `200 OK`.
       - Az objektumban szerepel a `teljesitmeny` mező.

3. **POST** `/api/categories`
   - **Cél**: Új kategória létrehozása az adatbázisban.
   - **Teszt metódus**: `test_post_fake_category_type_into_db`
   - **Adat**:
      ```json
      {
       "kat_besorolas": 3,
       "teljesitmeny": 100
      }
      ```
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `201 Created`.
     - Az adatbázis tartalmazza az új kategóriát:

      ```json
      {
        "kat_besorolas": 3,
        "teljesitmeny": 100
      }
      ```

4. **DELETE** `/api/categories/{id}`
   - **Cél**: Létező kategória törlése az adatbázisból.
   - **Teszt metódus**: `test_delete_previous_category_type_from_db`
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `204 No Content`.
     - Az adatbázis már nem tartalmazza a törölt kategóriát.
---

#### Car Modul Tesztelése

1. **GET /api/cars**
   - **Cél**: Az összes autó adatainak sikeres lekérése.
   - **Teszt metódus**: `test_get_all_cars`
   - **Elvárt eredmény**:
     - HTTP válasz státusz: `200 OK`.
     - Az adatok megfelelő struktúrában jelennek meg, például:
       ```json
       [
         {
           "car_id": 1,
           "rendszam": "AAA-123",
           "toltes_szaz": 85.0,
           "toltes_kw": 30.5,
           "becs_tav": 250,
           "status": 1,
           "kategoria": 3,
           "felszereltseg": 2,
           "kilometerora": 45000,
           "gyartasi_ev": 2020,
           "flotta_azon": 1
         }
       ]
       ```
2. **POST /api/cars**

   - **Cél**: Új autó létrehozása az adatbázisban.
   - **Teszt metódus**: `test_post_fake_car_to_db`
   - **Adat**:
     ```json
     {
       "rendszam": "BBB-456",
       "kategoria": 2,
       "felszereltseg": 1,
       "flotta_azon": 1,
       "kilometerora": 10000,
       "gyartasi_ev": 2023,
       "toltes_szaz": 90.5,
       "toltes_kw": 35.0,
       "becs_tav": 300,
       "status": 1
     }
     ```
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `201 Created`.
     - Adatbázis tartalmazza az új rekordot:
     ```sql
     SELECT * FROM cars WHERE rendszam = 'BBB-456';
     ```

3. **PUT /api/cars/{id}**

   - **Cél**: Létező autó adatainak módosítása.
   - **Teszt metódus**: `test_put_existing_car_modifying`
   - **Adat**:
     ```json
     {
       "rendszam": "BBB-789",
       "kategoria": 3,
       "felszereltseg": 2,
       "flotta_azon": 2,
       "kilometerora": 15000,
       "gyartasi_ev": 2021,
       "toltes_szaz": 80.0,
       "toltes_kw": 25.0,
       "becs_tav": 200,
       "status": 2
     }
     ```
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `200 OK`.
     - Adatbázis tartalmazza a frissített rekordot:
     ```sql
     SELECT * FROM cars WHERE rendszam = 'BBB-789';
     ```

4. **DELETE /api/cars/{id}**

   - **Cél**: Létező autó rekord törlése az adatbázisból.
   - **Teszt metódus**: `test_delete_existing_car`
   - **Elvárt eredmények**:
     - HTTP válasz státusz: `204 No Content`.
     - Adatbázis **_nem tartalmazza_** a törölt **_rekordot_**:
     ```sql
     SELECT * FROM cars WHERE id = {id};
     ```

5. **GET /api/cars/{id}/tickets**

- **Cél**: Egy adott autóhoz kapcsolódó összes jegy _(tickets)_ lekérése.
  - **Teszt metódus**: `test_get_car_tickets`
  - **Elvárt eredmények**:
    - HTTP válasz státusz: `200 OK`.
    - Az autóhoz kapcsolódó jegyek megfelelően megjelennek:
    ```json
    [
      {
        "id": 1,
        "description": "Az autó tisztítása megtörtént.",
        "car_id": 1,
        "status_id": 1,
        "status_descrip": "Az autó elérhető és bérlésre kész.",
        "bejelentve": "2025-01-11 12:23:58"
      },
      {
        "id": 3,
        "description": "A kormánykerék ragacsos, tisztításra szorul.",
        "car_id": 1,
        "status_id": 6,
        "status_descrip": "Az autót tisztításra ki kell vonni a forgalomból.",
        "bejelentve": "2025-01-11 12:23:54"
      }
    ]
    ```

5. **GET /api/cars/{id}/renthistory**

- **Cél**: Egy adott autó bérlési előzményeinek lekérése.
  - **Teszt metódus**: `test_get_car_rent_history`
  - **Elvárt eredmények**:
    - HTTP válasz státusz: `200 OK`.
    - Az autóhoz kapcsolódó bérlési előzmények listázása:
    ```json
    {
      "car_id": 1,
      "rendszam": "RNR-590",
      "kategoria": 1,
      "felszereltseg": 1,
      "kilometerora": "31 899",
      "gyarto": "VW",
      "tipus": "e-up!",
      "berlok": [
        {
          "berles_id": 51,
          "user": "FOebiOen1309",
          "berles_kezd_datum": "2024-10-03",
          "berles_kezd_ido": "23:02:54",
          "nyitas_szaz": 32.86,
          "nyitas_kw": 5.9,
          "berles_veg_datum": "2024-10-03",
          "berles_veg_ido": "23:29:26",
          "zaras_szaz": 26.67,
          "zaras_kw": 4.8,
          "megtett_tavolsag": 8,
          "berles_osszeg": "2 492",
          "parkolas": 0,
          "szamla_kelt": "2025-01-11 12:23:15"
        },
        {
          "berles_id": 52,
          "user": "ex134",
          "berles_kezd_datum": "2024-12-02",
          "berles_kezd_ido": "23:59:47",
          "nyitas_szaz": 26.67,
          "nyitas_kw": 4.8,
          "berles_veg_datum": "2024-12-03",
          "berles_veg_ido": "00:37:36",
          "zaras_szaz": 18.33,
          "zaras_kw": 3.3,
          "megtett_tavolsag": 11,
          "berles_osszeg": "4 351",
          "parkolas": 0,
          "szamla_kelt": "2025-01-11 12:23:15"
        }
      ]
    }
    ```

### Tesztek összefoglalása

- Az automatizált tesztek lefedik a `Cars` és `Fleets` modulok **CRUD műveleteit**, valamint az ezekhez kapcsolódó speciális funkciókat _(pl. jegyek és bérlési előzmények lekérése)._

- A tesztek biztosítják, hogy az **API végpontok** helyesen működjenek, és az adatbázis tranzakciók mindig **konzisztens állapotukban** maradjanak.

- A tesztelési logika ellenőrzi a relációkat a különböző modulok között (pl. autók és flották, autók és bérlők), biztosítva az adatintegritást az adatbázisban.

- Az elvárt válaszok részletes ellenőrzése garantálja, hogy az API válaszstruktúrái megfeleljenek a specifikációknak, és helyes adatokat szolgáltassanak a frontend számára.

### Tesztek futtatása

```bash
docker compose exec backend fish
```

Majd az alábbi parancs kiadása:

```php
php artisan test
```

Továbbá a teljes program újraindításával `megváltoztatott adatokkal`, ugyanakkor azonos struktúra felépítéssel a `terminálban`:

```bash
sh start.sh
```

## Gyakran ismételt kérdések (GYIK)

#### 1. Telepítés és Inicializálás

<details>
  <summary><strong>1. Hogyan tudom inicializálni a projektet?</strong></summary>
  A projekt inicializálásának leggyorsabb módja Linux alapú Virtual Machine (virtual-server) használatával érhető el. További információkért kövesd az alábbi útmutatót:

[Linux Virtual Machine beállítása](https://www.linuxbabe.com/linux-mint/install-virtualbox-guest-additions-in-linux-mint)

**Lépések**:

1. **Töltsd le a Docker legújabb verzióját** a [hivatalos weboldalról](https://www.docker.com/).

2. **Klónozd** le a repository-t a **Visual Studio Code** termináljában:

   ```bash
   git clone "https://github.com/Bari1192/Power-And-Go"
   cd Power-And-Go
   ```

3. **Futtasd** az alábbi parancsot:
    ```bash
    sh start.sh
    ```
Ez a folyamat automatikusan felépíti a konténereket a szükséges modulokkal és kiegészítőkkel a Docker fájlok segítségével.
</details>

<details>
  <summary><strong>2. Hogyan érem el a projektet localhoston?</strong></summary>

Amint a `start.sh` folyamata befejeződött, a projekt az alábbi **lokális linkeken** érhető el:

- [Backend](http://backend.vm1.test)

- [Frontend](http://frontend.vm1.test)

- [JSON Server](http://jsonserver.vm1.test)

- [Proxy](http://proxy.vm1.test)

- [Swagger](http://swagger.vm1.test)

- [Dokumentáció](http://docs.vm1.test)
</details>
<details>
  <summary><strong>3. Hogyan módosíthatom a generálás tartalmát? </strong></summary>
   
  `Figyelem!` A generálás összetételének módosítása kihatással lehet a Migrációra, Validálásra, Controllerre és az egyéb vele kapcsolatban álló modulokra!
  - **Minden** *- Factory -* módosítás esetén **ellenőrizze a dokumentációban** lévő **kapcsolatokat** a redundancia és hibák elkerülése végett!
    ```php
    Car -> CarFactory
    {Model} -> {Model}Factory
    ```
  - Az adatgenerálások minden esetben a hozzá tartozó `modul` nevének megfelelő `Factory` részben található, a `backend` mappában.
  
    <details>

    <summary><strong>
    3.1 Csak egy adattípust szeretnék változtatni. Hol találom annak a generálási folyamatát? </strong></summary>
    
      - `Figyelem!` A Factory részben szinte minden adat generikusan jön létre, `függvények` segítségével és kerül átadásra az értéke.
      - A függvények meghívása a `return` metódusban történik:
        ```php
        <?php
        return [
              'flotta_azon' => $flottaTipus->id,
              'kategoria' => $this->katBesorolasAutomatan($flotta),
              'rendszam' => $this->rendszamGeneralasUjRegi(),
              'gyartasi_ev' => $gyartasiEv,
              'kilometerora' => $this->kmOraAllasGeneralas($gyartasiEv),
              'felszereltseg' => $felszereltseg ? $felszereltseg->id : 1,
              'toltes_szaz' => $toltes_szazalek,
              'toltes_kw' => $toltes_kw,
              'becs_tav' => $becsultHatotav,
              'status' => 1, 
          ];
        ```

      - Minden generálási függvény az adott Factory fájl alsó részében helyezkedik el a könyebb olvashatóság jegyében, pl:

        ```php
          <?php
          private function katBesorolasAutomatan(int $flotta): int
          {
              $idAlapjanKatBesorolas = DB::table('fleets')->where('id', $flotta)->first();
              if (!$idAlapjanKatBesorolas) {
                  throw new \Exception("Flotta nem található az ID alapján: $flotta");
              }
      
              return match ($idAlapjanKatBesorolas->teljesitmeny) {
                  18 => 1,
                  33 => 2,
                  36 => 3,
                  65 => 4,
                  75 => 5,
                  default => 5,
              };
          }
        ```
</details>

<details>
  <summary><strong>4. Hogyan módosíthatom a generált adatok mennyiségét? </strong></summary>

  `Figyelem!` A generálás adatok mennyiségi módosítása kihatással lehet a többi modelre, Validálásra, kapcsolatokra és a generálási folyamat idejére.

  - Az adatgenerálások mennyiségi változtatását minden esetben a hozzá tartozó `modul` nevének megfelelő `Seeder` osztályban tudjuk végrehajtani, amit, a `backend/database/seeders` útvonalon ér el.

    - Reprezentatív példa a Factory által generált adatok mennyiségére a Seeder fájlban:
    ```php
    <?php
    class CarSeeder extends Seeder
    {
        public function run(): void
        {
            $cars = Car::factory(500)->make()->toArray();
            DB::table('cars')->insert($cars);
        }
    }
    ```
    - **500-ról 1.000-re** emeljük az autók generálását.Ennek eléréshez az alábbi adatsort szükséges módosítani:
      
      ```php
      <?php
      $cars = Car::factory(1_000)->make()->toArray();
      ```
</details>

## Contributors

***Special thanks to the contributors who helped make this project possible:***

> #### [@rcsnjszg](https://github.com/rcsnjszg)

- Core functionalities, debugging, and backend-side feature suggestions.

- I am deeply grateful for their tireless and persistentefforts over the years. Even during late-night hours, theirsupport and dedication have been invaluable.
- I **aspire** to **reach the level of knowledge and experience** they possess in my lifetime. 
---
> #### [@ignaczdominik](https://github.com/ignaczdominik)
- Core functionalities, frontend debugging, and refactoring

- I extend my **heartfelt thanks** for their unwavering commitment to **frontend development** ideas and for bringing innovative and efficient solutions that rival even senior developers.
- Your **knowledge**, **boundless** **energy**, and willingness to help have been the cornerstone of effective frontend implementation.
---

> ***Their selfless contributions will always beremembered with gratitude.***

---

---

---

---


### E-mail kiküldés alapja

Elsőnek tesztelés céljából az e-mail kiküldéséhez szükséges adatokat összegyűjtjük a táblá(k)ból:
[LezartBerlesek] - berles_kezd_datum, - berles_kezd_ido, - berles_veg_datum, - berles_veg_ido,
[Autok] - Auto rendszámát reláción keresztül `$this->auto->rendszam`
[Felhasználó_e-mail_cím_kinyerese] 1. El kell jutni a [Szemelyek] táblához, amiben az e-mail van.
Ehhez a [LezartBerlesek]-től `szemely_id_fk`-val átmegyünk a [Felhasznalok] táblába.
Onnan a `szemely_id`-val átmegyünk a [Szemelyek] táblába.
A [Szemelyek] táblából meg az `email` és a `k_nev` entitásokat "kivesszük".
`email` -> kelleni fog az automata e-mail küldéshez bérlés lezárása után.
`k_nev` -> Címzés során az Email osztályban a content() részhez kelleni fog az automata megszólítás miatt.
Továbbá ki kellett egészíteni az API Resource Controller-t, hogy "behúzzuk" a táblákat.

[Végezetül]
Egyesítenünk kell 4 értéket 1-1-be, azaz: 1. `berles_kezdete` és 2. `berles_vege` változókat hozunk létre ezekre.
Carbon osztály meghívásával (metódusával) össze tudjuk fűzni a 2-2 adatot 1-1-be.
$berlesIdotartam = $berlesKezd->diffInMinutes($berlesVeg); --> Időtartamot adja vissza percekben. 3. berles_percek -et átadni a JSON-ben.