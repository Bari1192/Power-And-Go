using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public interface IFelhasznalo
    {
        string FelhasznaloNev { get; set; }
        int ElofizKat { get; set; }
        string JogosítvanySzama { get; set; }
    }
}