<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';

use Faker\Factory as FakerFactory;

$faker = FakerFactory::create('hu_HU');

$darab = $argv[1];
$fajl_neve_kiterjesztese = $argv[2];



// 1. Személy generálás || Darabszám megadása
// 2. Személy Generálás Tömmbe rakás!
// 3. Tömbbe rakás után JSON-be rakás!

# +1 Személyigazolvány "gyártása" függvény:
# +1.1 Személyigazolvány számok ne ismétlődhessenek soha!

# +2 Jogosítványszámok "gyártása" függvény:
# +2.1 Jogosítványszámok számok ne ismétlődhessenek soha & Ne is egyezzenek egyetlen személyigazolvány számmal sem!
# +2.2 Mivel a jogosítványszámok formátumban egyeznek a Személyivel, mehet ugyanaz a formátum + ellenőrzés is megvan!
$legeneraltSzemelyik = [];
function szemelyIgazolvanyok(array &$legeneraltSzemelyik, $faker): string
{
    do {
        $betuk = strtoupper($faker->bothify('??')); #Kettő BETÜ kell nekünk.
        $szamok = str_pad((string) $faker->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        $elkeszult = $betuk . $szamok;
    }
    #   Visszaellenőrzés    #
    while (in_array($elkeszult, $legeneraltSzemelyik));
    $legeneraltSzemelyik[] = $elkeszult;
    return $elkeszult;
}

$szemelyekTomb = [];
for ($i = 0; $i < $darab; $i++) {

    $V_nev = $faker->lastName();
    $K_nev = $faker->firstName();
    $szul_datum = $faker->dateTimeBetween($startDate = 'now - 64 years', $endDate = 'now -18 years', $timezone = 'Europe/Budapest');
    $telefon = '+36' . $faker->numerify('## ### ####');
    $telefon = $faker->regexify('\+36(20|30|70)[0-9]{3}[0-9]{4}');
    $email = $faker->safeEmail();
    $Szig_szam = szemelyigazolvanyok($legeneraltSzemelyik, $faker);
    $jogositvany_szama = szemelyigazolvanyok($legeneraltSzemelyik, $faker); # NEM HIBÁS!
    $jogositvany_ervenyesseg = $faker->dateTimeBetween($startDate = 'now - 10 years', $endDate = 'now -1 days', $timezone = 'Europe/Budapest');
    $jogositvany_lejarata = (clone $jogositvany_ervenyesseg)->modify('+10 years');
    $felh_jelszo = $faker->numberBetween(1000, 9999);
    $felh_jelszo_megegyszer = $felh_jelszo;

    $szemelyekTomb[] = [
        'Név' => $V_nev.' '.$K_nev,
        'Születési Dátum' => $szul_datum->format('Y-m-d'),
        'Tel.:' => $telefon,
        'E-mail' => $email,
        'Szig. Szám' => $Szig_szam,
        'jogos. Száma' => $jogositvany_szama,
        'jogos. Érv. Kezdete.' => $jogositvany_ervenyesseg->format('Y-m-d'),
        'jogos. Érv. Vége.' => $jogositvany_lejarata->format('Y-m-d'),
        'felhasználó Jelszava' => $felh_jelszo,
    ];
}
if (isset($argv[2])) {
    if (str_ends_with($fajl_neve_kiterjesztese, '.csv')) {
        $fajl = fopen($fajl_neve_kiterjesztese, 'w');
        foreach ($szemelyekTomb as $sor) {
            $csv_sor = implode(';', $sor);  
            fwrite($fajl, $csv_sor . PHP_EOL);
        }
        fclose($fajl);
    } elseif (str_ends_with($fajl_neve_kiterjesztese, '.json')) {
        $SzemelyekJSON = json_encode($szemelyekTomb, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($fajl_neve_kiterjesztese, $SzemelyekJSON);
    }
}
