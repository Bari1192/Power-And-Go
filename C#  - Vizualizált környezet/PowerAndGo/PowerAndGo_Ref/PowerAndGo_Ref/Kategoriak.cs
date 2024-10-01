using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record Kategoriak(int Id, string KatModellNev)
    {
        public static Kategoriak? Beolvasas(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new Kategoriak(
                    int.Parse(tordelo[0]),
                    tordelo[2]
                    );
            }
            catch { return null; }
        }


    }
}
