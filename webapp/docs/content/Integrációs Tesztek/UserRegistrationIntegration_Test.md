# RegisterController

## RegisterController Integrációs Tesztjegyzőkönyve

### 1. **POST /api/persons**

- **Cél**: Egy új `Person` létrehozása az adatbázisban.
- **Teszt metódus**: `test_can_regist_user_to_database_with_its_all_keys`
- **Elvárt eredmény**:
  - HTTP válasz státusz: `201 CREATED`.
  - Az adatbázisban megjelenik az új `Person` rekord.
  - A létrehozott rekord tartalmazza a következő mezőket:
    - `person_password`
    - `firstname`
    - `lastname`
    - `phone`
    - `email`

---

### 2. **POST /api/users**

- **Cél**: Egy új `User` létrehozása az adatbázisban a `Person` ID alapján.
- **Teszt metódus**: `test_can_regist_user_to_database_with_its_all_keys`
- **Elvárt eredmény**:
  - HTTP válasz státusz: `201 CREATED`.
  - Az adatbázisban megjelenik az új `User` rekord.
  - A létrehozott rekord tartalmazza a következő mezőket:
    - `person_id` (kapcsolódik a korábban létrehozott `Person` rekordhoz)
    - `user_name` (egyedi érték)
    - `password` (hashelt jelszó)
    - `password_2_4`
    - `account_balance`
    - `sub_id`
  - A válasz tartalmazza a `user_name` kulcsot.

---

### 3. **POST /api/authenticate**

- **Cél**: Egy létező `User` hitelesítése a rendszerben.
- **Teszt metódus**: `test_can_regist_user_to_database_with_its_all_keys`
- **Elvárt eredmény**:
  - HTTP válasz státusz: `200 OK`.
  - A válasz tartalmazza a `token` kulcsot.
  - A `token` értéke egy érvényes hitelesítési token.

---

### 4. **Token ellenőrzés**

- **Cél**: Annak ellenőrzése, hogy a hitelesítési token sikeresen generálódott.
- **Teszt metódus**: `test_can_regist_user_to_database_with_its_all_keys`
- **Elvárt eredmény**:
  - A válasz JSON tartalmazza a `token` kulcsot.
  - A token nem lehet üres, és egyedinek kell lennie.

---

## Teszt forgatókönyv

| **Lépés** | **Cél**                                | **Várható eredmény**                 |
|-----------|----------------------------------------|--------------------------------------|
| 1         | Tesztadatok generálása (`Person`)      | Az adatok megfelelően generálódnak. |
| 2         | `Person` rekord mentése az adatbázisba | HTTP 201 válasz és új rekord.       |
| 3         | `User` rekord mentése az adatbázisba   | HTTP 201 válasz és új rekord.       |
| 4         | `User` hitelesítése                   | HTTP 200 válasz és token.           |
| 5         | Token ellenőrzése                     | A token kulcs létezik és érvényes.  |

### Tesztadatok az adatbázisban
#### **Person tábla ellenőrzése**

- **Elvárt rekord**:
  - `person_password`: `12345678`

  - `firstname`: `Teszt`
  - `lastname`: `Felhasználó`
  - `phone`: `+36301234567`
  - `email`: `teszt@example.com`

#### **User tábla ellenőrzése**
- **Elvárt rekord**:

  - `person_id`: A korábban létrehozott `Person` rekord ID-ja.

  - `user_name`: Egyedi érték, például: `Test123456789`.
  - `password`: Hashelt jelszó.
  - `password_2_4`: Az első és harmadik karakter az eredeti jelszóból (`12`).
  - `account_balance`: `0`.
  - `sub_id`: `1`.

---

### Függőségek és előfeltételek

- Laravel alkalmazás futtatása Docker környezetben.

- PHPUnit telepítve és konfigurálva.
- Alkalmazás kulcs generálva (`php artisan key:generate`).
- Migrációk futtatva (`php artisan migrate`).
- **Használt Trait**: `RefreshDatabase`.

---

### Tesztelési korlátok

1. A teszt nem használja a `/register` végpontot, hanem manuálisan hívja meg az egyes műveleteket.

2. Nem vizsgál szélsőséges eseteket, például üres vagy hibás adatokkal történő hívásokat.
3. A token érvényességi időt és lejáratot nem ellenőrzi.

---

### Javasolt további tesztek

1. **`/register` végpont tesztelése**:
   - Egy lépésben hozza létre a `Person` és `User` adatokat.

   - Validálja a mezők helyességét és kapcsolati integritását.

2. **Hibakezelési tesztek**:
   - Duplikált `user_name` érték kezelése.

   - Üres mezőkkel történő validáció.

3. **Token kezelés tesztelése**:
   - Token érvényességi idő ellenőrzése.

   - Lejárt tokenek kezelése.

4. **Teljesítménytesztek**:
   - Nagy mennyiségű párhuzamos kérés kezelése regisztráció és autentikáció során.

---

### Összegzés

- **Teszt lefedettség**: A teszt validálja a regisztrációs folyamat alapvető lépéseit: `Person` és `User` létrehozását, az adatok adatbázisba mentését, az autentikációt, és a token generálását.

- **Sikeres műveletek**:
  - `Person` és `User` rekordok helyes létrehozása.
  - Hitelesítés és token generálás megfelelő működése.
  - Az adatok adatbázisban való tárolása megfelel az elvárásoknak.

- **Teszt stabilitása**: A teszt stabil és újrafuttatható, a környezet tisztítását a `RefreshDatabase` trait biztosítja.

- **Hiányosságok**:
  - A `/register` végpontot nem használja.
  - Nem teszteli szélsőséges eseteket, például üres vagy hibás adatok kezelését.
  - A token érvényességi idejének és lejáratának ellenőrzése nem része a tesztnek.

---
---

### Tesztlépések és eredmények összefoglalása

| **Lépés**                  | **Művelet**                                  | **Eredmény**                                              | **Státusz** |
|----------------------------|----------------------------------------------|----------------------------------------------------------|-------------|
| **1. Tesztadatok generálása** | `Person` adatok előállítása egyedi jelszóval | Az adatok helyesen generálódtak (`person_password: 12345678`). | ✔️          |
| **2. Person létrehozása**   | `POST /api/persons`                         | HTTP 201 válasz, új rekord az adatbázisban.              | ✔️          |
| **3. Person adat lekérése** | `Person::latest('id')->first()`             | A legutóbb létrehozott `Person` rekord lekérve.          | ✔️          |
| **4. User létrehozása**     | `POST /api/users`                           | HTTP 201 válasz, új `User` rekord az adatbázisban.       | ✔️          |
| **5. User válasz ellenőrzése** | JSON válasz ellenőrzése (`user_name`)       | A válasz tartalmazza a `user_name` kulcsot.              | ✔️          |
| **6. Authentikáció**        | `POST /api/authenticate`                    | HTTP 200 válasz, sikeres bejelentkezés.                  | ✔️          |
| **7. Token ellenőrzése**    | JSON válasz ellenőrzése (`token` kulcs)      | A válasz tartalmazza az érvényes `token`-t.              | ✔️          |