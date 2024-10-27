using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Szemely(int ID, string V_nev, string K_nev, DateOnly Szul_datum, string Telefon, string Email, string Szig_szam,
        string Jogos_szam, DateOnly Jogos_erv_kezdete, DateOnly jogos_erv_vege, int Felh_jelszo)
    {
        public static Szemely? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            try
            {
                return new Szemely(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    tordelo[2],
                    DateOnly.Parse(tordelo[3]),
                    tordelo[4],
                    tordelo[5],
                    tordelo[6],
                    tordelo[7],
                    DateOnly.Parse(tordelo[8]),
                    DateOnly.Parse(tordelo[9]),
                    int.Parse(tordelo[10]));
            }
            catch { return null; }
        }
    }
}
