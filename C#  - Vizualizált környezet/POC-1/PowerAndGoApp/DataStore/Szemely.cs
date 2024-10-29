using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.DataStore
{
    public class Szemely
    {
        public int ID { get; set; }

        public string? Szig_szam { get; set; }
        public string? V_nev { get; set; }
        public string? K_nev { get; set; }
        public DateTime Szul_datum { get; set; }
        public string? Tel { get; set; }
        public string? Email { get; set; }
        public string? Jogos_szam { get; set; }
        public DateOnly Jogos_erv_kezdete { get; set; }
        public DateOnly jogos_erv_vege { get; set; }
        public int Felh_jelszo { get; set; }

        // # Navigációk - összekapcsolások
        public Felhasznalo Felhasznalo { get; set; }
    }
}
