using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Felszereltseg(string Rendszam, string Tolatokamera, string Tolatoradar,
        string Multifunkcionalis_Kormany, string Savtarto, string Tempomat)
    {
        public static Felszereltseg? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            try
            {
                return new Felszereltseg(
                    tordelo[0],
                    tordelo[1],
                    tordelo[2],
                    tordelo[3],
                    tordelo[4],
                    tordelo[5]
                    );
            }
            catch { return null; }
        }
    }
}
