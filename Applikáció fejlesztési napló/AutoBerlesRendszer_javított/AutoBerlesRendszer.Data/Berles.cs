using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public class Berles
    {
        public int Id { get; set; }
        public DateTime BerlesKezd { get; set; }
        public DateTime BerlesVeg { get; set; }
        public int KategoriaId { get; set; }
        public string FelhasznaloNev { get; set; }
        public string Rendszam { get; set; }
    }
}
