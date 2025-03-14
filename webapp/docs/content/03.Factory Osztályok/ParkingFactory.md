# Parking Factory - Technikai Dokumentáció

_(CarUserRentParkingFactory - Parkoláskezelő Rendszer Specifikáció)_

## Áttekintés

A **CarUserRentParkingFactory** egy komplex **parkoláskezelő rendszer**, amely az elektromos járművek bérlési időszakai alatti parkolási eseményeket szimulálja és kezeli. A rendszer a **bérlés teljes időtartamát**, különböző **napszakokat** és **felhasználói előfizetési csomagokat**, _(szinteket)_ figyelembe véve alakítja ki a parkolási díjak számítási mechanizmusát.

## Működési Alapelvek

### Parkoláskezelési Alapszabályok

A rendszer működését meghatározó főbb alapelvek:

- A parkolási események **időzítésének** és **hosszának** intelligens meghatározása
- **Napszakok** szerinti differenciált árazás (nappali és éjszakai tarifák)
- Előfizetési szintek szerinti **kedvezmények** alkalmazása
- Parkolási költségek **valós idejű** kalkulációja
- **Töltési események során generált** parkolások integrációja
> Hosszabb bérlés esetén *(pl: 60 perc felett)*, ha a töltésből eredő parkolási igény megerősödik – *a ChargeFactory logikája alapján* –, a rendszer **automatikusan generál parkolási eseményt**

### Parkolási Események Generálása

#### Alapvető kizáró feltételek:
- A jármű már **parkolás alatt áll**
- A bérlési időtartam **kevesebb** mint _10 perc_

#### A parkolási esemény(eke)t kiváltó feltételek:
- A teljes bérlési-ciklus ideje meghaladja a **30 percet**
- 30 perc fölötti bérlés esetén a időarányos parkolási **darabszám növekedés**
- A ChargeFactory logikája alapján elrendelt **töltés esetén.**


## Technikai Implementáció
A rendszer a következő szempontok alapján generálja a parkolási eseményeket:
- **Minimum parkolási idő**: 5 perc
- **Maximális parkolási idő**: bérlési időtartam - 5 perc (biztonsági tartalékkal)
- **Parkolások eloszlása**: A bérlési időszakban egyenletesen elosztva
- **Költségkalkuláció**: Napszak és előfizetési típus alapján


### Időszámítási Modell

1. **Napszakok meghatározása:**
   - Nappali időszak: 07:00 - 22:00
   - Éjszakai időszak: 22:00 - 07:00

```bash
   $dayStart = (clone $current)->setTime(7, 0);
   $dayEnd = (clone $current)->setTime(22, 0);
```

2. **Napszakok Átfedésének Számítása:**

```bash
private function overlapInMinutes(DateTime $startA, DateTime $endA,DateTime $startB, DateTime $endB): int
{
    $startMax = max($startA->getTimestamp(), $startB->getTimestamp());
    $endMin = min($endA->getTimestamp(), $endB->getTimestamp());
    return ($endMin <= $startMax) ? 0 : floor(($endMin - $startMax) / 60);
}
```

### Parkolási Díjszámítási Mechanizmus
A rendszer a parkolási **díjak** és **időtartamok** pontos kalkulációjára az alábbi formulákat alkalmazza:

1. **Parkolási Díj Számítása (D):**
   - **D = Időtartam × Egységár**
   - **Időtartam:** A parkolás tényleges időtartama percben
   - **Egységár:** Az adott felhasználónak az előfizetési csomagjában meghatározott parkolási díjszabása (Ft/perc).
   > A részletes díjszabások elérhetőek előfizetési csomagonként és az adott gépjármű kategóriájának besorolása alapján a ***Price Modelhez*** tartozó **Seeder fájlban**.

2. **Parkolási Idő Számítása (T):**
   - **T = t_vége - t_kezd**
   - **t_kezd:** A parkolási esemény kezdő időpontja
   - **t_vége:** A parkolási esemény lezárásának időpontja

3. **Teljes Parkolási Költség Számítása:**
   - **K = D × T**
   - **K:** A felhasználó által fizetendő teljes parkolási díj

