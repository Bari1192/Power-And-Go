# User Regisztrációs Oldal Kialakítása

## Alapvető tézis
>User (és egyben Person) regisztrációhoz megvan az aloldal, erre kellene egy interaktív, szép formkit-es regisztráció kitöltő oldalt készíteni.
A usernek az alábbi adatokat kell megadnia:

```json
{
  "person_password": "12345678",
  "id_card": "XXX23456CD",
  "driving_license": "DXR12345",
  "license_start_date": "2024-01-01",
  "license_end_date": "2034-01-01",
  "firstname": "asd",
  "lastname": "asd",
  "birth_date": "1990-01-01",
  "phone": "+36999234999",
  "email": "asd@gmail.com",
  "user_name": "teszteset",
  "pin": "1234",
}
```
Ezeket kell igényesen felveni / bekérni a regisztrálótól a form-kit segítségével a weboldalon. 

## A megvalósítás:
- Külön aloldalon (/register/registerPage.vue) oldalon lesz a regisztráció a gyakorlatban.
- Szerkezetileg egy táblázatot kell elképzelni, aminek a fejléce a "Regisztráció a Power And Go-ba" felirat lesz.
- Alatta lesz 2 oszlop. Az oszlop egyik felében lesznek az adatok ELSŐ fele,
- Az oszlop másik felében lesznek az adatok MÁSODIK fele, az egységes kinézet végett.

## Ellenőrzés

- Az adatokat validálni is kell az alábbiak szerint:
`id_card` esetében ha már van ilyen a rendszerben regisztrálva, akkor azt jelezni kell, DE SZIGORÚAN ÚGY, hogy "Érvénytelen Személyigazolvány" - mivel nem adhatjuk ki, hogy "ez már foglalt", mert személyes adat!!

- `driving_license` esetében ugyanez.
- `license_start_date`és `license_end_date` esetében pedig vizsgálni kell frontend oldalon is, hogy a kettő közötti időkülönbségnek 10 évnek kell lennie, ahogyan ezt a ***rule***-ban meg is írtam már korábban:

```php
   public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->license_startDate || !strtotime($this->license_startDate)) {
            $fail("A kezdési dátum érvénytelen.");
            return;
        }

        $startDate = Carbon::parse($this->license_startDate);
        $endDate = Carbon::parse($value); 

        if (!$startDate->addYears(10)->isSameDay($endDate)) {
            $fail("Hibás a(z) '$attribute' mező. A jogosítvány kiállítási és érvényességi ideje között pontosan 10 évnek kell lennie!");
        }
    }
```
- `birth_date` esetében pedig az aktuális naphoz viszonyítva 18 évesnek kell lennie **MINIMUM** ahhoz, hogy regisztrálhasson - mivel jogsi köteles MO-n.
>  *Ami 17-18 éves kortól van, de a jogi felelősségre vonás miatt 18 lesz és kész.*

- `email`,`phone` esetében ugyanaz vonatkozik rá, azaz jelezni kell, DE SZIGORÚAN ÚGY, hogy "Érvénytelen telefonszám" - mivel nem adhatjuk ki, hogy "ez már foglalt". Továbbá ha a validációs fájl alapján sem felel meg, akkor arra ugyanez kerüljön kiírásra.

- `user_name` esetében nyugodtan jelezhetjük, hogy: "Ez a felhasználónév már foglalt!"

## Végezetül 
Amint minden adatot megadott, minden validáció megfelelő, akkor ezesetben az adatoknak (titkosított úton?) el kell menniük a:

```
http://backend.vm1.test/api/register
```
 végpontra, ahol pedig a backend Hash-eli és eltárolja a szükséges adatokat. Ezután a felhasználó már be is tud lépni.

A sikeres regisztrációról a felhasználót tájékoztatni KELL! (Ez mehet megint ***toasty***-val, egyszerű pop-upolás.)

## K I E G É S Z Í T É S E K 

1. A felhasználónak jó lenne, ha menne `regisztráció` megerősítése `e-mail`,

2. A felhasználó regisztráció beküldésekor PMA-ban `Sys_validation` értéke `pending` státuszban lenne,

3. A megerősítés után >>`user_confirm` mező státusa elsőnek `pending`, majd megerősítés után `user_confirmed` statusa lenne, ami azt jelenti, hogy a **USER OLDALRÓL** meg lett erősítve a regelése. Ebből kifolyólag lehetőség nyílik a sikeres / hatékony regisztrálók, regisztrációk mérésére.

4. Amint a `Sys_validation` értéke `confirmed` értéket kap (*admin megerősíti*) és a `user_confirm` értéke is `confirmed` státuszú lesz a User által, akkor lesz hivatalosan felhasználó (user) a regisztráló személyből (**Person**-ből). 

5. Ugyanezen elv alapján fog működni az `Employee` regisztrációs folyamata is, csak ott picit átalakítva 
**(Role-ok, fizetés és egyebek végett)**.