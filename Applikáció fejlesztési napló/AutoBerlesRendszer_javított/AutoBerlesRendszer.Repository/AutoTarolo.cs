using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoBerlesRendszer.Data;


namespace AutoBerlesRendszer.Repository
{
    public class AutoTarolo : IRepository<IAuto>
    {
        private List<IAuto> autok;

        public AutoTarolo()
        {
            autok = new List<IAuto>();
        }

        public void Hozzaadas(IAuto auto)
        {
            autok.Add(auto);
        }

        public List<IAuto> Osszes()
        {
            return autok;
        }

        public IAuto AzonositoAlapjan(int id)
        {
            return autok.FirstOrDefault(a => a.Id == id);
        }

        public void Torles(int id)
        {
            var auto = AzonositoAlapjan(id);
            if (auto != null)
            {
                autok.Remove(auto);
            }
        }

        public void Frissites(IAuto auto)
        {
            var letezoAuto = AzonositoAlapjan(auto.Id);
            if (letezoAuto != null)
            {
                letezoAuto.Gyartmany = auto.Gyartmany;
                letezoAuto.Tipus = auto.Tipus;
                letezoAuto.GyartasiEv = auto.GyartasiEv;
                letezoAuto.Teljesitmeny = auto.Teljesitmeny;
                letezoAuto.Gyorsulas = auto.Gyorsulas;
                letezoAuto.Vegsebesseg = auto.Vegsebesseg;
                letezoAuto.Gumimeret = auto.Gumimeret;
                letezoAuto.Hatotav = auto.Hatotav;
                letezoAuto.KategoriaBesorolas = auto.KategoriaBesorolas;
            }
        }

        public void BeolvasAutoFajlbol(string fajlNev)
        {
            var sorok = File.ReadAllLines(fajlNev);
            foreach (var sor in sorok)
            {
                var adatok = sor.Split(',');

                IAuto auto = new Auto
                {
                    Gyartmany = adatok[1].Trim(),
                    Tipus = adatok[2].Trim(),
                    Rendszam = adatok[3].Trim(),
                    Teljesitmeny = int.Parse(adatok[4].Trim()),
                    Gyorsulas = double.Parse(adatok[5].Trim()),
                    Vegsebesseg = int.Parse(adatok[6].Trim()),
                    Gumimeret = adatok[7].Trim(),
                    Hatotav = int.Parse(adatok[8].Trim()),
                    GyartasiEv = int.Parse(adatok[9].Trim())
                };

                auto.BeallitKategoriabesorolas();
                Hozzaadas(auto);
            }
        }
    }
}