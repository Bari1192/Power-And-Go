using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record AutokTeljesitmeny(int Teljesitmeny)
    {
        public static AutokTeljesitmeny? BeolvasTeljesitmenyt(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new AutokTeljesitmeny(int.Parse(tordelo[4]));
            }
            catch
            {
                return null;
            }
        }
    }

    public record Autok(int Id, string Gyartmany, string Tipus, string Rendszam, int Teljesitmeny, double Gyorsulas,
        int Vegsebesseg, string Gumimeret, int Hatotav, int GyartasiEv, int KategoriaBesorolas)
    {
        public static Autok? BeolvasEsKategorizal(string sor, List<int> teljesitmenyCsoportok)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                var teljesitmeny = int.Parse(tordelo[4]);
                var kategoriaBesorolas = teljesitmenyCsoportok.IndexOf(teljesitmeny) + 1;
                // Kategória besorolás meghatározása
                // legkisebb telj. lesz az '1'-es kategória.
                // utána a '2'-es, '31-as, stb kategória.
                return new Autok(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    tordelo[2],
                    tordelo[3],
                    teljesitmeny,
                    double.Parse(tordelo[5]),
                    int.Parse(tordelo[6]),
                    tordelo[7],
                    int.Parse(tordelo[8]),
                    int.Parse(tordelo[9]),
                    kategoriaBesorolas
                );
            }
            catch
            {
                return null;
            }
        }
    }
}