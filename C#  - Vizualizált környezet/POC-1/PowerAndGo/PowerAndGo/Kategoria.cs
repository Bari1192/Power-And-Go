using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography.X509Certificates;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Kategoria(int Rendszam, string Tipus, int Besorolas)
    {
        public static Kategoria? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            tordelo.Skip(1);
            try
            {
                return new Kategoria(
                    int.Parse(tordelo[0]),
                    tordelo[0],
                    int.Parse(tordelo[2])
                    );
            }
            catch { return null; }
        }
    }
}
