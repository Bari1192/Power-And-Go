# Dokumentáció

## Backend

[SORREND] 5. A `folyamatban lévő bérléseket` is át kell alakítani, hogy `státusz` alapján `lezárás után` a `lezárt bérlések táblába kerüljenek` az adatok 6. `Konyvelesi táblát` is létre kell hozni, ahová a `lezárt bérlések számlák` kifizetés után `kerülnek` (hogy profilban elérhető legyen + könyvelési osztály is elérje, hogy a NAV ne szóljon be)

### [Lezárt-Bérlések]-[Autok-Töltöttség-%-Hatótáv-km]

1. Funkciók és Alapvető Célok
   Az Autók és Lezárt bérlések kapcsolatrendszerének célja az autók aktuális állapotának, töltöttségi szintjének, hatótávjának és bérlési adatainak pontos nyilvántartása. A logika biztosítja, hogy:

- Az autók energiafelhasználása és állapota dinamikusan, valós időben frissüljön.
- Az aktuális töltöttségi szint alapján meghatározott maximális távolság figyelembevételével történjen adatgenerálás.
- Az autó "foglalható" státusza automatikusan frissüljön a változások bekövetkeztekor.

2. Fő Komponensek és Funkcióik
   1. `Töltöttségi` Szint Számítás:
      - Az autók kezdeti és záró töltöttségi szintjét a toltes_szazalek mező határozza meg.
      - A hatótáv számítása:
      - Egy kW energia által megtett távolság:
      - egy_kW_hany_km = hatotav / teljesitmeny
      - Kezdeti hatótáv kiszámítása:
      - becsult_hatotav = egy_kW_hany_km \* toltes_kw
      - Záráskor várható állapot: zaras_toltes_kw = nyitas_toltes_kw - fogyasztott_kw
   2. Az autók csak akkor `foglalhatók`, ha:
      - Nyitási töltöttség >= 15%.
      - Zárási töltöttség > 12%.
        Amennyiben az autó nem felel meg ezeknek a kritériumoknak, automatikusan foglalhato = `false` állapotba kerül.
   3. `Kilométeróra Állás` Frissítése:
      - A megtett távolság (megtettTavolsag) alapján az km_ora_allas mező automatikusan frissül.
      - Új érték:
        km_ora_allas = km_ora_allas + megtettTavolsag
3. Működési Logika
   1. Autó Bérlésének Generálása:
      - Az autó adatainak frissítése a lezárt bérlés adatai alapján történik.
      - Ha a záró töltöttségi szint 10% alá csökkenne, a bérlés nem generálható, és az autó "foglalhatatlan" lesz.
   2. Adatfrissítés:
      - Záráskor:
        - toltes_szazalek, toltes_kw és becsult_hatotav mezők frissítése.
        - Ha a töltöttségi szint < 15%, akkor az autó "foglalhatatlan" státuszt kap.
      - Kilométeróra:
        - A megtett távolság automatikusan hozzáadódik a meglévő kilométeróra álláshoz.
4. Példák és Használati Esetek
   1. Autó 75%-os töltöttségi szintről indul:
      - 20 km megtételével 72%-ra csökken.
      - Új hatótáv számítása:
        - zaraskoriToltesKw = 36kW \* 0.72
        - becsult_hatotav = zaraskoriToltesKw \* (hatotav / teljesitmeny)
   2. Autó töltöttségi szint 12% alá csökken:
      - Az autó nem "foglalható", státusza automatikusan frissül.
   3. Új bérlésnél figyelembe vett töltöttségi szint:
      - Az előző bérlés után megmaradt töltöttség az alapja az új bérlésnek.

`Ez a logika lehetővé teszi:`

- Az autók bérlésének dinamikus és pontos nyilvántartását.
- Az autók foglalhatósági állapotának automatikus kezelését.
- A töltöttségi és hatótáv-adatok gyors és hatékony frissítését.

### [Autok-Töltöttség-%-Hatótáv-km]

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

