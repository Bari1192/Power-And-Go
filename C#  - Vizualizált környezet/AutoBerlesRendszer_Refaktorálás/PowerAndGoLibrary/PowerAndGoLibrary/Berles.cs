using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record Berles(int Id, DateTime BerlesKezd, DateTime BerlesVeg, int KategoriaId, string FelhasznaloNev, string Rendszam)
    {
        public static Berles? Beolvasas(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new Berles(
                    int.Parse(tordelo[0]),
                    DateTime.Parse(tordelo[1]),
                    DateTime.Parse(tordelo[2]),
                    int.Parse(tordelo[3]),
                    tordelo[4],
                    tordelo[5]
                    );
            }
            catch { return null; }
        }



    }
}
