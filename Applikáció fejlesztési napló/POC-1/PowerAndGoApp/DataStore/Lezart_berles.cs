using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.DataStore
{
    public class Lezart_berles
    {
        public int Berles_id { get; set; }

        public string? Rendszam { get; set; }
        public int Kat_besorolas { get; set; }
        public DateOnly Berles_kezd_ev_ho_nap { get; set; }
        public TimeOnly Berles_kezd_ora_perc_mp { get; set; }
        public DateOnly Berles_vege_ev_ho_nap { get; set; }
        public TimeOnly Berles_vege_ora_perc_mp { get; set; }
        public string? Felh_nev { get; set; }

        // # Navigációk - összekapcsolások
        public Auto Auto { get; set; }
        public Felhasznalo Felhasznalo { get; set; }
    }
}
