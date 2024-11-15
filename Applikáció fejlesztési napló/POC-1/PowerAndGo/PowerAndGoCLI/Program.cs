using PowerAndGo;


var adatok = DataStore.Beolvasas("input");

// # 1. Fájl adatsorainak számkiírása
Console.WriteLine($"Autóflotta aktuális száma: {adatok.Autok.Count()} db.");
Console.WriteLine($"Felszereltségek száma: {adatok.Felszereltsegek.Count()}");
Console.WriteLine($"Kategóriák száma: {adatok.Kategoriak.Count()}");
Console.WriteLine($"Személyek száma: {adatok.Szemelyek.Count()}");
Console.WriteLine($"Felhasználók száma: {adatok.Felhasznalok.Count()}");
Console.WriteLine($"Lezárt bérlések száma: {adatok.Lezart_Berlesek.Count()}\n");

//      AUTOFLOTTA LEKÉRDEZÉSEK         \\

// VW -k száma.
Console.WriteLine("VolksWagen - ekre:");
Console.WriteLine($"Összesen {adatok.Autok.Where(x => x.Gyarto == "VW").Count()} darab VW van a flottában.");

// TÍPUSOK felsorolása
Console.WriteLine("\nAutoflotta gyártóinak listázása:");
var autokCsoportositasa = adatok.Autok.GroupBy(x => x.Gyarto);
foreach (var sor in autokCsoportositasa)
{
    Console.WriteLine(sor.Key);
}



// Gyártónként és KW-onként csoportosítva az autók megjelenítése.
Console.WriteLine();
var autokGyartoKwCsoport = adatok.Autok.GroupBy(x => x.Gyarto).OrderBy(x => x.Key);
foreach (var sor in autokGyartoKwCsoport)
{
    Console.WriteLine($"\n{sor.Key} flotta autói:");
    Console.WriteLine("Típus | Teljesítmény | Rendszám \n");
    foreach (var belsoSor in sor.OrderBy(x => x.Teljesitmeny))
    {
        Console.WriteLine($"{belsoSor.Tipus} | {belsoSor.Teljesitmeny} kW | {belsoSor.Rendszam}");
    }
}

// Teljesítmény alapon hány féle autó van (kw felsorolás, növekvő)?
var autokKwcsoportok = adatok.Autok.Select(x => x.Teljesitmeny).Distinct().Order();
foreach (var sor in autokKwcsoportok)
{
    Console.WriteLine(sor);
}
// Felhasznaló szempont alapján szűrés || Hibakezelésekkel / hotkeys megoldásokkal
Console.WriteLine("\nKérem adja meg, az autókra való szűrési szempontot:");
Console.Write("(Típus, Teljesítmény, Hatótáv, Rendszám, Gyártási év, Futásteljesítmény)\n");
string? elsoSzurParam = Console.ReadLine()?.ToLower();

if (elsoSzurParam == "típus" || elsoSzurParam == "tipus" || elsoSzurParam == "tps" || elsoSzurParam == "tip")
{
    elsoSzurParam = "tipus";
    Console.WriteLine("\nAz alábbi típusok közül tud választani:\n");

    var autokTipus = adatok.Autok.GroupBy(x => x.Tipus);
    foreach (var sor in autokTipus)
    {
        Console.WriteLine(sor.Key);
    }

    // Második paraméter bekérése
    string? masodikSzurParam = Console.ReadLine();
    if (adatok.Autok.Any(x => x.Tipus == masodikSzurParam))
    {
        var autokTipusonBelul = adatok.Autok
            .Where(x => x.Tipus == masodikSzurParam)
            .OrderBy(x => x.Teljesitmeny)
            .ToList();

        Console.WriteLine($"\nA(z) {masodikSzurParam} típus alapján az alábbi gépjárművekkel rendelkezik a flottánk (kW teljesítmény szerint növekvő sorrendben):");
        Console.WriteLine("Típus | Gyártó | Aktuális futás (km-ben) | Végsebesség | Teljesítmény\n");

        foreach (var sor in autokTipusonBelul)
        {
            Console.WriteLine($"{sor.Tipus} | {sor.Gyarto} | {sor.Km_ora_allas} km | {sor.Vegsebesseg} km/h | {sor.Teljesitmeny} kW");
        }
    }
    else
    {
        Console.WriteLine("Hibás típusnév, nincs ilyen típus az adatbázisban!");
    }
}
else if (elsoSzurParam == "tljstmny" || elsoSzurParam == "teljesitmeny" || elsoSzurParam == "teljesítmény" || elsoSzurParam == "telj")
{
    var autokTeljesitmenyekAlapjan = adatok.Autok.GroupBy(x => x.Gyarto);
    foreach (var gyartoCsoport in autokTeljesitmenyekAlapjan)
    {
        var kulonbozoTeljesitmenyek = gyartoCsoport
            .Select(x => x.Teljesitmeny)
            .Distinct()
            .OrderBy(y => y)
            .ToList();

        if (kulonbozoTeljesitmenyek.Count > 1)
        {
            Console.WriteLine($"\n{(gyartoCsoport.Key).ToUpper()} gyártónak {kulonbozoTeljesitmenyek.Count} különböző teljesítményű autója van:");
        }
        else
        {
            Console.WriteLine($"\n{(gyartoCsoport.Key).ToUpper()} gyártónak 1 teljesítménytípusú autójával rendelkezünk:");

        }
        Console.WriteLine("Teljesítmények:");

        foreach (var teljesitmeny in kulonbozoTeljesitmenyek)
        {
            Console.WriteLine($"- {teljesitmeny} kW");
        }
    }
}

