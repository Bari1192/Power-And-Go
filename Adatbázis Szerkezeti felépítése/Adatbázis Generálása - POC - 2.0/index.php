<?php

declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . "/vendor/autoload.php";

echo ("##############################################################");
echo ("\nProgram telepítési környezetének létrehozása megtörtént!\n");
echo ("##############################################################");


echo ("\nKérem válassza ki és írja be, melyik adattípusból szeretne generáltatni!\n");
echo ("##############################################################");
echo ("\n# [Autok] # [Szemelyek] #");
echo ("##############################################################");

use Acme\Autok\Auto;
use Faker\Factory as FakerFactory;
$fajl_utvonal = './out/' . $argv[1];

# 1. [Tábla-Tipusa]
## 2. [Generálási sormennyiség]
### 3. [Fájl megnevezése & kiterjesztése]
$faker = FakerFactory::create('hu_HU');

$tabla=$argv[1];
$sorok_szama=$argv[2];
$fajl_neve_kiterjesztese=$argv[3];


// Például magyar rendszámok generálása:
// építs bele random generátort [0 - régi] [1 - új fajta]
// 2022 júliusától
$rendszam_uj_regi = random_int(0, 1);
if ($rendszam_uj_regi > 0) {
    $rendszam = strtoupper($faker->regexify('AA[A-C][A-O]-[0-9]{3}'));
} else {
    $rendszam = strtoupper($faker->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
}
// # AUTOK # || GENNERÁLÁS MAGJA \\


// private string $gyarto;
// private string $tipus;
// private string $rendszam;
// private int $teljesitmeny;
// private int $gyorsulas;
// private int $vegsebesseg;
// private string $gumimeret;
// private int $hatotav;
// private int $gyartasi_ev;
// * @method string password($minLength = 6, $maxLength = 20)

// for ($i = 0; $i < $sorok_szama; $i++) {
//     $diakok[] = new Auto(
//         $faker->,
//         $faker->,
//         $faker->,
//         $faker->dateTimeBetween('-30 years', '-10 years')
//     );
// }

// Személyigazolvány szám generálása:
$szemelyi_szam = $faker->regexify('[A-Z]{2}[0-9]{6}');
echo $szemelyi_szam; // Példa: 123456AB



if (str_ends_with($fajl_neve_kiterjesztese, '.txt')) {
    foreach ($diakok as $diak_adat) {
        $sor = $diak_adat->getTeljesNev() . "\n" . $diak_adat->getEmail() . "\n" . $diak_adat->getSzuletettISO() . "\n";
        // Itt a fajl_utvonal változót használjuk az íráshoz
        file_put_contents($fajl_utvonal, $sor, FILE_APPEND);
    }
}