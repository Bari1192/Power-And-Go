# ChargeFactory - Tesztdokumentáció

## Tesztesetek Áttekintése

A ChargeFactoryTest osztályban megvalósított tesztek a rendszer kritikus folyamatait ellenőrzik, többek között:

- **Adatbázis integritás:** Ellenőrzi, hogy a `car_user_rent_charges` táblába generált rekordokban az olyan mezők, mint a `start_percent`, `end_percent`, `start_kw`, `end_kw`, `charging_time`, `charged_kw` és `credits` érvényes értékeket tartalmaznak.
- **Teszt adatok helyessége:** A mock adatok (létrehozva a `mockTestData()` metódussal) megfelelőségét ellenőrzi, biztosítva a helyes összekapcsolódást a Flotta, Kategória, Felszereltség, Autó, Felhasználó és Árazás modellek között.
- **Bérlési idő és megtett távolság kalkuláció:** A RenthistoryFactory metódusainak (például a `berlesIdotartama()` és a `megtettTavolsag()`) működését teszteli különböző időtartamok esetén, figyelembe véve a vezetési idő (amely a megtett távolság kétszerese) és a parkolási események logikáját.
- **Töltési logika validálása:** Több autó kategóriára vonatkozóan (pl. E‑up! 18kW, Kangoo, Citigo/E‑up! 36kW, Opel Vivaro 75kW, Kia Niro 65kW) ellenőrzi, hogy a rendszer a megadott bérlési idő, megtett távolság és aktuális töltöttségi szint alapján helyesen határozza meg, hogy szükséges-e töltést indítani.
- **Töltöttség ellenőrzése és büntetés alkalmazása:** Az ellenőrizToltottseg() metódus segítségével a jármű aktuális töltöttségéhez kapcsolódó büntetés (ha szükséges) és státusz értékét validálja.
- **Töltési adatok generálása és kredit visszatérítés:** A `generaljToltest()` metódus által generált töltési adatok (pl. a töltött kWh érték) és a `chargingCreditsReturn()` által számolt kredit visszatérítés helyességét ellenőrzi, biztosítva, hogy a felhasználó egyenlege megfelelően frissüljön.

---

### TesztKörnyezet

- **Teszt keretrendszer:** PHP által definiált tesztkörnyezet _(PHPUnit)_
- **Futtatási környezet:** PHP 8.3.8 fpm-alpine, a projekt specifikációjának megfelelő konfigurációjával.
- **Előfeltételek:** A ChargeFactory modul konfigurációja, valamint a szükséges külső függőségek és mock-objektumok beállítása a teszteléshez.

---

- **Előkészítés:**
  - A `setUp()` metódus inicializálja a CarRefreshService-t és a CarUserrentChargeFactory-t, majd a `mockTestData()` metódussal létrehozza a szükséges teszt entitásokat (flotta, kategória, felszereltség, autó, személy, felhasználó, árazás, napi bérlés).
  - A segédmetódusok, mint a `calculateTimes()` és a `mockTestCarChargingData()`, biztosítják a szükséges adatok és segédfüggvények elérését.

---

> _Az renszer által futtatott tesztesetek tartalmazzák a függőségeket, a mock-objektumok beállításait, példányosításaikat. A tesztesetek változtatás nélkül garantált lefutást és validációt eredményeznek._

---

## Teszt Célok

A **CarUserrentChargeFactory** rendszer tesztelése során a következő főbb funkciókat és folyamatokat vizsgáljuk:

1. Adatok validálása a car_user_rent_charges táblában
2. Teszt adatok előkészítése és validálása
3. Bérlési időtartamok kategóriánkénti ellenőrzése
4. Parkolási logika tesztelése különböző bérlési időtartamokra
5. Megtett távolságok kalkulációjának ellenőrzése változatos bérlési időszakokra
6. Töltési szükségesség megállapítása különböző jármű kategóriákra
7. Töltöttségi szint validációk az egyes kategóriákban
8. Töltési folyamat generálásának tesztelése
9. Töltési kreditek visszatérítésének ellenőrzése

---

## Tesztesetek Részletezése

### 1. Adatbázis Rekordok Integritásának Ellenőrzése

- **Metódus:**  
  `test_a_CarUserRentCharges_tablaban_validalt_ertektartomanyu_rekordok_generalodnak()`
- **Cél:**  
  Ellenőrzi, hogy a `car_user_rent_charges` táblában a következő mezők:

  - `start_percent` és `end_percent` (0–100% tartomány),
  - `start_kw` és `end_kw` (0 és a flotta legnagyobb motor teljesítménye között),
  - `charging_time` (legalább 5 perc, de kevesebb, mint 1000 perc),
  - `charged_kw` (0–100 közötti érték) és
  - `credits` (0–21 000 közötti érték)

  érvényes értékeket tartalmaznak.

- **Eredmény:**  
  A teszt **assertFalse()** hívásokkal jelez hibát, ha bármely érték kívül esik az előírt tartományból.

