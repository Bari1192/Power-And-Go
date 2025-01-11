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

### Végpontok és Funkcionalitások

#### Fleets Modul Végpontok

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

#### Cars Modul Végpontok

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
  - `gyarto`: Kötelező, max. 30 karakter.
  - `tipus`: Kötelező, max. 30 karakter.
  - `teljesitmeny`: Kötelező, 18 és 500 közötti egész szám.
  - `vegsebesseg`: Kötelező, 100 és 300 közötti egész szám.
  - `gumimeret`: Kötelező, max. 30 karakter.
  - `hatotav`: Kötelező, 100 és 1000 közötti egész szám.

### `UpdateFleetRequest`

- Ugyanaz, mint a `StoreFleetRequest`, az `id` mező kötelező.

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
`bash
     sh start.sh
     `
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

### Adatbázis frissítése:

- A státuszváltás végrehajtása után vissza kell igazolnom a foglalhatóságot.
- Ha sikeres, egy 200-as HTTP választ kell visszaküldenem a frontendnek.

### Tesztelés és Ellenőrzés

- Ellenőriznem kell, hogy az autók státuszai pontosan frissülnek-e minden eseménynél.
- Tesztelnem kell az adminisztrátor által végrehajtott módosításokat, hogy a backend validálása megfelelően működik-e.
- Gondoskodnom kell arról, hogy a frontend logikája pontosan tükrözze az adatbázis állapotát

## [Lezárt-Bérlések]-[Autok-Töltöttség-%-Hatótáv-km]

1. Funkciók és Alapvető Célok
   Az Autók és Lezárt bérlések kapcsolatrendszerének célja az autók aktuális állapotának, töltöttségi szintjének, hatótávjának és bérlési adatainak pontos nyilvántartása. A logika biztosítja, hogy:

- Az autók energiafelhasználása és állapota dinamikusan, valós időben frissüljön.
- Az aktuális töltöttségi szint alapján meghatározott maximális távolság figyelembevételével történjen adatgenerálás.
- Az autó "foglalható" státusza automatikusan frissüljön a változások bekövetkeztekor.

2. Fő Komponensek és Funkcióik
   ### Töltöttségi Szint Számítás:
   - Az autók kezdeti és záró töltöttségi szintjét a toltes_szazalek mező határozza meg.
   - A hatótáv számítása:
   - Egy kW energia által megtett távolság:
   - egy_kW_hany_km = hatotav / teljesitmeny
   - Kezdeti hatótáv kiszámítása:
   - becsult_hatotav = egy_kW_hany_km \* toltes_kw
   - Záráskor várható állapot: zaras_toltes_kw = nyitas_toltes_kw - fogyasztott_kw
   2. Az autók csak akkor foglalhatók, ha:
      - Nyitási töltöttség >= 15%.
      - Zárási töltöttség > 12%.
        Amennyiben az autó nem felel meg ezeknek a kritériumoknak, automatikusan foglalhato = `false` állapotba kerül.
   3. `Kilométeróra Állás` Frissítése:
      - A megtett távolság (megtettTavolsag) alapján az km_ora_allas mező automatikusan frissül.
      - Új érték:
        km_ora_allas = km_ora_allas + megtettTavolsag

### Működési Logika

1.  Autó Bérlésének Generálása:
    - Az autó adatainak frissítése a lezárt bérlés adatai alapján történik.
    - Ha a záró töltöttségi szint 10% alá csökkenne, a bérlés nem generálható, és az autó "foglalhatatlan" lesz.
2.  Adatfrissítés:
    - Záráskor:
      - toltes_szazalek, toltes_kw és becsult_hatotav mezők frissítése.
      - Ha a töltöttségi szint < 15%, akkor az autó "foglalhatatlan" státuszt kap.
    - Kilométeróra:
      - A megtett távolság automatikusan hozzáadódik a meglévő kilométeróra álláshoz.

### Példák és Használati Esetek

1.  Autó 75%-os töltöttségi szintről indul:
    - 20 km megtételével 72%-ra csökken.
    - Új hatótáv számítása:
      - zaraskoriToltesKw = 36kW \* 0.72
      - becsult_hatotav = zaraskoriToltesKw \* (hatotav / teljesitmeny)
2.  Autó töltöttségi szint 12% alá csökken:
    - Az autó nem "foglalható", státusza automatikusan frissül.
3.  Új bérlésnél figyelembe vett töltöttségi szint:
    - Az előző bérlés után megmaradt töltöttség az alapja az új bérlésnek.

### Ez a logika lehetővé teszi

- Az autók bérlésének dinamikus és pontos nyilvántartását.
- Az autók foglalhatósági állapotának automatikus kezelését.
- A töltöttségi és hatótáv-adatok gyors és hatékony frissítését.

## [Autok-Töltöttség-%-Hatótáv-km]

1. Autok tábla bővítésének részletei:

