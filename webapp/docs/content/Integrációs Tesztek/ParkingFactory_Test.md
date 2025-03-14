# ParkingFactory - Tesztdokumentáció

## Áttekintés

A ParkingFactory tesztelési rendszere átfogó ellenőrzést biztosít a **parkolási események generálásának és kezelésének minden aspektusára.**
A tesztek lefedik az időbeli **validációkat**, **költségszámítást** és a különböző **előfizetési szintek**hez tartozó speciális esetek **kezelését**.
A tesztek biztosítják, hogy a parkolási események generálása, díjszámítása, időkorlátainak ellenőrzése és a **ChargeFactory-vel való integráció** megfelelően működik minden előre definiált forgatókönyvben.

## TesztKörnyezet


## Futási Feltételek és Környezet

- **Teszt keretrendszer:** PHP keretrendszer által definiált tesztkörnyezetben.
- **Futtatási környezet:** PHP 8.3.8 fpm-alpine 3.20, a projekt specifikációjának megfelelő konfigurációval.
- **Előfeltételek:** A ParkingFactory és a ChargeFactory modulok integrált működése, megfelelően konfigurált adatbázis és szimulált környezeti adatok

***
>
>**Fontos!**
> A tesztek futtatásához szükséges környezeti elemek, változók a tesztesetben integráltan **mock()** tesztelési mechanizmussal zajlik. 
> Ennek köszönhetően az adatbázis rekordjainak és feltételeiknek hű, **valós adatszerkezet** kerül kialakításta a teszteléshez.
>A **Dbtransaction**-nek köszönhetően a generált adattal való teszteset **módosítást nem hajt végre**, minden tesztművelet lefutása után a tesztadat a **rollback()** funkciónak köszönhetően tisztán tartja az adatbázis rekordjait.
***
## Teszt Célok

- Ellenőrizni, hogy a rendszer a bérlési idő, jármű státusz és parkolózóna adatok alapján helyesen generálja a parkolási eseményeket.
- Validálni a dinamikus díjszámítás és kredit levonás logikáját.
- Biztosítani, hogy a maximális parkolási idő korlátok betartásra kerülnek.
- Tesztelni a ChargeFactory modul integrációját, amelyből parkolási igények generálódhatnak.

## Részletes Tesztesetek

### 1. Minimális Parkolási Idő Validáció

**Teszt metódus**: `test_parkolasok_generalasa_ot_perces_idovel_nem_lehet()`

**Cél**: Ellenőrzi, hogy 5 percnél rövidebb parkolási események nem generálódhatnak.

**Vizsgált feltételek**:
- 0-4 perces időtartamok tesztelése
- Minden esetben üres tömb visszaadásának ellenőrzése

**Elvárt eredmény**: Egyetlen parkolási esemény sem jöhet létre 5 percnél rövidebb időtartamra.

### 2. Rövid Parkolási Események Validációja

**Teszt metódus**: `test_parkolasok_generalasa_5_es_15_perc_kozott_valid()`

**Cél**: 5-15 perc közötti parkolási események helyességének validálása.

**Vizsgált feltételek**:
- Minden időtartamra pontosan egy parkolási esemény generálódik
- Az események tartalmazzák a szükséges mezőket:
  - Kezdő időpont
  - Záró időpont
  - Parkolási időtartam
  - Teljes költség

### 3. Parkolási Időszakok Felosztásának Tesztelése

**Teszt metódus**: `test_teljes_parkolasi_ido_feldarabolása_tobb_0tol_5_idoszakra_helyesen_fut_e()`

**Vizsgált időtartamok és elvárt események**:

| Időtartam | Elvárt Parkolások Száma | Teszt Eset Név |
|-----------|------------------------|----------------|
| ≤ 15 perc | 0 | 'nincs' |
| 16-30 perc | 1 | 'egyParkolas' |
| 31p - 3 óra | 2 | 'ketParkolas' |
| > 3 óra | 3-5 | 'haromParkolas', 'negyParkolas', 'otParkolas' |

### 4. Költségszámítási Tesztek

**Teszt metódus**: `test_parkolasi_koltseg_szamitasa_minden_elofizetesre_auto_kategóriara_es_napszakokra_validan()`

**Tesztelési mátrix**:
- Előfizetési szintek: 1-4
- Autókategóriák: 1-5
- Napszakok: nappali, éjszakai, vegyes

**Speciális validációk**:
- VIP előfizetők (4-es szint) éjszakai kedvezményei
- Kategória-specifikus díjszabások
- Napszakok közötti átmenetek kezelése

### 5. Teljes Bérlési Validáció

**Teszt metódus**: `test_user_full_time_rent_validation()`

**Tesztesetek**:

| Eset Név | Bérlési Idő | Vezetési Idő | Parkolások | Elvárt Eredmény |
|----------|-------------|--------------|-------------|-----------------|
| normal_parkolas | 120 perc | 60 perc | 1x60 perc | Vezetés: 60, Parkolás: 1 |
| hosszu_parkolas | 100 perc | 20 perc | 1x70 perc | Vezetés: 40, Parkolás: 1 |
| tobb_parkolas | 180 perc | 60 perc | 2x(40+50) perc | Vezetés: 90, Parkolás: 2 |
| nincs_parkolas | 60 perc | 60 perc | 0 | Vezetés: 60, Parkolás: 0 |

## Biztonsági Ellenőrzések
A tesztek során a következő biztonsági szempontok kerülnek validálásra:

1. **Időbeli Korlátok**:
   - Minimum parkolási idő betartása
   - Maximum parkolási idő validációja
   - Átfedések elkerülése

2. **Költségszámítás**:
   - Napszak alapú díjszámítás helyessége
   - Kedvezmények megfelelő alkalmazása
   - Negatív összegek kizárása

3. **Parkolási Arányok**:
   - Maximális parkolási idő (60%-os szabály)
   - Minimum vezetési idő garantálása
   - Parkolások megfelelő eloszlása

## Összefoglalás

A tesztrendszer ***összesen 60+ különböző tesztesetet tartalmaz***, amelyek a parkolási rendszer minden kritikus aspektusát lefedik. A tesztek sikeressége garantálja a rendszer megbízható működését és a biztonsági szabályok betartását.


## Megjegyzések

- A teszt esetek célja a rendszer logikájának validálása különböző forgatókönyvek alapján.
- Az itt dokumentált tesztesetek a ParkingFactoryTest.php fájlban található implementációra épülnek, és rendszeres futtatásukkal biztosítják a rendszer stabilitását és megbízhatóságát a fejlesztés során.


