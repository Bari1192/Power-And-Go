using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Auto(string Gyarto, string Tipus, int Teljesitmeny,
     int Vegsebesseg, string Gumimeret, string Hatotav, string Rendszam, int Gyartasi_ev, int Km_ora_allas)
    {
        public static Auto? Beolvasas(string sor)
        {
            var tordelt = sor.Split(';');
            try
            {
                return new Auto(
                    tordelt[0],
                    tordelt[1],
                    int.Parse(tordelt[2]),
                    int.Parse(tordelt[3]),
                    tordelt[4],
                    tordelt[5],
                    tordelt[6],
                    int.Parse(tordelt[7]),
                    int.Parse(tordelt[8]));
            }
            catch { return null; }

        }
    }

}

