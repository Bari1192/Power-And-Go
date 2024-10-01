using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public interface IAuto
    {
        int Id { get; set; }
        string Gyartmany { get; set; }
        string Tipus { get; set; }
        string Rendszam { get; set; }
        int Teljesitmeny { get; set; }
        double Gyorsulas { get; set; }
        int Vegsebesseg { get; set; }
        string Gumimeret { get; set; }
        int Hatotav { get; set; }
        int GyartasiEv { get; set; }
        int KategoriaBesorolas { get; set; }

        int NapiDij(int kategoriaBesorolas, int elofizKat);
        int VezetesiPercdij(int kategoriaBesorolas, int elofizKat);
        int ParkolasiPercdij(int kategoriaBesorolas, int elofizKat);
        void BeallitKategoriabesorolas();
    }
}
