using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public class Felhasznalo : IFelhasznalo
    {
        public string FelhasznaloNev { get; set; }
        public int ElofizKat { get; set; }
        public string JogosítvanySzama { get; set; }
    }
}