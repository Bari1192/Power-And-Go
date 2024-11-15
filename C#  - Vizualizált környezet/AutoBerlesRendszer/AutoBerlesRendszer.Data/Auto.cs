using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Data
{
    public class Auto : IAuto
    {
        public int Id { get; set; }
        public string Gyartmany { get; set; }
        public string Tipus { get; set; }
        public string Rendszam { get; set; }
        public int Teljesitmeny { get; set; }
        public double Gyorsulas { get; set; }
        public int Vegsebesseg { get; set; }
        public string Gumimeret { get; set; }
        public int Hatotav { get; set; }
        public int GyartasiEv { get; set; }
        public int KategoriaBesorolas { get; set; }

        public void BeallitKategoriabesorolas()
        {
            switch (Teljesitmeny)
            {
                case 18:
                    KategoriaBesorolas = 0;
                    break;
                case 36:
                    KategoriaBesorolas = 1;
                    break;
                case 45:
                    KategoriaBesorolas = 2;
                    break;
                case 50:
                    KategoriaBesorolas = 3;
                    break;
                case 65:
                    KategoriaBesorolas = 4;
                    break;
                default:
                    throw new Exception("hibás adatsor/érték, nem lehet egy kategóriába sem sorolni.");
            }
        }

        public int NapiDij(int kategoriaBesorolas, int elofizKat)
        {
            int[,] dijMatrix = new int[,]
            {
                { 17680, 18680, 23680, 35200, 35200 },
                { 13680, 14830, 22680, 20128, 30200 },
                { 12780, 13780, 20680, 19278, 30200 },
                { 11780, 12780, 19250, 17500, 25200 }
            };

            return dijMatrix[elofizKat, kategoriaBesorolas];
        }

        public int VezetesiPercdij(int kategoriaBesorolas, int elofizKat)
        {
            int[,] dijMatrix = new int[,]
            {
                { 105, 105, 105, -1, -1 },
                { 83, 83, 83, -1, -1 },
                { 58, 58, 58, 100, 100 },
                { 50, 50, 52, 80, 80 }
            };

            int dij = dijMatrix[elofizKat, kategoriaBesorolas];

            if (dij == -1)
            {
                throw new Exception("Ez az autó típus nem elérhető az adott előfizetési kategória számára.");
            }

            return dij;
        }

        public int ParkolasiPercdij(int kategoriaBesorolas, int elofizKat)
        {
            int[,] dijMatrix = new int[,]
            {
                { 85, 85, 85, -1, -1 },
                { 59, 59, 59, -1, -1 },
                { 58, 58, 58, 80, 80 },
                { 40, 40, 45, 65, 65 }
            };

            int dij = dijMatrix[elofizKat, kategoriaBesorolas];

            if (dij == -1)
            {
                throw new Exception("Ez az autó típus nem elérhető az adott előfizetési kategória számára.");
            }

            return dij;
        }
    }
}