### [Számlázás-TÁBLA]

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

### SZÁMLÁZÁSI LOGIKA LEÍRÁSA [ENYÉM]:

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

### Előfizetések tábla - létrehozása

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

### Árazások tábla - létrehozása

1.  Árkategóriák & besorolások

    - A tábla létrehozása alapvető, mivel az előfizetési csomagok adatait le kell tárolni.
    - Minden felhasználó ehhez kapcsolódik FK-n keresztül: - Egyszerűbb karbantartást biztosít később - árak, kedvezmények, frissítések stb. - Egyértelmű és egységes logika - Statikus tömb-érték (korábbi) rendszer helyett.
      [Entitások]
    - `berles_ind` - Bérlésindítási díj.
    - `vez_perc` - Vezetés (percdíj).
    - `kedv_vez` - Kedvezményes vezetés (percdíj, 6:00 - 9:00) - opcionális.
    - `parkolas_perc` - Parkolás (percdíj).
    - `foglalasi_perc` - Foglalás (percdíj, 20 perc után).
    - `kedv_parkolas_perc` Kedvezményes parkolás (percdíj).
    - `napidij` - Napidíj összege egy adott autóra vetítve.
    - `napi_km_limit` # A napidíjban foglalt megtehető INGYENES km-ek száma.
    - `km_dij` # Ingyenesen (125) megtehető km-en felüli útdíj
    - `repter_ki_felar` # Reptéri felár transzferrel (reptérRE)
    - `repter_be_felar` # Reptéri felár transzferrel (reptérRŐL)
    - `repter_ki_terminal` # Reptéri felár terminálnál (reptérRE)
    - `repter_be_terminal` # Reptéri felár terminálnál (reptérRŐL)
    - `zona_nyit_felar` # Külső zónából való bérlés nyitási, indítási felára (nyitás)
    - `zona_zar_felar` # Külső zónában való bérlés zárási felára (zárás)

    [Seeder]

    - Teljesítményenként az autok 5 csoportban vannak:
      `18 => 1-es`
      `33 => 2-es`
      `36 => 3-as`
      `65 => 4-es`
      `75 => 5-ös`
      `default => 5-ös` - Ha új autót vennénk fel, de még nincsen neki teljesítményalapú beosztása - akkor sem lesz hibás a kalkulálás -> hibamegelőzés.
    - Minden elofizetési kategoriahoz manuálisan hozzuk létre az árak meghatározását. Így később könyebben lehet módosítani, ha változtatásokat kell eszközölni ezekben.

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
2.  [Megvalositas]
    - A futo_berlesek táblának tartalmaznia kell a `status`-t, állapot mező egyikét: 1. Aktív (folyamatban), vagy 2. Lezárt (vége van).
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
     3. Ha a `berles_kezd_ido` 22:00 `UTÁN` van && az `előfizetési kategória` Power-PLUS/VIP, akkor: - `kezdete` időponttól egészen 07:00-ig a --> `parkolási percet` adja vissza. - `parkolási perc` alapján (kategória és előfizetés szerint) `normal_parkolási_percdíjat` számolja `07:00-ig` || a `berles_vege_ido` értékig. Így adja vissza a(z): - `parkolasi_perc` - `parkolasi_osszeg` értékeket.
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
     - Parkolásra fizetendő részletező: 60 perc \* 41 Forint (Power-VIP tagság)
     - Vezetésre fizetendő részletező: 60 perc \* 41 Forint (Power-VIP tagság)
     - `berles_inditasi_dij`: 250 Ft
     - Parkolás Összege (Kedvezmény nélkül): 22 140 Ft
     - `Parkolás` Összege (kedvezménnyel): 2 460 Ft
     - `Vezetés` összege (60\*50 Ft): 3 000 Ft
       [Számla]:
     - Összesen: 5 710 Ft

### Lezárt bérlések

[parkolasi_perc]

#### E-mail kiküldés alapja

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