---

### 2. Teszt Adatok Helyességének Validálása

- **Metódus:**  
  `test_mock_test_adatok_validak_es_a_testelesre_keszek()`
- **Cél:**  
  Validálja, hogy a `mockTestData()` által generált adatok megfelelnek a várt paramétereknek:
  - **Flotta:** például `manufacturer` = VW, `carmodel` = e‑up!, motor_power = 18, driving_range = 135.
  - **Kategória:** kategória osztály és motor_power értékek.
  - **Felszereltség:** minden releváns mező (pl. reversing_camera, lane_keep_assist stb.) true.
  - **Autó:** power_percent, power_kw, estimated_range, status, odometer, gyártási év stb.
  - **Felhasználó:** user_name, password jellemzők, account_balance, előfizetési id (sub_id).
  - **Árazás:** kategória és sub_id értékek.
- **Eredmény:**  
  A teszt sikeresen lefut, ha az adatok minden esetben megfelelnek a specifikációknak.

---

### 3. Bérlési Időtartam Generálása Kategóriák Szerint

- **Metódus:**  
  `test_berles_idotartama_kategoriak_szerint_random_tartomanyban()`
- **Cél:**  
  Ellenőrzi, hogy a RenthistoryFactory::berlesIdotartama() metódus a különböző autó kategóriákhoz (pl. 1–5) olyan időtartamokat generál, melyek a kategória-specifikus minimum és maximum értékek között vannak (például 1 perc – 14400 perc, 60 perc – 4320 perc stb.).
- **Eredmény:**  
  Többszöri iteráció során a generált értékek mindig a megadott határok között mozognak.

---

### 4-5. Megtett Távolság és Parkolási Események Kalkulációja

A RenthistoryFactory::megtettTavolsag() metódus több teszt esetében kerül validálásra:

- **Teszt esetek:**

  - **0–15 perc bérlés:**  
    `test_0_es_15_perc_berles_kozotti_parkolas()`  
    – Nincs parkolás, a megtett távolság alapján a vezetési idő kétszerese a távolságnak.

  - **16–30 perc bérlés:**  
    `test_16_es_30_perc_berles_kozotti_parkolas()`  
    – Legalább 1 parkolási esemény várható; a vezetési idő megfelel a megtett távolság kétszeresének.

  - **60 perc, 120 perc, 180 perc, 300 perc, illetve több órás és napos bérlések:**  
    Olyan tesztek (pl. `test_60_perc_berles_kozotti_parkolas()`, `test_120_perc_berles_kozotti_parkolas()`, `test_180_perc_berles_kozotti_parkolas()`, `test_7_ora_es_15_perc_berles_kozotti_parkolas()`, `test_1_nap_es_8_oras_berles_parkolas()`, `test_2_napos_berles_parkolas()`, `test_3_napos_berles_parkolas()`) ellenőrzik:

    - A megtett távolság, amely a vezetési idő kétszerese.
    - A parkolási események számát, mely előre meghatározott intervallumokban mozog (pl. 1–5 esemény), és a parkolási idő arányait (pl. első, második, harmadik parkolás).

  - **Rövid (15–30 perc) és extrém esetek:**  
    `test_15_perc_alatt_megtett_tavolsag()` és `test_16_es_30_perc_kozotti_megtett_tavolsag()`  
    – Ellenőrzik, hogy a rövid időtartam alatt a megtett távolság nem haladja meg a maximum értéket, és a vezetési idő megfelelően kétszerese a megtett távolságnak.

  - **Kiterjedt iterációs teszt:**  
    `test_5_tol_240_percig_0_tol_120km_ig_megtettTavolsag()`  
    – Több különböző bérlési időhöz (5, 10, 15, … 240 perc) ellenőrzi, hogy a megtett távolság a megadott minimum és maximum értékek között van, valamint a vezetési idő kétszerese a megtett távolságnak.

- **Eredmény:**  
  A tesztek biztosítják, hogy a bérlés során generált megtett távolság, vezetési idő és parkolási események száma az elvárt logika szerint alakul.

---

### 6. Töltés szükségességének megállapítása különböző jármű kategóriákra

A különböző autó kategóriákra vonatkozó töltési igényt a kellHozzaTolteniAutot() metódus teszteli:

- Kategória-specifikus tesztesetek használatával ellenőrzi a töltés szükségességének meghatározását
- Különböző feltételek mellett (töltöttség, bérlési idő, megtett távolság) vizsgálja a döntéshozatalt