- `toltes_szazalek`: Az adott autó töltöttségi szintjét adja meg, százalékos (float) formátumban, 15.0% és 100.0% közötti értékkel. A cél, hogy minden autó egyedi töltöttségi értéket kapjon.
- `toltes_kw`: A töltöttségi szint alapján kiszámított érték az akkumulátor maximális kapacitásának (kW) figyelembevételével. Ez az érték az autóhoz rendelt flotta típus teljesitmeny mezőjéből származik, és a számított érték 1 tizedesjegyre kerekítve kerül tárolásra.
- `becsult_hatotav`: A megadott töltöttségi szint alapján becsült maximális hatótávot (km) adja meg. A számításhoz a flotta típus hatotav és teljesitmeny mezőjének arányát használjuk fel, szintén 1 tizedesjegyre kerekítve.

2. [Entitások] közötti összefüggések:

- A töltöttségi szint (%-os) értéke (toltes_szazalek) felhasználásra kerül a maximális akkumulátor kapacitás kiszámításához: - `toltes_kw = teljesitmeny * (toltes_szazalek / 100).`
  Az autóhoz tartozó hatótáv (hatotav) alapján kiszámítható az, hogy egy kW energia hány km megtételére elegendő: - `egy_kW_hany_km = hatotav / teljesitmeny.`
  Ezután a toltes_kw értékével felszorozva meghatározható a becsült hatótáv: - `becsult_hatotav = egy_kW_hany_km * toltes_kw.`

3. `A letárolt adatok szerepe:`

- A számított mezők közül a toltes_szazalek, toltes_kw, és becsult_hatotav értékeit az autók táblában tároljuk. Ennek oka, hogy ezek az értékek gyakran kerülnek felhasználásra, így a számítások helyett a közvetlen tárolt értékek biztosítanak gyorsabb hozzáférést.

4. `Gyakorlati felhasználása és az alkalmazása:`

- Mivel, hogy az autók egyedi töltöttségi szinttel kerülnek létrehozásra (15-100% között), és ezek alapján automatikusan kiszámításra kerül a pillanatnyi kW-ban mért töltöttség és a várható hatótáv.
  Az adatok előkészítésével biztosítható a flottákhoz rendelt autók állapotának és teljesítményének pontos nyilvántartása.

5. `Célok`:

- Az autók akkumulátor-teljesítményének és hatótávjának pontos, dinamikusan változtatható nyilvántartása, amely lehetővé teszi az autók energiafelhasználásának hatékony monitorozását, valamint az egyes autók várható teljesítményének egyszerű elemzését.

## [Számlázás-TÁBLA]

1.  Az egész tábla tartalma cirka származtatott lenne, ami annyit jelent, hogy létrejönne a(z):

    - `szamla_id`('szamla_sorszam') [PK] -> Egyedi azonosítószáma a számlának.
    - `szamla_tipus` -> A számla kiállításának típusát írja le, mely származhat bérlésből, baleset -ből (mint baleset okozója),károkozás-ból (mint rongálás, törés stb.), magatartás -ból (mint autóban dohányzás, tilosban parkolás, gyorshajtás). Egyik értéket mindig kötelező felvennie.
    - `felh_id` ('felhasznalo') [FK] -> felh_id_fk érték kerül ide, ami beazonosítja, hogy melyik felhasználóhoz tartozik a számla.
    - `szemely_id` ('szemely') [FK] -> szemely_id érték kerül ide, ami beazonosítja, hogy az adott személyhez ez a felhasznaló tartozik (azonosítószám alapján) a számla. (többszörös védelem a biztosan hibamentes számla kiállításához.)
    - `berles_kezd_datum` -> Meghatározza a berles kedzetének dátumát (év-hónap-nap).
    - `berles_kezd_ido` -> Meghatározza a berles kedzetének időpontját (óra-perc-másodperc).
    - `berles_veg_datum` -> Meghatározza a bérlés végének dátumát (év-hónap-nap).
    - `berles_veg_ido` -> Meghatározza a berles végének időpontját (óra-perc-másodperc).
    - `megtett_tavolsag` -> Meghatározza a bérlés során az autóval megtett távolságot (km-ben).
    - `parkolasi_perc` -> Meghatározza a bérlés során az autóval parkolt időt (percben, egész számként).
    - `vezetesi_perc` -> Meghatározza a bérlés során az autóval levezetett időt (percben, egész számként).
    - `berles_osszeg` -> Meghatározza a bérlés során az autó típusától és a felhasználó előfizetési csomagjától (ha van) függően a megtett távolság, vezetési percdíj, parkolás és egyéb szolgálatások fejében a bérlésének a teljes költségét. (Ft-ban, egész számként).
    - `szamla_kelt` -> A számla készítésének időpontját -> created_at érték.
    - `szamla_status` -> Az 'aktiv','függőben','archivált' értékek egyikét veszifel, attól függően, hogy a számla kifizetésre került-e. Alapértelmezetten a 'függőben' státust kapja, míg a számla kifizetése valóban meg nem történik.

    Mivel az adatok jórésze már a `lezart_berlesek` táblában elérhető, ezért onnan fel tudjuk használni. Fontos ugyanakkor, hogy az ottani értékek megmaradnak annak érdekében, hogy ahhoz a táblához / táblába a `felhasználó NEM láthat bele`. Ennek érdekében kerül elkészítésre a `szamlak` tábla, ahol majd a számlákkal:

    - `Státuszát` tudjuk állítani,
    - `Új számlát` tudunk kiállítani,
    - Számlát tudunk `törölni`,
    - Számlát tudunk `módosítani` - hiba esetére -> megfelelő jogosultságg(ok)al.

