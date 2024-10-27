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
Console.WriteLine("\n Kérem adja meg, az autókra való szűrési szempontot:");
Console.Write("(Típus, Márka, Teljesítmény, hatótáv, Rendszám, Gyártási év, Futásteljesítmény");
string? felhaszSzurese = Console.ReadLine()?.ToLower();
if (felhaszSzurese == "típus" || felhaszSzurese == "tipus" || felhaszSzurese == "tps" || felhaszSzurese == "tip")
{

}
else if (felhaszSzurese == "marka" || felhaszSzurese == "márka" || felhaszSzurese == "mrk" || felhaszSzurese == "mar")
{

}
else if (felhaszSzurese == "tljstmny" || felhaszSzurese == "teljesitmeny" || felhaszSzurese == "teljesítmény" || felhaszSzurese == "telj")
{

}
else if (felhaszSzurese == "hat" || felhaszSzurese == "hatotav" || felhaszSzurese == "httv" || felhaszSzurese == "tav" || felhaszSzurese == "hato")
{

}
else if (felhaszSzurese == "rendszam" || felhaszSzurese == "rndszm" || felhaszSzurese == "rend" || felhaszSzurese == "szám" || felhaszSzurese == "szam")
{

}
else if (felhaszSzurese == "gyartas" || felhaszSzurese == "ev" || felhaszSzurese == "gyartev" || felhaszSzurese == "gyrtv" || felhaszSzurese == "gyartasi ev" || felhaszSzurese == "gyártási év" || felhaszSzurese == "gyartasiev" || felhaszSzurese == "gyártásiév")
{

}
else if (felhaszSzurese == "futásteljesítmény" || felhaszSzurese == "futás" || felhaszSzurese == "futás" || felhaszSzurese == "futas" || felhaszSzurese == "futelj")
{

}

//Ötletek:

// Regisztráció létrehozása

