using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record Kategoriak(int Id, int KatBesorolas, string KatModellNev, int AutokIdFK)
    {
        public static Kategoriak? Beolvasas(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new Kategoriak(
                    int.Parse(tordelo[0]),
                    int.Parse(tordelo[1]),
                    tordelo[2],
                    int.Parse(tordelo[3])
                    );
            }
            catch { return null; }
        }


    }
}