## SZÁMLÁZÁSI LOGIKA LEÍRÁSA [ENYÉM]:

Ugye a `4-es előfizetési kategóriával`, `4-es kategóriájú autóval` bérelt. `közel 1,5 napig` bérelte az autót. Mivel a 4-es kat. autókat `CSAK napokra lehet bérelni` ezért úgy kellene számolnia, hogy:
ArazasSeederben kikeresi ezt (elofiz_azon,auto_besorolas, díjak stb.), majd utána:
`berles_ind` díja, =>1990 Ft + `napidij` => 20680
Majd ellenőrzi, hogy a LezartBerlesFactory `megtettTavolsag`() alapján generált érték (`pl:128km`)
Benne van-e az ArazasSeeder -ben lévő `napi_km_limit` -ben, ami => `125`, ennél az előfizetőnél.
Mivel 3 km-rel többet autózott el, mint a napi limit, ezért 3 \*48 Ft-ot kéne fizetnie pluszban. (Amennyiben 1 napig bérelte. Hogy ha 2 napot bérli, akkor minden egyes további nappal +125km-rel nő az ő napi_km_limit értéke.)
Maradva a számolásnál ez azt jelenti, hogy elsőnek ki kell számolnunk, hogy:

1. 1990+20680+(3*48)+((~5óra*60) \*vez_perc => 78) = 46 214 Ft (ha jól számolom),
   VAGY AMENNYIBEN KEDVEZŐBB NEKI, akkor:
2. 1990+20680(1.nap)+40360(2.nap), ami = 63030 Ft (azaz cirka annyi, mint az eredeti számolásban. Lehet kihagytam valamit)

Ergó ebben az esetben az ügyfélnek az 1. eset lesz a kedvezőbb, így azt a számlát kell kiállítanunk.

[Viszont]
Ha nem napi bérlésről van szó, akkor a kilóméterdíjat nem szabad hozzácsapni a végösszeghez. Alapesetben (ha nem napi bérlés lesz).
Napi bérlések esetén pedig a km limitig nem számolunk kilóméterdíjat. A kilóméterlimit túllépése után pedig felszámoljuk az "extra" kilómétereket.
Ugyanakkor meg kell vizsgálnunk, helyesen - ahogy tetted -, hogy azzal jár-e jobban, hogy az alaposszeget fizeti ki, vagy a napidijat (még akkor is, ha a napidijban mondjuk benne lenne extra kilóméterdíj).
24 órán belüli bérlés kezelése (2-es és 4-es kategória):

`Ha a bérlés időtartama 24 órán belül van ($napok <= 1) ÉS az autó 2-es vagy 4-es kategóriájú`

- Akkor a minimum összeg a napidij + extra kilométerdíj (ha túllépte a km-limitet).
- Az alapösszeg és a minimum napi díj közül a nagyobbat adjuk vissza.

`Többnapos bérlés kezelése`:

- Ha több mint 1 napos bérlésről van szó, akkor a NapiBerles táblából lekérjük a megfelelő napi díjat.
- A napi díjat a $napok - 2 index alapján határozzuk meg, mivel a 2 napos bérlés ára a 0. indexen van.

`Km-díj hozzáadása`:

- Ha túllépi a napi km-limitet, akkor az extra kilométerek díját (kmDijOsszeg) minden esetben hozzáadjuk.

`Kedvezőbb ár kiválasztása:`

- A minimum napi díj (vagy többnapos díj) és az alapösszeg közül mindig a kedvezőbbet adjuk vissza.

## Előfizetések tábla - létrehozása

1. [Entitások]-[Migráció]:

   - `elofiz_id` - Az előfizetés azonosítójának a száma. [PK],[AI].
   - `elofiz_nev` - Az előfizetésnek a megnevezése - kötelező, fix csomagok közül.
   - `havi_dij` - Az előfizetés havi díjának összege (opcionális).
   - `eves_dij` - Az előfizetés éves kedv. díja (opcionális VIP csomaghoz).

2. [Seeder]:
   `elofiz_nev` - 4 előfizetési csopor "választható". Alapértelmezetten aki nem havidíjas előfizetést választ - alkalmi felhasználó -, azon személyeket a 'Power' csomag részeként kezeljük.

   - `havi_dij` összeg opció csak a `Power-Plus`,`Power-Premium`,`Power-VIP` előfizetéseknél van.
   - `havi_dij` és `eves_dij` együttese csak a `Power-VIP` -ben érhető el.
     Ahol a havidíj, vagy az éves díj `null` értékként van kezelve, az azt jelenti, hogy az adott előfizetési csomagban `nincs/nem elérhető` ilyen opció.

3. [Relációk]-[Model]

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
