using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;
using AutoBerlesRendszer.Data;
using AutoBerlesRendszer.Repository;

namespace AutoBerlesRendszer.Logic
{
    public class AutoBerlesLogika
    {
        private readonly IRepository<IAuto> autoTarolo;
        private readonly IRepository<Kategoria> kategoriaTarolo;
        private readonly IRepository<IFelhasznalo> felhasznaloTarolo;
        private readonly IRepository<Szemely> szemelyTarolo;
        private readonly IRepository<Berles> berlesTarolo;

        public AutoBerlesLogika(
           IRepository<IAuto> autoTarolo,
           IRepository<Kategoria> kategoriaTarolo,
           IRepository<IFelhasznalo> felhasznaloTarolo,
           IRepository<Szemely> szemelyTarolo,
           IRepository<Berles> berlesTarolo)
        {
            this.autoTarolo = autoTarolo;
            this.kategoriaTarolo = kategoriaTarolo;
            this.felhasznaloTarolo = felhasznaloTarolo;
            this.szemelyTarolo = szemelyTarolo;
            this.berlesTarolo = berlesTarolo;
        }
        // Autók listázása kategóriabesorolásonként sorrendben
        public void ListazAutoKategoriaSzerint()
        {
            Console.WriteLine();
            var autok = autoTarolo.Osszes();
            var kategoriak = kategoriaTarolo.Osszes().Select(k => k.KatBesorolas).Distinct().OrderBy(k => k);

            foreach (var kategoria in kategoriak)
            {
                Console.WriteLine($"Kategória: {kategoria}");
                var autokKategoriaSzerint = autok.Where(a => a.KategoriaBesorolas == kategoria);
                foreach (var auto in autokKategoriaSzerint)
                {
                    Console.WriteLine($"\t{auto.Gyartmany} {auto.Tipus} ({auto.Rendszam})");
                }
            }
            Console.WriteLine();
        }
        // Felhasználók listázása csökkenő sorrendben megtett vezetési percek alapján
        public void ListazFelhasznalokVezetesiPercekSzerint()
        {
            var felhasznalok = felhasznaloTarolo.Osszes();
            var berlesek = berlesTarolo.Osszes();

            var felhasznaloVezetesiPercek = felhasznalok.Select(f => new
            {
                FelhasznaloNev = f.FelhasznaloNev,
                VezetesiPercek = berlesek.Where(b => b.FelhasznaloNev == f.FelhasznaloNev).Sum(b => (int)(b.BerlesVeg - b.BerlesKezd).TotalMinutes)
            })
            .OrderByDescending(f => f.VezetesiPercek);

            foreach (var felhasznalo in felhasznaloVezetesiPercek)
            {
                Console.WriteLine($"Felh.nev: {felhasznalo.FelhasznaloNev}, Percek: {felhasznalo.VezetesiPercek}");
            }
        }
        // Regisztráció
        public void RegisztralFelhasznalo(string vezeteknev, string keresztnev, DateTime szulDatum, string jogositvanySzam, DateTime kiallitasDatum, DateTime lejaratDatum, string telefonszam, string email, string felhasznalonev, string jelszo, int elofizetes)
        {
            if (lejaratDatum < DateTime.Now)
            {
                throw new Exception("A jogosítvány lejárati dátuma nem lehet korábbi, mint a mai dátum.");
            }

            if (kiallitasDatum < lejaratDatum.AddYears(-10))
            {
                throw new Exception("A jogosítvány kiállításának időpontja nem lehet korábbi, mint a lejárati dátum -10 év.");
            }

            if (felhasznaloTarolo.Osszes().Any(f => f.FelhasznaloNev == felhasznalonev))
            {
                throw new Exception("A felhasználónév már foglalt.");
            }

            // Új Személy és Felhasználó létrehozása
            var ujSzemely = new Szemely
            {
                Id = szemelyTarolo.Osszes().Max(s => s.Id) + 1,
                JogosítvanySzam = jogositvanySzam,
                JogosítvanyErvenyesseg = kiallitasDatum,
                JogosítvanyLejarat = lejaratDatum,
                VezetekNev = vezeteknev,
                KeresztNev = keresztnev,
                FelhasznaloNev = felhasznalonev,
                Jelszo = jelszo,
                Telefon = telefonszam,
                Email = email,
                SzuletesiDatum = szulDatum
            };
            var ujFelhasznalo = new Felhasznalo
            {
                FelhasznaloNev = felhasznalonev,
                ElofizKat = elofizetes,
                JogosítvanySzama = jogositvanySzam
            };

            // Új személy és felhasználó hozzáadása 
            szemelyTarolo.Hozzaadas(ujSzemely);
            felhasznaloTarolo.Hozzaadas(ujFelhasznalo);

            //  személy és felhasználó fájlba írása
            File.AppendAllLines("Szemely.txt", new[] { $"{ujSzemely.Id}, {ujSzemely.JogosítvanySzam}, {ujSzemely.VezetekNev}, {ujSzemely.KeresztNev}, {ujSzemely.FelhasznaloNev}, {ujSzemely.Telefon}, {ujSzemely.Email}, {ujSzemely.SzuletesiDatum:yyyy-MM-dd}, {ujSzemely.JogosítvanyErvenyesseg:yyyy-MM-dd}, {ujSzemely.JogosítvanyLejarat:yyyy-MM-dd}" });
            File.AppendAllLines("Felhasznalok.txt", new[] { $"{ujFelhasznalo.FelhasznaloNev}, {ujFelhasznalo.ElofizKat}, {ujFelhasznalo.JogosítvanySzama}" });

            Console.WriteLine("Sikeres regisztráció!");
        }

