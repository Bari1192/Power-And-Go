<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';

use Acme\Autok\Auto;
use Acme\Kategoriak\Kategoria;
use Acme\Felszereltsegek\Felszereltseg;
use Faker\Factory as FakerFactory;

$faker = FakerFactory::create('hu_HU');
$autok = [];
$kategoriak = [];
$felszereltseg = [];
$darab = (int) $argv[1];
$kiterjesztes = (string) $argv[2];

$output_dir = __DIR__ . '/src/output';
if (!is_dir($output_dir)) {
    mkdir($output_dir, 0777, true);
}

$json_fajl_utvonal = __DIR__ . '/src/Acme/Autok/autok.json';
$auto_adatok = json_decode(file_get_contents($json_fajl_utvonal), true);

# Rendszám ismétlődés elkerülésére
$rendszamok = [];
for ($i = 0; $i < $darab; $i++) {
    # Véletlenszerűen egy autó kiválasztása JSON adatokból
    $auto_kivalasztas = array_rand($auto_adatok);
    $auto_adat = $auto_adatok[$auto_kivalasztas];
    # Folyamatos ellenőrzés, hogy ne legyen ismételt rendszám!
    do {
        $rendszam_uj_regi = random_int(0, 1);
        if ($rendszam_uj_regi > 0) {
            $rendszam = strtoupper($faker->regexify('AA[A-C][A-O]-[0-9]{3}'));
        } else {
            $rendszam = strtoupper($faker->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
        }
    } while (in_array($rendszam, $rendszamok));

    $rendszamok[] = $rendszam;
    $gyartasi_ev = random_int(2019, 2023);

    # Itt jön létre EGY ÚJ autó objektum!
    # Amit a JSON-ből ki tud nyerni a random kiválasztás alapján.
    $auto = new Auto(
        $auto_adat['gyarto'],
        $auto_adat['tipus'],
        $auto_adat['teljesitmeny'],
        $auto_adat['vegsebesseg'],
        $auto_adat['gumimeret'],
        $auto_adat['hatotav'],
        $rendszam,
        $gyartasi_ev,

    );
    $kategoria = new Kategoria(
        # párhuzamosan ugyanaz, mint amit az adott autó sorába is generálunk.
        $rendszam,
        $auto_adat['tipus'],
        $auto_adat['teljesitmeny']
    );
    $felszereltseg = new Felszereltseg(
        $rendszam,
        $auto_adat['tipus'],
        $kategoria->getBesorolas(),
        $auto_adat['gyarto']
    );
    $autok[] = [
        'Gyarto' => $auto->getGyarto(),
        'Tipus' => $auto->getTipus(),
        'Teljesitmeny' => $auto->getTeljesitmeny(),
        'Vegsebesseg' => $auto->getVegsebesseg(),
        'Gumimeret' => $auto->getGumimeret(),
        'Hatótav' => $auto->getHatotav(),
        'Rendszam' => $auto->getRendszam(),
        'Gyartasi_ev' => $auto->getGyartasiEv(),
        'Km_ora_allas' => $auto->getKm(),
    ];
    $kategoriak[] = [
        'Rendszam' => $kategoria->getRendszam(),
        'Tipus' => $kategoria->getTipus(),
        'Besorolas' => $kategoria->getBesorolas()
    ];
    $felszereltsegek[] = array_merge(['Rendszam' => $rendszam], $felszereltseg->getFelszerelesek());
}
if ($kiterjesztes === 'json') {
    ### || Autok fájl ||
    $autok_json_fajl_utvonal = $output_dir . '/autok.json';
    file_put_contents($autok_json_fajl_utvonal, json_encode($autok, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    ### || Kategóriák fájl ||
    $kategoriak_json_fajl_utvonal = $output_dir . '/kategoriak.json';
    file_put_contents($kategoriak_json_fajl_utvonal, json_encode($kategoriak, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    ### || Felszereltségek JSON mentés ||
    file_put_contents($output_dir . '/felszereltsegek.json', json_encode($felszereltsegek, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

} elseif ($kiterjesztes === 'csv') {
    ### || Autok fájl ||
    $autok_csv_fajl_utvonal = $output_dir . '/autok.csv';
    $fajl = fopen($autok_csv_fajl_utvonal, 'w');

    fputcsv($fajl, array_keys($autok[0]), ';');  // Fejléc generálása

    foreach ($autok as $auto_sor) {
        fputcsv($fajl, $auto_sor, ';');
    }
    fclose($fajl);

    ### || Kategóriák fájl ||
    $kategoriak_csv_fajl_utvonal = $output_dir . '/kategoriak.csv';
    $fajl = fopen($kategoriak_csv_fajl_utvonal, 'w');

    fputcsv($fajl, array_keys($kategoriak[0]), ';');  // Fejléc generálása

    foreach ($kategoriak as $kategoria_sor) {
        fputcsv($fajl, $kategoria_sor, ';');
    }
    fclose($fajl);

    ### || Felszereltségek CSV mentés ||
    $fajl = fopen($output_dir . '/felszereltsegek.csv', 'w');
    fputcsv($fajl, array_keys($felszereltsegek[0]), ';');  // Fejléc generálása

    foreach ($felszereltsegek as $felszereltseg_sor) {
        fputcsv($fajl, $felszereltseg_sor, ';');
    }
    fclose($fajl);
}
