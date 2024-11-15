using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public class Kategoria
    {
        public int Id { get; set; }
        public int KatBesorolas { get; set; }
        public string KatModellNev { get; set; }
        public int AutokIdFK { get; set; }
    }
}