> Parkolási esemény generálásának meghívása
```bash
$parkingFactory = new CarUserRentParkingFactory();
$parkolasok = $parkingFactory->generaltParkolasok(
    $berlesKezdete,
    $berlesVege,
    $arazas,
    $user,
    $auto,
    $parkolasokAranyok
);
```
> Parkolási költség számításának meghívása
```bash
$koltseg = $parkingFactory->parkolasiKoltsegek(
    $user,
    $auto,
    $arazas,
    $parkolasok
);
```

### Parkolási Események Validációja

A rendszer a **userFullTimeRentValidation** függvénnyel végzi a parkolási események visszaellenőrzését, hitelesítését. Ennek érdekében az **egy bérlési ciklusra** jutó **parkolások össz időintervalluma** leszabályozásra került, melynek értéke **maximum** a teljes bérlési-ciklus időintervallumának **60% lehet**.

> Implementáció:

```bash
$maxParkingMinutes = round($totalMinutes * 0.6);
if ($osszesParkolasIdo > $maxParkingMinutes) 
   {
    $excessParking = $osszesParkolasIdo - $maxParkingMinutes;
    $vezetesIdo += $excessParking;
}
```

## Biztonsági Mechanizmusok
A ParkingFactory több rétegű **biztonsági eljárásokat alkalmaz** annak érdekében, hogy a parkolási folyamatok hibamentesen és megbízhatóan működjenek:
   - Parkolási Folyamat Védelme
   - Adatvalidáció
   - Költségszámítási Védelem
   - Üzleti Logika Védelme


| Parkolási Folyamat Védelme | Adatvalidáció | Költségszámítási Védelem | Üzleti Logika Védelme |
|----------------------------|---------------|--------------------------|-----------------------|
| - **Minimális** parkolási idő betartása<br>- **Időzítési** konfliktusok kezelése, átfedések elkerülése<br>- **Maximális** parkolási időkorlátok ellenőrzése<br>- **Arányosított** parkolási idő felosztása a valós parkolás szimulálására | - Bemeneti paraméterek **ellenőrzése**<br>- Parkolási értékek **korlátozása**<br>- Időintervallumok **validációja** | - Negatív összegek kizárása<br>- Maximális díjak korlátozása<br>- Kedvezmények helyes alkalmazása | - **Felhasználói** díjak biztonságos kezelése<br>- **Gépjármű kategóriák** biztonságos besorolása<br>- **Bérlési időszak integritásának** megőrzése |

## Rendszer Interakciók
A ParkingFactory szorosan **együttműködik** a következő **komponensekkel**:

### Bérlési Rendszer
- Bérlési események kezelése
- Parkolási időpontok koordinálása
- Költségszámítások integrálása

### Töltéskezelő Rendszer (ChargeFactory)
- A **ChargeFactory** által generált parkolási **igények fogadása és feldolgozása**
- **Integrált döntéshozatal** a töltési és parkolási események között
- **Energiagazdálkodási adatok** közös **kezelése**

___
>
>A ParkingFactory implementációja megfelel azoknak a kritikus működési és integrációs **teszteseteknek**, amelyeket a **ParkingFactoryTest.php** fájl **definiál**. Ezek a tesztek ellenőrzik:
> - A parkolási döntések helyes aktivációját, a bemeneti paraméterek és határértékek alapján.
> - A ***dinamikus díjszámítást***, a felhasználói előfizetések és autókategóriák kezelését.
> - Az ***integrációs pontokat***, különösen a ChargeFactory-vel történő együttműködést.
>
>>  **Fontos!** 
>  A tesztesetek konkrét részleteit ezen dokumentáció ***nem tartalmazza***, csupán a rendszer viselkedésére gyakorolt hatásukat foglaltam össze a ***Biztonsági Mechanizmusok*** részben.
>
___

### Előfizetési Rendszer
- Kedvezmények kezelése
- Jogosultságok ellenőrzése
- Speciális díjszabások alkalmazása

### Flottakezelő Rendszer
- Járműkategóriák kezelése
- Parkolási események rögzítése
- Statisztikák és jelentések generálása


