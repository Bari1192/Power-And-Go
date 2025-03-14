# Charge Factory - Technikai Dokumentáció

_(CarUserrentChargeFactory - Töltéskezelő Rendszer Specifikáció)_

## Áttekintés

A **CarUserrentChargeFactory** egy komplex _töltéskezelő rendszer_, amely az elektromos járművek töltési folyamatait szimulálja és kezeli a bérlési időszak alatt. A rendszer figyelembe veszi a különböző járműkategóriák egyedi tulajdonságait, és ezen tulajdonságok alapján intelligens döntéseket képes hozni **többlépcsős ellenőrzési folyamatokkal** a töltési események szükségességéről és azok végrehajtásáról.

## Működési Alapelvek

### Energiagazdálkodási Alapszabályok

A rendszer alapvető energiagazdálkodási elvei a következők:

- Az **autó** energiaszintjének folyamatos **monitorozása**
- **Töltési szükséglet** valós idejű **meghatározása**
- **Töltési folyamatok optimalizálása** az autó specifikációi alapján
- Felhasználói **kreditrendszer** kezelése a töltési események után

### Töltés Döntéshozatali Mechanizmusa

A rendszer logikai magját a `kellHozzaTolteniAutot` függvény határozza meg és kezeli. Az alábbi feltételek alapján hoz tényleges döntéseket és rendeli el a töltés szükségességét:

#### Alapvető kizáró feltételek:
- A jármű töltöttsége **meghaladja** a _60%_-ot
- A bérlési időtartam **kevesebb** mint _20 perc_

#### A töltés szükségeségét állapítja meg, ha:
- A megtett **távolság** meghaladja a jelenlegi töltöttséggel megtehető maximális távot
- A bérlési idő és energiafogyasztás párhuzamában kritikus szint elérése feltételezhető
- A **bérlési idő** több mint **60** perc **és** a töltöttség **35%** vagy az alatt van
- A bérlési idő több mint **180** perc **és** a töltöttség **50%** vagy az alatt van

Minden további esetben, melynél - a fentiekben meghatározott - feltétel közül egy sem teljesül, a **rendszer nem ír elő töltést**, és a jármű az aktuális töltöttségi szintjével folytathatja az útját a generálási folyamat, bérlés modellezése során.

## Technikai Implementáció

### Energiaszámítási Modell
A rendszer döntéshozatali matematikai modellje a következő számításokat végzi el a háttérben:

1. **Töltési Teljesítmény meghatározása(P)**:
   - P = min(Ptöltő, Pjármű)
   - Ptöltő: töltőállomás teljesítménye
   - Pjármű: jármű maximális töltési kapacitása

Ahol a **töltési teljesítmény** (P) kiszámítása az adott **töltőoszlop kapacitásának** és a **jármű maximális töltési teljesítményének** függvényében történik:
- P = min (**P***töltő*,**P***max* ). A töltőoszlop teljesítménye *(pl. 50 kW)*,  **P***max* pedig a **jármű** által elfogadható maximális **töltésfelvételi kapacitása** *(teljesítménye)*.

2. **Töltési idő számítása**
   **A teljes töltési idő kiszámítása:**
   - **t = E / P**
   
   Ahol az értékek:
   - **t** = Töltési idő
   - **E** = Az autó akkumulátorának maximum kapacitása *(kW-ban)*
   - **P** = Az elérhető maximális töltési teljesítmény mértéke
   
3. **Töltési sebesség meghatározása**
Az átlagos töltési sebesség kiszámítása:
   - **S =  E / t**
   Ahol az értékek:
   - **S** = Az átlagos töltési sebességet határozza meg *(kw/perc)*
   - **E** = Az autó akkumulátor aktuális kapacitása *(kW-ban)*
   - **t** = A töltéshez szükséges teljes időintervallumot

4. Hatótáv számítása a töltés alapján
A jármű energiafelhasználásának figyelembevételével a maximálisan elérhető távolság.*1*
   - **R = E * η**
   Ahol az értékek:
   - **E**: rendelkezésre álló energia
   - **η**: energiahatékonysági együttható *2*

> **  **
>> **Fontos!**
>>
>>*1. - (A hivatalos forgalmazó álltal meghatározott feltételekben foglaltak szerinti, átlagos felhasználást és futásteljesítményt feltételezve.)*
>>
>>*2. - Számított és előre becsült érték. A mindenkori felhasználás során összegyűjtött és elemzett adatok statisztikai átlagából számított értékre utal.*
>
> *Felhasználása csak iránymutatási célokra, tájékoztató jellegére szolgál!* 
**  **

### Kategória-specifikus Töltési Sebességek 

#### Maximum 22 kWh-s Töltőrendszer Használatával

| **Model Megnevezése** | **Akku. kapacitása** | **Becs. Töltési sebesség** | **Becs. Teljes Töltési Ciklus** | **Töltőoszlop kapacitása** |
|---|---|---|---|---|
| VolksWagen **E-up!** | 18 kW | 0.32-0.37 kW/perc | 45-60 perc | Max. 22 kWh |
| Renault **Kangoo** (33 kWh) | 33 kw | 0.32-0.37 kWh/perc | 90-120 perc | Max. 22 kWh |
| Skoda **Citigo** & VW **E-up!**  | 36 kw | 0.32-0.37 kWh/perc | 90-120 perc | Max. 22 kWh |
| Opel **Vivaro-e**  | 75 kw | 0.37-0.40 kWh/perc | 90-120 perc | Max. 22 kWh |
| Kia **Niro-EV** | 65 kw | 0.51-0.61 kWh/perc | 120-130 perc | Max. 22 kWh |


