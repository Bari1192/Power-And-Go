# Dokumentáció




## Backend

### Lezárt bérlések


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

