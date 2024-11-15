using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public class DataStore
    {
        // ##   Listák a fájltípusokhoz  ## \\
        readonly List<Auto> autok = new();
        readonly List<Felszereltseg> felszereltseg = new();
        readonly List<Kategoria> kategoriak = new();
        readonly List<Szemely> szemelyek = new();
        readonly List<Felhasznalo> felhasznalok = new();
        readonly List<Lezart_berles> lezart_Berlesek = new();

        // Publikus Interface hozzáférések \\
        public IEnumerable<Auto> Autok => autok;
        public IEnumerable<Felszereltseg> Felszereltsegek => felszereltseg;
        public IEnumerable<Kategoria> Kategoriak => kategoriak;
        public IEnumerable<Szemely> Szemelyek => szemelyek;
        public IEnumerable<Felhasznalo> Felhasznalok => felhasznalok;
        public IEnumerable<Lezart_berles> Lezart_Berlesek => lezart_Berlesek;


        private DataStore(string fajlhelye)
        {
            Parallel.Invoke(
                () => FajlBeolvasasok(Path.Combine(fajlhelye, "autok.csv"), autok, Auto.Beolvasas),
                () => FajlBeolvasasok(Path.Combine(fajlhelye, "felszereltsegek.csv"), felszereltseg, Felszereltseg.Beolvasas),
                () => FajlBeolvasasok(Path.Combine(fajlhelye, "kategoriak.csv"), kategoriak, Kategoria.Beolvasas),
                () => FajlBeolvasasok(Path.Combine(fajlhelye, "szemelyek.csv"), szemelyek, Szemely.Beolvasas),
                () => FajlBeolvasasok(Path.Combine(fajlhelye, "felhasznalok.csv"), felhasznalok, Felhasznalo.Beolvasas),
                () => FajlBeolvasasok(Path.Combine(fajlhelye, "lezart_berlesek.csv"), lezart_Berlesek, Lezart_berles.Beolvasas)
            );
        }
        private void FajlBeolvasasok<T>(string filePath, List<T> lista, Func<string, T?> parseFunc) where T : class
        {
            using (var reader = new StreamReader(filePath))
            {
                reader.ReadLine(); // Fejléc egyikhez sem kell \\
                string? sor;
                while ((sor = reader.ReadLine()) != null)
                {
                    var elem = parseFunc(sor); // Beolvasás függvényt hívja meg az adott osztályon belül. \\
                    if (elem != null)
                    {
                        lista.Add(elem);
                    }
                }
            }
        }
        public static DataStore Beolvasas(string directory = "input") => new(directory);
    }
}