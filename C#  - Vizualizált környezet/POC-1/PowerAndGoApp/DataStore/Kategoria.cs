using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.DataStore
{
    public class Kategoria
    {
        public string? Rendszam { get; set; }

        public string? Tipus { get; set; }
        public int Besorolas { get; set; }

        // # Navigációk - összekapcsolások
        public Auto Auto { get; set; }
    }
}
