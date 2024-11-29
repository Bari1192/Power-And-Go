<?php

declare(strict_types=1);

use Acme\LezartBerlesek\LezartBerles;
use Acme\Felhasznalok\Felhasznalo;
use Faker\Factory as FakerFactory;
require_once 'vendor/autoload.php';

$faker = FakerFactory::create('hu_HU');
$berlesek = [];
$rendszamokBerlesei = []; 
$darab = (int) $argv[1];
$kiterjesztes = (string) $argv[2];

$output_dir = __DIR__ . '/src/output';
if (!is_dir($output_dir)) {
    mkdir($output_dir, 0777, true);
}
$autok = loadData($output_dir . '/autok.json', $output_dir . '/autok.csv');
$kategoriak = loadData($output_dir . '/kategoriak.json', $output_dir . '/kategoriak.csv');
$felhasznalok = loadData($output_dir . '/felhasznalok.json', $output_dir . '/felhasznalok.csv');
$szemelyek = loadData($output_dir . '/szemelyek.json', $output_dir . '/szemelyek.csv');

$kategoriakByRendszam = [];
foreach ($kategoriak as $kategoria) {
    $kategoriakByRendszam[$kategoria['Rendszam']] = $kategoria['Besorolas'];
}

function loadData(string $jsonPath, string $csvPath): array
{
    if (file_exists($jsonPath)) {
        return json_decode(file_get_contents($jsonPath), true);
    } elseif (file_exists($csvPath)) {
        $data = [];
        if (($handle = fopen($csvPath, 'r')) !== false) {
            $headers = fgetcsv($handle, 1000, ';');
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $data[] = array_combine($headers, $row);
            }
            fclose($handle);
        }
        return $data;
    } else {
        throw new Exception("Fájl nem található: $jsonPath vagy $csvPath");
    }
}
for ($i = 0; $i < $darab; $i++) {
    $berlesAzonosito = $i + 1;
    $auto = $autok[array_rand($autok)];
    $rendszam = $auto['Rendszam'];
    if (isset($kategoriakByRendszam[$rendszam])) {
        $besorolas = $kategoriakByRendszam[$rendszam];
    } else {
        echo "Hiba: Besorolás nem található a rendszámhoz: $rendszam\n";
        continue;
    }
    $felhasznalo = $felhasznalok[array_rand($felhasznalok)];
    $felhasznalo_id = $felhasznalo['ID'];

    $szemely = array_filter($szemelyek, function ($szemely) use ($felhasznalo_id) {
        return $szemely['ID'] == $felhasznalo_id;
    });

    if (empty($szemely)) {
        throw new Exception("Előszőr generálja le a személyeket! [szemely_generator.php]");
    }
    $felhasznaloNev = $felhasznalo['Felh_nev'];

    do {
        $berlesKezdete = $faker->dateTimeBetween('-180 days', 'now');
        $idokulonbseg = random_int(1, 100) <= 85
            ? random_int(60, 3600)
            : random_int(86400, 259200);
        $berlesVege = clone $berlesKezdete;
        $berlesVege->modify("+{$idokulonbseg} seconds");

        $atszokik = false;
        if (isset($rendszamokBerlesei[$rendszam])) {
            foreach ($rendszamokBerlesei[$rendszam] as $berles) {
                if (
                    ($berlesKezdete < $berles['vege'] && $berlesKezdete > $berles['kezdet']) ||
                    ($berlesVege > $berles['kezdet'] && $berlesVege < $berles['vege'])
                ) {
                    $atszokik = true;
                    $berlesKezdete->modify('+5 minutes');
                    $berlesVege->modify('+5 minutes');
                    break;
                }
            }
        }
    } while ($atszokik);
    $rendszamokBerlesei[$rendszam][] = ['kezdet' => $berlesKezdete, 'vege' => $berlesVege];

    $lezartBerles = new LezartBerles(
        $berlesAzonosito,
        $rendszam,
        (int) $besorolas,
        $berlesKezdete,
        $berlesVege,
        $felhasznaloNev
    );

    $berlesek[] = [
        'Berles_id' => $lezartBerles->getBerlesAzonosito(),
        'Rendszam' => $lezartBerles->getRendszam(),
        'Kat_besorolas' => $besorolas,
        'Berles_kezd_ev_ho_nap' => $lezartBerles->getBerlesKezdete()->format('Y-m-d'),
        'Berles_kezd_ora_perc_mp' => $lezartBerles->getBerlesKezdete()->format('H:i:s'),
        'Berles_vege_ev_ho_nap' => $lezartBerles->getBerlesVege()->format('Y-m-d'),
        'Berles_vege_ora_perc_mp' => $lezartBerles->getBerlesVege()->format('H:i:s'),
        'Felh_nev' => $lezartBerles->getFelhasznaloNev()
    ];
}
function appendToCSV($filePath, $data)
{
    $exists = file_exists($filePath);
    $file = fopen($filePath, 'a');

    if (!$exists) {
        fputcsv($file, array_keys($data[0]), ';'); 
    }
    foreach ($data as $row) {
        fputcsv($file, $row, ';');
    }
    fclose($file);
}
if ($kiterjesztes === 'json') {
    file_put_contents($output_dir . '/lezart_berlesek.json', json_encode($berlesek, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
} elseif ($kiterjesztes === 'csv') {
    appendToCSV($output_dir . '/lezart_berlesek.csv', $berlesek);
}