- **Teszt esetek:**

  - **E‑up! 18kW:**  
    `test_18Kw_eup_KellEHozzaTolteniAutot_ido_tav_toltes_aranyokkal()`  
    – Az `eup18kwCases` tömb alapján ellenőrzi, hogy a megadott megtett távolság, bérlési idő és töltöttségi szint mellett a rendszer helyesen dönt a töltési igényről.

  - **Renault Kangoo (33kW):**  
    `test_33KwKangoo_KellEHozzaTolteniAutot_TestCasesTombHasznalataval()`  
    – A `kangooCases` alapján hasonlítja össze az elvárt és a visszakapott eredményt.

  - **Citigo/E‑up! (36kW):**  
    `test_36Kw_eup_Skoda_KellEHozzaTolteniAutot_TestCasesTombHasznalataval()`  
    – A `citigoEupCases` teszteseteivel validálja a logikát.

  - **Opel Vivaro (75kW):**  
    `test_75Kw_OpelVivaro_kellEHozzaTolteniAutot_TestCasesTombHasznalataval()`  
    – A `vivaroCases` alapján ellenőrzi, hogy a töltési igény meghatározása megfelelő.

  - **Kia Niro (65kW):**  
    `test_65Kw_KiaNiro_kellEHozzaTolteniAutot_TestCasesTombHasznalataval()`  
    – A `KiaNiroCases` teszteseteivel hasonlítja össze a várt és a tényleges eredményt.

- **Eredmény:**  
  A tesztek azt igazolják, hogy az egyes autó típusok esetén a töltési igény meghatározása a bérlés paramétereinek (töltöttségi szint, megtett távolság, bérlési idő) függvényében pontosan működik.

---

### 7. Töltöttségi szint validációk az egyes kategóriákban

- **Metódus:**  
  `test_toltes_validacio_minden_kategoriara()`
- **Cél:**  
  Különböző kategóriák esetében ellenőrzi, hogy a jármű aktuális töltöttsége alapján:
  - Teszteli a töltöttségi szint függvényében történő beavatkozásokat (figyelmeztetés, státusz változás) kategóriánként
  - Ellenőrzi a büntetések kiszabásának helyességét a kritikusan alacsony töltöttségi szint esetén
  - Biztosítja, hogy a rendszer megfelelően reagál a különböző töltöttségi szintekre az egyes kategóriákban
- **Eredmény:**  
  A teszt összehasonlítja a visszakapott büntetési információkat és státusz értéket a megadott elvárásokkal minden kategóriára.

---

### 8. Töltési Adatok Generálása

- **Metódus:**  
  `test_GeneraljToltest_function_test()`
- **Cél:**  
  A `generaljToltest()` metódus által generált töltési adatok esetén a teszt:
  - Teszteli a töltöttségi szint függvényében történő beavatkozásokat (figyelmeztetés, státusz változás) kategóriánként
  - Ellenőrzi a büntetések kiszabásának helyességét a kritikusan alacsony töltöttségi szint esetén
  - Biztosítja, hogy a rendszer megfelelően reagál a különböző töltöttségi szintekre az egyes kategóriákban
- **Eredmény:**  
  A teszt biztosítja, hogy a töltési sebesség megfelel a kategória beállításainak.

### 9. Töltési kreditek visszatérítésének ellenőrzése

- **Metódus:**  
  `test_ChargingCreditsReturn_function_test()`

- **Cél:**  
  Ellenőrzi, hogy a `chargingCreditsReturn()` metódus a töltött kWh értékének megfelelően:

  - Teszteli a felhasználói kreditek helyes számítását és jóváírását a töltési mennyiség alapján
  - Ellenőrzi a folyamat során a felhasználó egyenlegének megfelelő növekedését
  - Biztosítja a kreditrendszer megbízható és konzisztens működését

- **Teszt esetek:**  
  Több különböző toltott kWh érték (például 1.6, 5.9, 6.0, 10.0) esetén az elvárt kredit visszatérítést hasonlítja össze.
- **Eredmény:**  
  A teszt megerősíti, hogy a kredit kalkuláció logikája helyes, és a felhasználó egyenlege a várt módon növekszik.

---

## Összefoglalás

A ChargeFactoryTest tesztesetek együttesen biztosítják, hogy:

- Az adatbázisba generált töltési rekordok értéktartománya megfelel a rendszer specifikációinak.
- A mock adatok és a generált bérlési idő, megtett távolság, vezetési idő és parkolási események logikája pontosan működik.
- Az egyes autó kategóriákra vonatkozó töltési igény meghatározása, a töltöttségi validáció (büntetés és státusz) valamint a töltési adatok generálása megbízhatóan végrehajtódik.
- A töltés után a kredit visszatérítés kalkulációja is az elvárt módon történik, így a felhasználó egyenlege helyesen frissül.

Ezzel a tesztcsomaggal biztosított, hogy a rendszer főbb funkciói – a bérlés, parkolás, töltés és kredit visszatérítés – integritása, helyessége és stabilitása garantált a fejlesztési folyamat során.

## Megjegyzések

> A dokumentáció a ChargeFactoryTest.php fájlban implementált tesztesetek alapján készült.
> A rendszeres tesztfuttatások biztosítják a modul stabilitását, és segítenek az esetleges hibák gyors felismerésében.
