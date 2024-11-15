using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography.X509Certificates;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Kategoria(string Rendszam, string Tipus, int Besorolas)
    {
        public static Kategoria? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            try
            {
                return new Kategoria(
                    tordelo[0],
                    tordelo[1],
                    int.Parse(tordelo[2])
                    );
            }
            catch { return null; }
        }
    }
}
