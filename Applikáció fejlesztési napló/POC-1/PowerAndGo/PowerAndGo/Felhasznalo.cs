using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Felhasznalo(int ID, string Felh_nev, int Jelszo, string Elofizetesi_Kat)
    {
        public static Felhasznalo? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            try
            {
                return new Felhasznalo(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    int.Parse(tordelo[2]),
                    tordelo[3]);
            }
            catch { return null; }
        }
    }
}
