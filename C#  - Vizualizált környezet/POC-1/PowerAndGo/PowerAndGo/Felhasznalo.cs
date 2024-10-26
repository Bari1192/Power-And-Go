using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Felhasznalo(int ID, string Felh_nev, int Jelszo, int Elofizetesi_Kat)
    {
        public static Felhasznalo? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            tordelo.Skip(1);
            try
            {
                return new Felhasznalo(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    int.Parse(tordelo[2]),
                    int.Parse(tordelo[3]));
            }
            catch { return null; }
        }
    }
}
