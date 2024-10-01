using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public class DataStore
    {
        private readonly List<int> teljesitmenyCsoportok;
        private readonly List<Autok> autok;
        private readonly List<Berles> berlesek;
        private readonly List<Felhasznalok> felhasznalok;
        private readonly List<Kategoriak> kategoriak;
        private readonly List<Szemely> szemelyek;

        public IEnumerable<Autok> AutokK => autok;
        public IEnumerable<Berles> BerlesS => berlesek;
        public IEnumerable<Felhasznalok> FelhasznalokK => felhasznalok;
        public IEnumerable<Kategoriak> KategoriaK => kategoriak;
        public IEnumerable<Szemely> SzemelyEK => szemelyek;

        // Az Autobesorolas() alapján a megkapott "N" számú csoportok alapján
        // Besoroljuk az autokat kategóriákba
        // Kategória besorolást set-teljük az Autok Interface-be.

        private DataStore(string fajlhelye)
        {
            teljesitmenyCsoportok = OsszegyujtTeljesitmenyek(Path.Combine(fajlhelye, "Autok.txt"));

            autok = File.ReadLines(Path.Combine(fajlhelye, "Autok.txt")).Select(sor => Autok.BeolvasEsKategorizal(sor, teljesitmenyCsoportok)).Where(x => x != null).Select(x => x!).ToList(); berlesek = File.ReadLines(Path.Combine(fajlhelye, "Berles.txt")).Select(Berles.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            felhasznalok = File.ReadLines(Path.Combine(fajlhelye, "Felhasznalok.txt")).Select(Felhasznalok.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            kategoriak = File.ReadLines(Path.Combine(fajlhelye, "Kategoriak.txt")).Select(Kategoriak.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            szemelyek = File.ReadLines(Path.Combine(fajlhelye, "Szemely.txt")).Select(Szemely.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
        }
        // Privát beolvasásnak itt adjuk meg a fájl helyét a beolvasáshoz és a lefutást.
        public static DataStore Beolvasas(string fajlhelye = "Input") => new(fajlhelye);
        // Teljesítménycsoportok beolvasása
        public List<int> OsszegyujtTeljesitmenyek(string fajlNev)
        {
            return File.ReadLines(fajlNev)
                .Select(AutokTeljesitmeny.BeolvasTeljesitmenyt)
                .Where(x => x != null)
                .Select(x => x!.Teljesitmeny)
                .Distinct()
                .OrderBy(x => x)
                .ToList();
        }
        public void KiirAutokat()
        {
            foreach (var auto in autok)
            {
                Console.WriteLine($"Autó: {auto.Gyartmany}, Teljesítmény: {auto.Teljesitmeny} kW, Kat.Bes.: {auto.KategoriaBesorolas}");
            }
        }
        // VISSZAELLENŐRZÉS + VIZUALIZÁLÁS

        // Autok besorolása kategóriánként -> Teljesítmény alapján
        // -> kategoria besorolás frissüljön ha az adat átírásra kerül
        // -> Kategóriák mennyiségének kiírása (db)
        // -> Kategórián belüli darabszám kiírása
        // -> Össz darabszám kiírása
        public void Autobesorolas()
        {
            Console.WriteLine("Az adatbázis frissítése folyamatban...\n.\n.\n.\n.\n.\n.");
            Console.WriteLine("##############################################################");
            Console.WriteLine("########### ALKALMAZÁS ADATBÁZISÁNAK LEKÉRDEZÉSE...###########");
            Console.WriteLine("##############################################################\n");
            var telj_csoport = autok.GroupBy(x => x.Teljesitmeny).ToList();
            int kw_telj_csoport_db = autok.GroupBy(x => x.Teljesitmeny).Count();
            Console.WriteLine($"# # # Jelenleg {autok.Count()} db autó érhető el az alkalmazásban # # #\n");
            Console.WriteLine($"# # # Teljesítmény alapján [{kw_telj_csoport_db} különböző autó érhető el az adatbázisban. # # #\n");
            foreach (var sor in telj_csoport.OrderBy(x => x.Key))
            {
                Console.WriteLine($"Ezek közül {sor.Key} kW teljesítményű autóból a flottában jelenleg {sor.Count()} db található.\n");
            }
        }
        // ELKÉSZÍTETT FUNKCIÓK FELSOROLÁSA \\

// - Nincs előre megadott kategória besorolás és teljesitmenyID az Autok fájlban. [eltávolításra került]
// - Nincs előre megadva, hogy hány kategória van/lesz később. [OOP - Automatizáció]
// [-> A csoportosítási Record (Autok.cs) külön teljesitmeny tárolásra kerül, továbbá külön behívásra az 'Autok.txt' fájl.
// [-> Az OsszegyujtTeljesitmenyek() fgv-ben listához adom ismétlés nélkül a kW értékeket leszűrjük.
// [--> Ez alapján hozza létre az 'N' számú csoportot, ami az autók KATEGÓRIA_BESOROLÁSUK -at megadja.
// [--> kategoriaBesorolas() a legkisebb kW értéktől kezdi a kategórizálást ('1'-től).
// [--> Az Autok adatstruktúra teljes beolvasásánál,így már minden kategória besorolása a kW teljesitmeny alapján automatikusan átadásra kerül.
// [--> Ebből kifolyólag egy új Auto modell bekerülése esetén a program automatikusan növeli a kategóriák mennyiségét, ami alapján kategorizál.


// [---> Az előfizetésikategória() alapján és az elkészült AutoKategoriaBesorolás() figyelembevételével
// [----> Adja vissza a bérlésnek a végösszegét.
// [############] Fejlesztési Notes [############]
// 
// 1. Ha új autó kerül be, akkor pl. 10-es kategóriába kerül, így:
// [--> Mi lesz a díjkategória?
// [--> Az Autobesorolas() metódusban megjelenik, mint elérhető autó?
// [--> Az Autobesorolas() Össz. autóbázisában (db) megjelenik / növeli az értékét?
// [--> Az Autobesorolas() metódusban megjelenik, mint jelenleg elérhető (BÉRELHETŐ) autó?

