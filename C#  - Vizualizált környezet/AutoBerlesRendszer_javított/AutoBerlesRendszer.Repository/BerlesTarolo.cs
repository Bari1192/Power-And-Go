using AutoBerlesRendszer.Data;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Repository
{
    public class BerlesTarolo : IRepository<Berles>
    {
        private List<Berles> berlesek;

        public BerlesTarolo()
        {
            berlesek = new List<Berles>();
        }

        public void Hozzaadas(Berles berles)
        {
            berlesek.Add(berles);
        }

        public List<Berles> Osszes()
        {
            return berlesek;
        }

        public Berles AzonositoAlapjan(int id)
        {
            return berlesek.FirstOrDefault(b => b.Id == id);
        }

        public void Torles(int id)
        {
            var berles = AzonositoAlapjan(id);
            if (berles != null)
            {
                berlesek.Remove(berles);
            }
        }

        public void Frissites(Berles berles)
        {
            var letezoBerles = AzonositoAlapjan(berles.Id);
            if (letezoBerles != null)
            {
                letezoBerles.BerlesKezd = berles.BerlesKezd;
                letezoBerles.BerlesVeg = berles.BerlesVeg;
                letezoBerles.KategoriaId = berles.KategoriaId;
                letezoBerles.FelhasznaloNev = berles.FelhasznaloNev;
                letezoBerles.Rendszam = berles.Rendszam;
            }
        }
        public void BeolvasBerlesFajlbol(string fajlNev)
        {
            var sorok = File.ReadAllLines(fajlNev);
            foreach (var sor in sorok)
            {
                var adatok = sor.Split(',');

                Berles berles = new Berles
                {
                    Id = int.Parse(adatok[0].Trim()),
                    BerlesKezd = DateTime.Parse(adatok[1].Trim()),
                    BerlesVeg = DateTime.Parse(adatok[2].Trim()),
                    KategoriaId = int.Parse(adatok[3].Trim()),
                    FelhasznaloNev = adatok[4].Trim(),
                    Rendszam = adatok[5].Trim()
                };

                Hozzaadas(berles);
            }
        }
    }
}