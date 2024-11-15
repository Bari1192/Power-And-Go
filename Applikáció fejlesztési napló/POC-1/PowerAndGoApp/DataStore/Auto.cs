using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.DataStore
{
    public class Auto
    {
        // [PK]
        public string? Rendszam { get; set; }

        public string Gyarto { get; set; }
        public string? Tipus { get; set; }
        public int Teljesitmeny { get; set; }
        public int Vegsebesseg { get; set; }
        public string? Gumimeret { get; set; }
        public int Hatotav { get; set; }
        public int Gyartasi_ev { get; set; }
        public int Km_ora_allas { get; set; }

        // # Navigációk - összekapcsolások
        public Kategoria Kategoria { get; set; }
        public Felszereltseg Felszereltseg { get; set; }
        public ICollection<Lezart_berles> LezartBerlesek { get; set; }
    }
}
