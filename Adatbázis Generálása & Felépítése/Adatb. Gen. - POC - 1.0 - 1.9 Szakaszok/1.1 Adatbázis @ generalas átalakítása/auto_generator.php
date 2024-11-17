<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';

use Acme\Autok\Autok;
use Acme\FlottaTipusok\FlottaTipusok;
use Faker\Factory as FakerFactory;

$faker = FakerFactory::create('hu_HU');
$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// FlottaTipusok generálása
$flottaGenerator = new FlottaTipusok();
$flottaSQL = $flottaGenerator->generateSQL();
file_put_contents($outputDir . '/flotta_tipusok.sql', $flottaSQL);

$flottaAdatok = json_decode(file_get_contents(__DIR__ . '/src/Acme/Autok/autok.json'), true);

if ($flottaAdatok === null) {
    die("Hiba: Nem sikerült betölteni a flotta adatokat a JSON fájlból.\n");
}

// Kulcsok kisbetűsítése
$flottaAdatok = array_map(function ($adat) {
    return array_change_key_case($adat, CASE_LOWER);
}, $flottaAdatok);
// Autok generálása
# .yaml fájlból kapja meg az első értékét, a db számát a generáláshoz!
$autokSzama = isset($argv[1]) && is_numeric($argv[1]) ? (int)$argv[1] : 10;

$autokGenerator = new Autok($flottaAdatok);
$autokGenerator->generateAutok($autokSzama);
$autokSQL = $autokGenerator->generateSQL();
file_put_contents($outputDir . '/autok.sql', $autokSQL);

echo "SQL fájlok generálása sikeres a /output mappában.\n";