#### Maximum 50 kWh-s Töltőrendszer Használatával

| **Model Megnevezése** | **Akkumulátor max. kapacitása** | **Becs. Töltési sebesség** | **Becs. Töltési idő ~5%-ról 100%-ra** | **Töltőoszlop kapacitása** |
|---|---|---|---|---|
| VolksWagen **E-up!** | 18 kW | 0.40-0.43 kW/perc | ~43-48 perc | Max. 50 kWh |
| Renault **Kangoo** (33 kWh) | 33 kw |  0.8-0.83 kW/perc | ~40-45 perc | Max. 50 kWh |
| Skoda **Citigo** & VW **E-up!**  | 36 kw | 0.8-0.83 kW/perc | ~43 perc | Max. 50 kWh |
| Opel **Vivaro-e**  | 75 kw |  0.8-0.83 kW/perc | ~90 perc | Max. 50 kWh |
| Kia **Niro-EV** | 65 kw | 0.8-0.83 kW/perc | ~78-80 perc | Max. 50 kWh |

___

> ** ***Figyelem!*** **
>
> - *A valóságos töltési idő a jármű maximális töltési teljesítményét veszi figyelembe, nem a töltő osztályát.*
> - *A 18kw-os akkumulátorral felszerelt VW e-up például az akkumulátor- és járműspecifikus korlátozások miatt ***nem képes 50 kW-ot*** felvenni*.
>
> - *Ebből fakadóan az ***50 kW-os*** töltő használatával egyetemben is csupán ***~25 kW körüli átlagos teljesítménnyel töltene***, ami a 0%-ról 100%-ra való feltöltési időt ***relevánsabb mértékben nem csökkentené le***, maximum a minimum töltési idő értékéhez közelebbi eredmény várható.*
>
> - *A töltési folyamat során a **töltési függvény-görbe** figyelembevétele mellett arányosított, **átlag** töltési sebesség került feltüntetésre.*
> - *A rendszer ennek figyelembevételével köztes töltési értékekkel számol. Modelenként, időjárástól, akkumulátor kapacitásától és a használt töltőoszlop tényleges teljesítményének függvényében változó érték. A rendszer számításai során **referenciaadatként használja**.*
>
___

### A Rendszer Töltési Folyamatának Végrehajtása
#### A töltési folyamat lépései
___
| **Töltési Paraméterek Inicializálása** | **Töltési Folyamat Optimalizálása** | **Energiaszint Frissítések** |
|---|---|---|
| Kategória-specifikus töltési sebesség meghatározása | Minimum töltési idő: 5 perc | Töltöttségi szint folyamatos monitorozása |
| Kezdeti energiaszintek rögzítése | Maximum töltési idő: bérlési időtartam - 10 perc | Hatótáv újraszámítása |
| Időkeretek beállítása | Valós idejű korrekciók az aktuális paraméterek alapján | Biztonsági limitek betartása |


### Kreditrendszer
___
A töltési események után a rendszer automatikusan **számítja** és **jóváírja** a felhasználói **krediteket**:

- 6 kW **alatti** töltés: 400 Ft-nak megfelelő kredit összeg kW értékenként.
- 5 kW érték **felett** minden további kiloWattért: alapdíj 2000 kredit + 200 kredit/kWh

> **Példa a töltés és kredit jóvírásra:**
> 8 kw töltés esetén 5*400 Ft kredit + 3x200 Ft értékű kredit. **Összesen 2600 Ft töltési kredit** kerül jóváírásra a felhasználó számlájára a bérlés lezárta után.

___
## Biztonsági Mechanizmusok
___
A rendszer többrétegű biztonsági mechanizmusokat implementál magában:

### Töltési Folyamat Védelme
   - **Túltöltés** elleni védelem
   - **Minimális** töltési szint garantálása
   - **Időzítési** konfliktusok kezelése

### Adatvalidáció
   - Bemeneti paraméterek **ellenőrzése**
   - Töltési értékek **korlátozása**
   - Időintervallumok **validációja**

### Üzleti Logika Védelme
   - **Kategória-specifikus** korlátozások betartása
   - **Felhasználói** kreditek biztonságos kezelése
   - Bérlési időszak integritásának, egyediségének megőrzése


## Rendszer Interakciók
A rendszer szorosan **együttműködik** a következő **komponensekkel**:

### Bérlési Rendszer
   - Bérleti információk feldolgozása
   - Időpontok koordinálása
   - Költségszámítások integrálása

### Flottakezelő Rendszer
   - Járműállapot monitoring
   - Energiaszint követés
   - Karbantartási események koordinálása

### Felhasználói Rendszer
   - Kreditrendszer kezelése
   - Felhasználói visszajelzések feldolgozása
   - Jogosultságok ellenőrzése