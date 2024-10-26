using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public class DataStore
    {
        readonly List<Auto> autok;
        readonly List<Felszereltseg> felszereltseg;
        readonly List<Kategoria> kategoriak;
        readonly List<Szemely> szemelyek;
        readonly List<Felhasznalo> felhasznalok;
        readonly List<Lezart_berles> lezart_Berlesek;
        public IEnumerable<Lezart_berles> Lezart_Berlesek => lezart_Berlesek;
        public IEnumerable<Felhasznalo> Felhasznalok => felhasznalok;

        public IEnumerable<Szemely> Szemelyek => szemelyek;
        public IEnumerable<Kategoria> Kategoriak => kategoriak;
        public IEnumerable<Felszereltseg> Felszereltsegek => felszereltseg;
        public IEnumerable<Auto> Autok => autok;


        private DataStore(string fajlhelye)
        {
            autok = File.ReadLines(Path.Combine(fajlhelye, "Auto.cs")).Select(Auto.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            felszereltseg = File.ReadLines(Path.Combine(fajlhelye, "Felszereltseg.cs")).Select(Felszereltseg.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            kategoriak = File.ReadLines(Path.Combine(fajlhelye, "Kategoria.cs")).Select(Kategoria.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            szemelyek = File.ReadLines(Path.Combine(fajlhelye, "Szemely.cs")).Select(Szemely.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            felhasznalok = File.ReadLines(Path.Combine(fajlhelye, "Felhasznalo.cs")).Select(Felhasznalo.Beolvasas).Where(x => x != null).Select(x => x!).ToList();
            lezart_Berlesek = File.ReadLines(Path.Combine(fajlhelye, "Lezart_berles.cs")).Select(Lezart_berles.Beolvasas).Where(x => x != null).Select(x => x!).ToList();

        } // DataStore Class Lezárása

        public DataStore Beolvasas(string directory = "input") => new(directory);
    }
}