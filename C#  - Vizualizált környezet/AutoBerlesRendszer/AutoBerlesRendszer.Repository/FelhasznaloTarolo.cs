using AutoBerlesRendszer.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Repository
{
    public class FelhasznaloTarolo : IRepository<IFelhasznalo>
    {
        private List<IFelhasznalo> felhasznalok;

        public FelhasznaloTarolo()
        {
            felhasznalok = new List<IFelhasznalo>();
        }

        public void Hozzaadas(IFelhasznalo felhasznalo)
        {
            felhasznalok.Add(felhasznalo);
        }

        public List<IFelhasznalo> Osszes()
        {
            return felhasznalok;
        }

        public IFelhasznalo AzonositoAlapjan(int id)
        {
            string felhasznaloNev = id.ToString();
            return felhasznalok.FirstOrDefault(f => f.FelhasznaloNev == felhasznaloNev);
        }

        public void Torles(int id)
        {
            var felhasznalo = AzonositoAlapjan(id);
            if (felhasznalo != null)
            {
                felhasznalok.Remove(felhasznalo);
            }
        }

        public void Frissites(IFelhasznalo felhasznalo)
        {
            var letezoFelhasznalo = AzonositoAlapjan(int.Parse(felhasznalo.FelhasznaloNev));
            if (letezoFelhasznalo != null)
            {
                letezoFelhasznalo.ElofizKat = felhasznalo.ElofizKat;
                letezoFelhasznalo.JogosítvanySzama = felhasznalo.JogosítvanySzama;
            }
        }

        public void BeolvasFelhasznaloFajlbol(string fajlNev)
        {
            var sorok = File.ReadAllLines(fajlNev);
            foreach (var sor in sorok)
            {
                var adatok = sor.Split(',');

                IFelhasznalo felhasznalo = new Felhasznalo
                {
                    FelhasznaloNev = adatok[0].Trim(),
                    ElofizKat = int.Parse(adatok[1].Trim()),
                    JogosítvanySzama = adatok[2].Trim()
                };

                Hozzaadas(felhasznalo);
            }
        }
    }
}