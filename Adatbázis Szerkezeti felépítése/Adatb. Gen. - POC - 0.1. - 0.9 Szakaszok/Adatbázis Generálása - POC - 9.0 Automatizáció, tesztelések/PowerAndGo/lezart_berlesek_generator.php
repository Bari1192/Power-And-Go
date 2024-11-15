<?php

declare(strict_types=1);

use Acme\LezartBerlesek\LezartBerles;
use Acme\Felhasznalok\Felhasznalo;
use Faker\Factory as FakerFactory;

require_once 'vendor/autoload.php';

$faker = FakerFactory::create('hu_HU');
$berlesek = [];
$rendszamokBerlesei = [];  # Tároljuk az autók bérlési IDŐPONTOKAT, hogy NE legyenek ÁTFEDÉSEK!
$darab = (int) $argv[1];
$kiterjesztes = (string) $argv[2];

$output_dir = __DIR__ . '/src/output';
if (!is_dir($output_dir)) {
    mkdir($output_dir, 0777, true);
}


# Adatok betöltése (autók, kategóriák, felhasználók, személyek)
$autok = loadData($output_dir . '/autok.json', $output_dir . '/autok.csv');
$kategoriak = loadData($output_dir . '/kategoriak.json', $output_dir . '/kategoriak.csv');
$felhasznalok = loadData($output_dir . '/felhasznalok.json', $output_dir . '/felhasznalok.csv');
$szemelyek = loadData($output_dir . '/szemelyek.json', $output_dir . '/szemelyek.csv');

# Kategóriák indexelése RENDSZÁM KULCS alapján
$kategoriakByRendszam = [];
foreach ($kategoriak as $kategoria) {
    $kategoriakByRendszam[$kategoria['Rendszam']] = $kategoria['Besorolas'];
}

# Betöltési funkció JSON/CSV alapján
function loadData(string $jsonPath, string $csvPath): array
{
    if (file_exists($jsonPath)) {
        return json_decode(file_get_contents($jsonPath), true);
    } elseif (file_exists($csvPath)) {
        $data = [];
        if (($handle = fopen($csvPath, 'r')) !== false) {
            # Fejléc beolvasása
            $headers = fgetcsv($handle, 1000, ';');
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                # Fejléc hozzárendelése az értékekhez
                $data[] = array_combine($headers, $row);
            }
            fclose($handle);
        }
        return $data;
    } else {
        throw new Exception("Fájl nem található: $jsonPath vagy $csvPath");
    }
}

# Bérlések generálása
for ($i = 0; $i < $darab; $i++) {
    $berlesAzonosito = $i + 1;

    # Véletlenszerűen egy autó kiválasztása
    $auto = $autok[array_rand($autok)];
    $rendszam = $auto['Rendszam'];

    # Kategória besorolás betöltése a kategoriak.csv alapján
    if (isset($kategoriakByRendszam[$rendszam])) {
        $besorolas = $kategoriakByRendszam[$rendszam];
    } else {
        echo "Hiba: Besorolás nem található a rendszámhoz: $rendszam\n";
        continue;
    }

    # Véletlenszerű felhasználó kiválasztása a felhasználók közül
    $felhasznalo = $felhasznalok[array_rand($felhasznalok)];
    $felhasznalo_id = $felhasznalo['ID'];

    # Személyek közül a felhasználóhoz tartozó adatokat keressük meg az ID alapján
    $szemely = array_filter($szemelyek, function ($szemely) use ($felhasznalo_id) {
        return $szemely['ID'] == $felhasznalo_id;
    });

    # Ha nincs megfelelő személy az ID alapján, hibát dobunk
    if (empty($szemely)) {
        throw new Exception("Előszőr generálja le a személyeket! [szemely_generator.php]");
    }

    # Felhasználó nevének beállítása
    $felhasznaloNev = $felhasznalo['Felh_nev'];

    # Véletlenszerű bérlési időpontok generálása
    do {
        # 30 NAPOS bérlések:
        // $berlesKezdete = $faker->dateTimeBetween('-30 days', 'now');
        # 90 NAPOS bérlések
        // $berlesKezdete = $faker->dateTimeBetween('-90 days', 'now');
        # 180 NAPOS bérlések
        $berlesKezdete = $faker->dateTimeBetween('-180 days', 'now');
        # 1 ÉVES bérlések:
        // $berlesKezdete = $faker->dateTimeBetween('-1 years', 'now');
        $idokulonbseg = random_int(1, 100) <= 85
            ? random_int(60, 3600)  # 1 perc - 1 óra (85%)
            : random_int(86400, 259200);  # 1 nap - 3 nap (15%)
        $berlesVege = clone $berlesKezdete;
        $berlesVege->modify("+{$idokulonbseg} seconds");

        # Ellenőrzés: nincs-e átfedés más bérléssel az adott autóra
        $atszokik = false;
        if (isset($rendszamokBerlesei[$rendszam])) {
            foreach ($rendszamokBerlesei[$rendszam] as $berles) {
                if (
                    ($berlesKezdete < $berles['vege'] && $berlesKezdete > $berles['kezdet']) ||
                    ($berlesVege > $berles['kezdet'] && $berlesVege < $berles['vege'])
                ) {
                    # Átfedés van, akkor próbáljunk egy új időt generálni!
                    $atszokik = true;
                    $berlesKezdete->modify('+5 minutes');  # Adjunk 5 perc különbségeket
                    $berlesVege->modify('+5 minutes');
                    break;
                }
            }
        }
    } while ($atszokik);

    # Bérlés hozzáadása
    $rendszamokBerlesei[$rendszam][] = ['kezdet' => $berlesKezdete, 'vege' => $berlesVege];

    # Lezárt bérlés objektum létrehozása
    $lezartBerles = new LezartBerles(
        $berlesAzonosito,
        $rendszam,
        (int) $besorolas,  # Kategória besorolás számmá konvertálva
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

#Fájlba írásnak itt külön függvény-t.
function appendToCSV($filePath, $data)
{
    $exists = file_exists($filePath);
    $file = fopen($filePath, 'a');

    if (!$exists) {
        fputcsv($file, array_keys($data[0]), ';'); // Fejléc, ha a fájl nem létezik
    }

    foreach ($data as $row) {
        fputcsv($file, $row, ';');
    }
    fclose($file);
}

#Fájlmentés
if ($kiterjesztes === 'json') {
    file_put_contents($output_dir . '/lezart_berlesek.json', json_encode($berlesek, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
} elseif ($kiterjesztes === 'csv') {
    appendToCSV($output_dir . '/lezart_berlesek.csv', $berlesek);
}