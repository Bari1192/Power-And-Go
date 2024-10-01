using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoLibrary
{
    public record Autok(int Id, string Gyartmany, string Tipus, string Rendszam, int Teljesitmeny, double Gyorsulas, int Vegsebesseg, string Gumimeret, int Hatotav, int GyartasiEv, int KategoriaBesorolas)
    {
        public static Autok? Beolvasas(string sor)
        {
            var tordelo = sor.Trim().Split(',');
            try
            {
                return new Autok(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    tordelo[2],
                    tordelo[3],
                    int.Parse(tordelo[4]),
                    double.Parse(tordelo[5]),
                    int.Parse(tordelo[6]),
                    tordelo[7],
                    int.Parse(tordelo[8]),
                    int.Parse(tordelo[9]),
                    int.Parse(tordelo[10])
                    );
            }
            catch { return null; }
        }
    }
}








