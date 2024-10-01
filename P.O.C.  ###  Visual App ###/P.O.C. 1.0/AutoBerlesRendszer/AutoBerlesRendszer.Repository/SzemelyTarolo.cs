using AutoBerlesRendszer.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Repository
{
    public class SzemelyTarolo : IRepository<Szemely>
    {
        private List<Szemely> szemelyek;

        public SzemelyTarolo()
        {
            szemelyek = new List<Szemely>();
        }

        public void Hozzaadas(Szemely entity)
        {
            szemelyek.Add(entity);
        }

        public void Torles(int id)
        {
            var szemely = szemelyek.Find(s => s.Id == id);
            if (szemely != null)
            {
                szemelyek.Remove(szemely);
            }
        }

        public void Frissites(Szemely entity)
        {
            var index = szemelyek.FindIndex(s => s.Id == entity.Id);
            if (index != -1)
            {
                szemelyek[index] = entity;
            }
        }

        public Szemely AzonositoAlapjan(int id)
        {
            return szemelyek.Find(s => s.Id == id);
        }

        public List<Szemely> Osszes()
        {
            return new List<Szemely>(szemelyek);
        }

        public void BeolvasSzemelyFajlbol(string fajlNev)
        {
            var sorok = File.ReadAllLines(fajlNev);
            foreach (var sor in sorok)
            {
                var adatok = sor.Split(',');

                var szemely = new Szemely
                {
                    Id = int.Parse(adatok[0].Trim()),
                    SzigSzam = adatok[1].Trim(),
                    JogosítvanySzam = adatok[2].Trim(),
                    JogosítvanyErvenyesseg = DateTime.Parse(adatok[3].Trim()),
                    JogosítvanyLejarat = DateTime.Parse(adatok[4].Trim()),
                    VezetekNev = adatok[5].Trim(),
                    KeresztNev = adatok[6].Trim(),
                    FelhasznaloNev = adatok[7].Trim(),
                    Jelszo = adatok[8].Trim(),
                    SzuletesiDatum = DateTime.Parse(adatok[9].Trim()),
                    Telefon = adatok[10].Trim(),
                    Email = adatok[11].Trim()
                };

                Hozzaadas(szemely);
            }
        }
    }
}