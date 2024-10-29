using Microsoft.EntityFrameworkCore;
using PowerAndGoApp.DataBase;
using PowerAndGoApp.DataStore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.Services
{
    public class DataImportService
    {
        private readonly AppDBContext dbContext;

        public DataImportService(AppDBContext dbContext)
        {
            this.dbContext = dbContext;
        }

        public async Task ImportDataAsync()
        {
            await ImportAutokAsync();
            //await ImportKategoriakAsync();
            //await ImportFelszereltsegekAsync();
            //await ImportSzemelyekAsync();
            //await ImportFelhasznalokAsync();
            //await ImportLezartBerlesekAsync();
        }
        // Autok táblának az import metódusa
        private async Task ImportAutokAsync()
        {
            if (await dbContext.Autok.AnyAsync())
                return;

            var assembly = Assembly.GetExecutingAssembly();
            using (var stream = assembly.GetManifestResourceStream("PowerAndGoApp.DataBase.Resources.Auto.csv"))
            using (var reader = new StreamReader(stream))
            {
                var fejlec = reader.ReadLine();// Fejléc SKIP
                while (!reader.EndOfStream)
                {
                    var elsosor = reader.ReadLine();
                    var sorok = elsosor.Split(';');

                    var auto = new Auto
                    {
                        Gyarto = sorok[0],
                        Tipus = sorok[1],
                        Teljesitmeny = int.Parse(sorok[2]),
                        Vegsebesseg = int.Parse(sorok[3]),
                        Gumimeret = sorok[4],
                        Hatotav = int.Parse(sorok[5]),
                        Rendszam = sorok[6],
                        Gyartasi_ev = int.Parse(sorok[7]),
                        Km_ora_allas = int.Parse(sorok[8])
                    };
                    dbContext.Autok.Add(auto);
                }
            }
            await dbContext.SaveChangesAsync();
        }
        // Implementáld a többi importáló metódust hasonló módon
    }




}