else if (elsoSzurParam == "hat" || elsoSzurParam == "hatotav" || elsoSzurParam == "httv" || elsoSzurParam == "tav" || elsoSzurParam == "hato")
{
    Console.WriteLine("Kérem adja meg a kívánt hatótávot (km):");
    if (int.TryParse(Console.ReadLine(), out int hatotav))
    {
        // Az első megfelelő autó, amelyik eléri a megadott hatótávot
        var megfeleloAuto = adatok.Autok.FirstOrDefault(x => int.Parse(x.Hatotav) >= hatotav);

        if (megfeleloAuto != null)
        {
            Console.WriteLine($"\nAz alábbi autó megfelel a(z) {hatotav} km-es hatótáv követelménynek:");
            Console.WriteLine($"{megfeleloAuto.Gyarto} | {megfeleloAuto.Tipus} | Hatótáv: {megfeleloAuto.Hatotav} km | Rendszám: {megfeleloAuto.Rendszam}");
        }
        else
        {
            Console.WriteLine("Nincs olyan autó a flottában, amely teljesíti a megadott hatótávot.");
        }
    }
    else
    {
        Console.WriteLine("Érvénytelen hatótáv! Kérjük, adjon meg egy érvényes számot.");
    }
}
else if (elsoSzurParam == "rendszam" || elsoSzurParam == "rndszm" || elsoSzurParam == "rend" || elsoSzurParam == "szám" || elsoSzurParam == "szam")
{
    Console.WriteLine("Kérem adja meg a keresendő betűket vagy számokat a rendszámhoz:");
    string? keresendo = Console.ReadLine()?.ToUpper();

    if (!string.IsNullOrEmpty(keresendo))
    {
        // Rendszámokat tartalmazó autók keresése
        var talalatok = adatok.Autok
            .Where(x => x.Rendszam.Contains(keresendo))
            .ToList();

        if (talalatok.Any())
        {
            Console.WriteLine($"\nAz alábbi autók rendszámában szerepel a(z) '{keresendo}':");
            foreach (var auto in talalatok)
            {
                Console.WriteLine($"{auto.Gyarto} | {auto.Tipus} | Rendszám: {auto.Rendszam}");
            }
        }
        else
        {
            Console.WriteLine("Nincs olyan autó, amely rendszámában szerepel a megadott keresési feltétel.");
        }
    }
    else
    {
        Console.WriteLine("Érvénytelen keresési feltétel! Kérjük, adjon meg betűket vagy számokat.");
    }
}
else if (elsoSzurParam == "futásteljesítmény" || elsoSzurParam == "futás" || elsoSzurParam == "futás" || elsoSzurParam == "futas" || elsoSzurParam == "futelj")
{
    Console.WriteLine("Kérem adja meg a maximális futásteljesítményt (km):");
    if (int.TryParse(Console.ReadLine(), out int maxKm))
    {
        // Az összes autó, amelyik kevesebb mint a megadott km-t futott
        var megfeleloAutok = adatok.Autok
            .Where(x => x.Km_ora_allas <= maxKm)
            .ToList();

        if (megfeleloAutok.Any())
        {
            Console.WriteLine($"\nAz alábbi autók kevesebb mint {maxKm} km-t futottak:");
            foreach (var auto in megfeleloAutok)
            {
                Console.WriteLine($"{auto.Gyarto} \t {auto.Tipus} \t\t Futásteljesítmény: {auto.Km_ora_allas} km \t\t Rendszám: {auto.Rendszam}");
            }
        }
        else
        {
            Console.WriteLine($"Nincs olyan autó, amelynek futásteljesítménye {maxKm} km alatt lenne.");
        }
    }
    else
    {
        Console.WriteLine("Érvénytelen szám! Kérjük, adjon meg egy érvényes futásteljesítményt.");
    }
}
else
{
    Console.WriteLine("Hibás szűrési feltételt adott meg!");
}



//Ötletek:

// Regisztráció létrehozása

