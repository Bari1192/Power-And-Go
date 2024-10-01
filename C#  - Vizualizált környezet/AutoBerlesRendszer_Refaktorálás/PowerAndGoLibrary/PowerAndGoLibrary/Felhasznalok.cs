using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record Felhasznalok(string FelhasznaloNev, int ElofizKat, string JogosítvanySzama)
    {
        public static Felhasznalok? Beolvasas(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new Felhasznalok(
                    tordelo[0],
                    int.Parse(tordelo[1]),
                    tordelo[1]
                    );
            }
            catch { return null; }
        }
    }
}
