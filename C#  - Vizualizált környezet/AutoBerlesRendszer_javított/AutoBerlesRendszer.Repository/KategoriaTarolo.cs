using AutoBerlesRendszer.Data;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Repository
{
    public class KategoriaTarolo : IRepository<Kategoria>
    {
        private List<Kategoria> kategoriak;

        public KategoriaTarolo()
        {
            kategoriak = new List<Kategoria>();
        }

        public void Hozzaadas(Kategoria kategoria)
        {
            kategoriak.Add(kategoria);
        }

        public List<Kategoria> Osszes()
        {
            return kategoriak;
        }

        public Kategoria AzonositoAlapjan(int id)
        {
            return kategoriak.FirstOrDefault(k => k.Id == id);
        }

        public void Torles(int id)
        {
            var kategoria = AzonositoAlapjan(id);
            if (kategoria != null)
            {
                kategoriak.Remove(kategoria);
            }
        }

        public void Frissites(Kategoria kategoria)
        {
            var letezoKategoria = AzonositoAlapjan(kategoria.Id);
            if (letezoKategoria != null)
            {
                letezoKategoria.KatBesorolas = kategoria.KatBesorolas;
                letezoKategoria.KatModellNev = kategoria.KatModellNev;
                letezoKategoria.AutokIdFK = kategoria.AutokIdFK;
            }
        }

        public void BeolvasKategoriaFajlbol(string fajlNev)
        {
            var sorok = File.ReadAllLines(fajlNev);
            foreach (var sor in sorok)
            {
                var adatok = sor.Split(',');

                Kategoria kategoria = new Kategoria
                {
                    Id = int.Parse(adatok[0].Trim()),
                    KatBesorolas = int.Parse(adatok[1].Trim()),
                    KatModellNev = adatok[2].Trim(),
                    AutokIdFK = int.Parse(adatok[3].Trim())
                };

                Hozzaadas(kategoria);
            }
        }
    }
}