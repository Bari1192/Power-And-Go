<?php

declare(strict_types=1);

use Acme\LezartBerlesek\LezartBerles;
use Acme\Felhasznalok\Felhasznalo;
use Faker\Factory as FakerFactory;

require_once 'vendor/autoload.php';

$faker = FakerFactory::create('hu_HU');
$berlesek = [];
$rendszamokBerlesei = [];  // Tárolja az autók bérlési időpontjait, hogy ne legyenek átfedések
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

# Kategóriák indexelése rendszám alapján
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
            $headers = fgetcsv($handle, 1000, ';'); // Fejléc beolvasása
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $data[] = array_combine($headers, $row); // Fejléc hozzárendelése az értékekhez
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
    $felhasznalo_id = $felhasznalo['id'];

    # Személyek közül a felhasználóhoz tartozó adatokat keressük meg az ID alapján
    $szemely = array_filter($szemelyek, function ($szemely) use ($felhasznalo_id) {
        return $szemely['id'] == $felhasznalo_id;
    });

    # Ha nincs megfelelő személy az ID alapján, hibát dobunk
    if (empty($szemely)) {
        throw new Exception("Előszőr generálja le a személyeket! [szemely_generator.php]");
    }

    # Felhasználó nevének beállítása
    $felhasznaloNev = $felhasznalo['Felhasználónév'];

    # Véletlenszerű bérlési időpontok generálása
    do {
        $berlesKezdete = $faker->dateTimeBetween('-30 days', 'now');
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
                    # Átfedés van, próbáljunk egy új időt
                    $atszokik = true;
                    $berlesKezdete->modify('+5 minutes');  # Adjunk 5 perc különbséget
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
        'berles_azonosito' => $lezartBerles->getBerlesAzonosito(),
        'rendszam' => $lezartBerles->getRendszam(),
        'kategoria_besorolas' => $besorolas,
        'berles_kezdete' => $lezartBerles->getBerlesKezdete()->format('Y-m-d H:i:s'),
        'berles_vege' => $lezartBerles->getBerlesVege()->format('Y-m-d H:i:s'),
        'felhasznalo_nev' => $lezartBerles->getFelhasznaloNev()
    ];
}
# Mentés JSON fájlba
if ($kiterjesztes === 'json') {
    file_put_contents($output_dir . '/lezart_berlesek.json', json_encode($berlesek, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    # Mentés CSV fájlba
} elseif ($kiterjesztes === 'csv') {
    $csvFajlUt = $output_dir . '/lezart_berlesek.csv';
    $fajl = fopen($csvFajlUt, 'w');
    fputcsv($fajl, array_keys($berlesek[0]), ';');  // Fejléc
    foreach ($berlesek as $berles) {
        fputcsv($fajl, $berles, ';');
    }
    fclose($fajl);
}
