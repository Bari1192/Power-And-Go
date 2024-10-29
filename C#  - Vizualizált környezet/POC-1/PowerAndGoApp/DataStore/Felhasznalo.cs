using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
namespace PowerAndGoApp.DataStore
{
    public class Felhasznalo
    {
        public int ID { get; set; }

        public string? Felh_nev { get; set; }
        public int JelszoSecret { get; set; }
        public string? Elofizetesi_kat { get; set; }

        // # Navigációk - összekapcsolások
        public Szemely Szemely { get; set; }
        public ICollection<Lezart_berles> LezartBerlesek { get; set; }
    }
}