        //  Felhasználók listázása csökkenő sorrendben megtett vezetési percek alapján előfizetésenként a TOP 10-et
        public void ListazFelhasznalokVezetesiPercekSzerintElofizetesenkent()
        {
            var felhasznalok = felhasznaloTarolo.Osszes();
            var berlesek = berlesTarolo.Osszes();

            var felhasznaloVezetesiPercekElofizetesenkent = felhasznalok
                .GroupBy(f => f.ElofizKat)
                .OrderBy(g => g.Key)
                .Select(g => new
                {
                    ElofizetesKategoria = g.Key,
                    Felhasznalok = g.Select(f => new
                    {
                        FelhasznaloNev = f.FelhasznaloNev,
                        VezetesiPercek = berlesek.Where(b => b.FelhasznaloNev == f.FelhasznaloNev).Sum(b => (int)(b.BerlesVeg - b.BerlesKezd).TotalMinutes)
                    })
                    .OrderByDescending(f => f.VezetesiPercek)
                    .Take(10)
                });

            foreach (var kategoria in felhasznaloVezetesiPercekElofizetesenkent)
            {
                Console.WriteLine($"Előfizetési kategória: {kategoria.ElofizetesKategoria}");
                foreach (var felhasznalo in kategoria.Felhasznalok)
                {
                    Console.WriteLine($"\tFelhasználó: {felhasznalo.FelhasznaloNev}, Vezetési percek: {felhasznalo.VezetesiPercek}");
                }
            }
        }

        public int SzamolNapiDij(int autoId, int felhasznaloId)
        {
            var auto = autoTarolo.AzonositoAlapjan(autoId);
            var felhasznalo = felhasznaloTarolo.AzonositoAlapjan(felhasznaloId);

            if (auto == null || felhasznalo == null)
            {
                throw new Exception("Nem található az autó vagy a felhasználó.");
            }

            return auto.NapiDij(auto.KategoriaBesorolas, felhasznalo.ElofizKat);
        }

        public int SzamolVezetesiPercdij(int autoId, int felhasznaloId)
        {
            var auto = autoTarolo.AzonositoAlapjan(autoId);
            var felhasznalo = felhasznaloTarolo.AzonositoAlapjan(felhasznaloId);

            if (auto == null || felhasznalo == null)
            {
                throw new Exception("Nem található az autó vagy a felhasználó.");
            }

            return auto.VezetesiPercdij(auto.KategoriaBesorolas, felhasznalo.ElofizKat);
        }

        public int SzamolParkolasiPercdij(int autoId, int felhasznaloId)
        {
            var auto = autoTarolo.AzonositoAlapjan(autoId);
            var felhasznalo = felhasznaloTarolo.AzonositoAlapjan(felhasznaloId);

            if (auto == null || felhasznalo == null)
            {
                throw new Exception("Nem található az autó vagy a felhasználó.");
            }

            return auto.ParkolasiPercdij(auto.KategoriaBesorolas, felhasznalo.ElofizKat);
        }

        //Megadott felhasználónév alapján kilistázza az összes bérlést.
        public List<Berles> GetBerlesekFelhasznaloSzerint(string felhasznaloNev)
        {
            return berlesTarolo.Osszes().Where(b => b.FelhasznaloNev == felhasznaloNev).ToList();
        }

        //Megadott felhasználónév és hónap alapján kilistázza az adott hónapban történt bérléseket.
        //Minden bérléshez megjeleníti a bérlés kezdete és vége közötti levezetett perceket.
        public List<Berles> GetBerlesekFelhasznaloSzerintHonapban(string felhasznaloNev, string honapNev)
        {
            int honapSzam = DateTime.ParseExact(honapNev, "MMMM", CultureInfo.CurrentCulture).Month;
            return berlesTarolo.Osszes().Where(b => b.FelhasznaloNev == felhasznaloNev && b.BerlesKezd.Month == honapSzam).ToList();
        }

        // Megadott felhasználónév alapján összeadja az összes bérlés vezetési perceit és megjeleníti az összesítést.
        public int GetFelhasznaloOsszesVezetesiPerce(string felhasznaloNev)
        {
            var berlesek = berlesTarolo.Osszes().Where(b => b.FelhasznaloNev == felhasznaloNev).ToList();
            return berlesek.Sum(b => (int)(b.BerlesVeg - b.BerlesKezd).TotalMinutes);
        }
    }
}