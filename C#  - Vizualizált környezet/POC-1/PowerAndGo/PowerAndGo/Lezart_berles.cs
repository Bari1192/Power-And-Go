using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGo
{
    public record Lezart_berles(int Berles_id, string Rendszam, int Kat_besorolas, DateOnly Berles_kezd_ev_ho_nap, TimeOnly Berles_kezd_ora_perc_mp,
        DateOnly Berles_vege_ev_ho_nap, TimeOnly Berles_vege_ora_perc_mp, string Felh_nev)
    {
        public static Lezart_berles? Beolvasas(string sor)
        {
            var tordelo = sor.Split(';');
            try
            {
                tordelo.Skip(1);
                return new Lezart_berles(
                    int.Parse(tordelo[0]),
                    tordelo[1],
                    int.Parse(tordelo[2]),
                    DateOnly.Parse(tordelo[4]),
                    TimeOnly.Parse(tordelo[5]),
                    DateOnly.Parse(tordelo[6]),
                    TimeOnly.Parse(tordelo[7]),
                    tordelo[8]
                    );
            }
            catch { return null; }
        }
    }
}
