# A működés tartalma

1. A felhasználó a fleetIndex.vue oldalon látja a flotta autóinak listáját
2. A felhasználó rákattint egy autó "Módosítás" gombjára
3. A BaseFleetCards.vue komponensben az editFleet függvény:
4. Betölti az aktuális autó adatait az editableData ref objektumba
5. Beállítja az isEditing értékét true-ra
6. Emittálja az edit eseményt az autó ID-jával
7. Az EditFleetCar komponens megjelenik a szerkesztési űrlappal
8. A felhasználó módosítja az adatokat és a "Mentés" gombra kattint
9. Az EditFleetCar komponens emittálja a save eseményt az ID-val és az új adatokkal
10. A BaseFleetCards.vue komponens saveChanges függvénye elfogja ezt az eseményt és továbbítja az update eseményt a szülő komponens felé
11. A fleetIndex.vue komponens frissites függvénye elfogja az update eseményt és meghívja a Pinia store updateFleet metódusát
12. A Pinia store frissíti az adatbázist és a lokális state-et