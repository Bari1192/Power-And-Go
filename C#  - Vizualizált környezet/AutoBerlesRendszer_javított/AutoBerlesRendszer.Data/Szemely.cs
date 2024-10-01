using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public class Szemely
    {
        public int Id { get; set; }
        public string SzigSzam { get; set; }
        public string JogosítvanySzam { get; set; }
        public DateTime JogosítvanyErvenyesseg { get; set; }
        public DateTime JogosítvanyLejarat { get; set; }
        public string VezetekNev { get; set; }
        public string KeresztNev { get; set; }
        public string FelhasznaloNev { get; set; }
        public string Jelszo { get; set; }
        public DateTime SzuletesiDatum { get; set; }
        public string Telefon { get; set; }
        public string Email { get; set; }
    }
}
