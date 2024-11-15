using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.DataStore
{
    public class Felszereltseg
    {
        public string? Rendszam { get; set; }

        public string? Tolatokamera { get; set; }
        public string? Tolatoradar { get; set; }
        public string? Multifunkcionalis_Kormany { get; set; }
        public string? Savtarto { get; set; }
        public string? Tempomat { get; set; }

        public Auto Auto { get; set; }
    }
}
