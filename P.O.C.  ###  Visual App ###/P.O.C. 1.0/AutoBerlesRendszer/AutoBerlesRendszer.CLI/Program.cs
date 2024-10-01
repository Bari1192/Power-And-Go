using AutoBerlesRendszer.Logic;
using AutoBerlesRendszer.Repository;
using AutoBerlesRendszer.Data;

namespace AutoBerlesRendszer.CLI
{
    class Program
    {
        static void Main(string[] args)
        {
            AutoTarolo autoTarolo = new AutoTarolo();
            KategoriaTarolo kategoriaTarolo = new KategoriaTarolo();
            FelhasznaloTarolo felhasznaloTarolo = new FelhasznaloTarolo();
            SzemelyTarolo szemelyTarolo = new SzemelyTarolo();
            BerlesTarolo berlesTarolo = new BerlesTarolo();

            // Beolvasások a fájlokból:
            autoTarolo.BeolvasAutoFajlbol("Autok.txt");
            berlesTarolo.BeolvasBerlesFajlbol("Berles.txt");
            felhasznaloTarolo.BeolvasFelhasznaloFajlbol("Felhasznalok.txt");
            kategoriaTarolo.BeolvasKategoriaFajlbol("Kategoriak.txt");
            szemelyTarolo.BeolvasSzemelyFajlbol("Szemely.txt");


            AutoBerlesLogika logika = new AutoBerlesLogika(
                autoTarolo,
                kategoriaTarolo,
                felhasznaloTarolo,
                szemelyTarolo,
                berlesTarolo);
            Console.WriteLine("Autóbérlési rendszer CLI");

            while (true)
            {
                Console.WriteLine("Kérem válasszon az alábbi menüpontok közül:");
                Console.WriteLine("1. Autók listázása kategóriabesorolásonként");
                Console.WriteLine("2. Felhasználók listázása csökkenő sorrendben vezetési percek alapján");
                Console.WriteLine("3. Felhasználók listázása csökkenő sorrendben vezetési percek alapján előfizetésenként a TOP 10");
                Console.WriteLine("4. Felhasználó regisztráció");
                Console.WriteLine("5. Kilépés");

                var menu = Console.ReadLine();

                switch (menu)
                {
                    case "1":
                        logika.ListazAutoKategoriaSzerint();
                        break;
                    case "2":
                        logika.ListazFelhasznalokVezetesiPercekSzerint();
                        break;
                    case "3":
                        logika.ListazFelhasznalokVezetesiPercekSzerintElofizetesenkent();
                        break;
                    case "4":
                        Regisztracio(logika);
                        break;
                    case "5":
                        return;
                    default:
                        Console.WriteLine("Érvénytelen választás, próbálja újra.");
                        break;
                }
                static void Regisztracio(AutoBerlesLogika logika)
                {
                    Console.Write("Vezetéknév: ");
                    string vezeteknev = Console.ReadLine();
                    Console.Write("Keresztnév: ");
                    string keresztnev = Console.ReadLine();
                    Console.Write("Születési dátum (yyyy-MM-dd): ");
                    DateTime szulDatum = DateTime.Parse(Console.ReadLine());
                    Console.Write("Jogosítvány száma: ");
                    string jogositvanySzam = Console.ReadLine();
                    Console.Write("Jogosítvány kiállításának időpontja (yyyy-MM-dd): ");
                    DateTime kiallitasDatum = DateTime.Parse(Console.ReadLine());
                    Console.Write("Jogosítvány lejárati dátuma (yyyy-MM-dd): ");
                    DateTime lejaratDatum = DateTime.Parse(Console.ReadLine());
                    Console.Write("Telefonszám: ");
                    string telefonszam = Console.ReadLine();
                    Console.Write("Email: ");
                    string email = Console.ReadLine();
                    Console.Write("Felhasználónév: ");
                    string felhasznalonev = Console.ReadLine();
                    Console.Write("Jelszó: ");
                    string jelszo = Console.ReadLine();
                    Console.Write("Előfizetési csomag (0-1-2-3): ");
                    int elofizetes = int.Parse(Console.ReadLine());

                    try
                    {
                        logika.RegisztralFelhasznalo(vezeteknev, keresztnev, szulDatum, jogositvanySzam, kiallitasDatum, lejaratDatum, telefonszam, email, felhasznalonev, jelszo, elofizetes);
                        Console.WriteLine("Sikeres regisztráció!");
                    }
                    catch (Exception ex)
                    {
                        Console.WriteLine($"Hiba: {ex.Message}");
                    }
                }

                Console.WriteLine();
                //Console.WriteLine("\nAutók:");
                //foreach (var auto in autoTarolo.Osszes())
                //{
                //    Console.WriteLine($"{auto.Gyartmany} {auto.Tipus}, Rendszám: {auto.Rendszam}, Teljesítmény: {auto.Teljesitmeny} LE, Kategória: {auto.KategoriaBesorolas}");
                //}

                //Console.WriteLine("\nKategóriák:");
                //foreach (var kategoria in kategoriaTarolo.Osszes())
                //{
                //    Console.WriteLine($"{kategoria.Id}, Besorolás: {kategoria.KatBesorolas}, Modell: {kategoria.KatModellNev}, Autó ID: {kategoria.AutokIdFK}");
                //}

                //Console.WriteLine("\nFelhasználók:");
                //foreach (var felhasznalo in felhasznaloTarolo.Osszes())
                //{
                //    Console.WriteLine($"{felhasznalo.FelhasznaloNev}, Előfiz. kat.: {felhasznalo.ElofizKat}, Jog. szám: {felhasznalo.JogosítvanySzama}");
                //}

                //Console.WriteLine("\nSzemélyek:");
                //foreach (var szemely in szemelyTarolo.Osszes())
                //{
                //    Console.WriteLine($"{szemely.VezetekNev} {szemely.KeresztNev}, felh. név: {szemely.FelhasznaloNev}, Szül.dátum: {szemely.SzuletesiDatum.ToShortDateString()}, Telefon: {szemely.Telefon}, Email: {szemely.Email}");
                //}

                //Console.WriteLine("\nBérlések:");
                //foreach (var berles in berlesTarolo.Osszes())
                //{
                //    Console.WriteLine($"ID: {berles.Id}, Kezdete: {berles.BerlesKezd}, Vége: {berles.BerlesVeg}, Kategória ID: {berles.KategoriaId}, Felhasználó: {berles.FelhasznaloNev}, Rendszám: {berles.Rendszam}");
                //}


                //Console.ReadLine();
            }
        }
    }
}