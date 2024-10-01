using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record Szemely(int Id, string SzigSzam, string JogosítvanySzam, DateTime JogosítvanyErvenyesseg, DateTime JogosítvanyLejarat, string VezetekNev, string KeresztNev, string FelhasznaloNev, string Jelszo, DateTime SzuletesiDatum, string Telefon, string Email)
    {
        public static Szemely? Beolvasas(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new Szemely(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    tordelo[2],
                    DateTime.Parse(tordelo[3]),
                    DateTime.Parse(tordelo[4]),
                    tordelo[5],
                    tordelo[7],
                    tordelo[6],
                    tordelo[8],
                    DateTime.Parse(tordelo[9]),
                    tordelo[10],
                    tordelo[11]
                    );
            }
            catch
            {
                return null;
            }

        }
    }
}
