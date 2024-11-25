# PowerAndGo Webalkalmazás – Projekt Dokumentáció

Ez a projekt egy **fullstack webalkalmazást** valósít meg, amely egy autómegosztó platformhoz készült. A backend részleg egy JSON-szerver alapú adatbázist használ az autók és felhasználói adatok kezelésére, míg a frontend rész Vue.js alapú és dinamikusan jeleníti meg az adatokat különböző oldalakra bontva.

---

## Projekt Felépítése

### Backend
- **JSON-szerver**: Az alkalmazás adatbázisa, amely Docker Compose segítségével indul el, és JSON formátumban tárolja az adatokat.
- **Adatbázis-végpontok**: Az adatbázis különböző végepontokkal rendelkezik az egyes adatkategóriákhoz:
  - `/cars`: Autók adatai (pl. gyártó, típus, teljesítmény)
  - `/car_category`: Autók kategóriái
  - `/personal_datas`: Felhasználók személyes adatai
  - `/users`: Felhasználói adatok (pl. felhasználónév, jelszó)
  - `/rental_history`: Lezárt bérlések listája
  - **Dinamikus routing**: A backend dinamikusan irányítja az egyes végpontokat az autoroutes rendszer segítségével.

### Frontend
- **Vue.js**: A frontend Vue.js alapú, és különböző oldalakra osztva jeleníti meg az adatokat a felhasználói felületen.
- **BaseTable komponens**: Egy univerzális táblázatkomponens, amely dinamikusan jeleníti meg az adatokat különböző gombokra kattintva, a megfelelő adatokkal feltöltve.
- **Navigációs rendszer**: A Vue Router és autoroutes segítségével minden oldal saját útvonallal rendelkezik, így a felhasználók könnyen elérhetik az egyes adatkategóriákat (pl. autók, kategóriák).

---
## Licenc és Felhasználási feltételek

Ez a projekt az MIT licenc alatt érhető el. Ha a kódot nyilvánosan felhasználod vagy kereskedelmi célra értékesíted, kérjük, tüntesd fel az eredeti készítőt. További részletekért lásd a [LICENSE] fájlt.

---