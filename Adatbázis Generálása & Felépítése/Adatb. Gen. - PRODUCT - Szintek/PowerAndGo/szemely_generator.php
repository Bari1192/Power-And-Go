<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';

use Acme\Felhasznalok\Felhasznalo;
use Acme\Szemelyek\Szemely;
use Faker\Factory as FakerFactory;

$faker = FakerFactory::create('hu_HU');
$darab = $argv[1];
$szemelyek_fajl = 'szemelyek.' . $argv[2];
$felhasznalok_fajl = 'felhasznalok.' . $argv[2];

$output_dir = __DIR__ . '/src/output';
if (!is_dir($output_dir)) {
    mkdir($output_dir, 0777, true);
}
$szemelyek_fajl_utvonal = $output_dir . '/' . $szemelyek_fajl;
$felhasznalok_fajl_utvonal = $output_dir . '/' . $felhasznalok_fajl;

$elozo_id = 0;
if (file_exists($szemelyek_fajl_utvonal)) {
    if (str_ends_with($szemelyek_fajl_utvonal, 'csv')) {
        $fajl = fopen($szemelyek_fajl_utvonal, 'r');
        while (($sor = fgetcsv($fajl, 1_000, ";")) !== false) {
            $elozo_id = (int) $sor[0];
        }
        fclose($fajl);
    } elseif (str_ends_with($szemelyek_fajl_utvonal, 'json')) {
        $adatok = json_decode(file_get_contents($szemelyek_fajl_utvonal), true);
        if (!empty($adatok)) {
            $utolso_adat = end($adatok);
            $elozo_id = (int) $utolso_adat['ID'];
        }
    }
}
$szemelyekTomb = [];
$felhasznalokTomb = [];
$legeneraltSzemelyik = [];
function szemelyIgazolvanyok(array &$legeneraltSzemelyik, $faker): string
{
    do {
        $betuk = strtoupper($faker->bothify('??'));
        $szamok = str_pad((string) $faker->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        $elkeszult = $betuk . $szamok;
    }
    while (in_array($elkeszult, $legeneraltSzemelyik));
    $legeneraltSzemelyik[] = $elkeszult;
    return $elkeszult;
}

$felhNevek_ell_tar_tomb = [];
function felhasznaloNevGenerator(array &$felhNevek_ell_tar_tomb, $V_nev, $faker): string
{
    do {
        $tipusValaszto = random_int(0, 1);
        if ($tipusValaszto === 0) {
            $felhNev_vege = $faker->numberBetween(1_000, 9_999);
            $keszFelhNev = $V_nev . $felhNev_vege;
            $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');
        } else {
            $felhNev_eleje = $faker->word;
            $felhNev_vege = $faker->numberBetween(100, 999);
            $keszFelhNev = $felhNev_eleje . $felhNev_vege;
            $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');

        }
    } while (in_array($keszFelhNev, $felhNevek_ell_tar_tomb));

    $felhNevek_ell_tar_tomb[] = $keszFelhNev;
    return $keszFelhNev;

}
for ($i = 0; $i < $darab; $i++) {
    $id = $elozo_id + $i + 1;
    $V_nev = $faker->lastName();
    $K_nev = $faker->firstName();
    $szul_datum = $faker->dateTimeBetween($startDate = 'now - 64 years', $endDate = 'now -18 years', $timezone = 'Europe/Budapest');
    $telefon = '+36' . $faker->numerify('## ### ####');
    $telefon = $faker->regexify('\+36(20|30|70)[0-9]{3}[0-9]{4}');
    $email = $faker->safeEmail();
    $Szig_szam = szemelyigazolvanyok($legeneraltSzemelyik, $faker);
    $jogositvany_szama = szemelyigazolvanyok($legeneraltSzemelyik, $faker);
    $jogositvany_ervenyesseg = $faker->dateTimeBetween($startDate = 'now - 10 years', $endDate = 'now -1 days', $timezone = 'Europe/Budapest');
    $jogositvany_lejarata = (clone $jogositvany_ervenyesseg)->modify('+10 years');
    $felh_jelszo = (string) $faker->numberBetween(1_000, 9_999);
    $felh_jelszo_megegyszer = $felh_jelszo;

    $szemelyekTomb[] = [
        'ID' => $id,
        'V_nev' => $V_nev,
        'K_nev' => $K_nev,
        'Szul_datum' => $szul_datum->format('Y-m-d'),
        'Tel' => $telefon,
        'E-mail' => $email,
        'Szig_szam' => $Szig_szam,
        'Jogos_szam' => $jogositvany_szama,
        'Jogos_erv_kezdete' => $jogositvany_ervenyesseg->format('Y-m-d'),
        'jogos_erv_vege' => $jogositvany_lejarata->format('Y-m-d'),
        'Felh_jelszo' => $felh_jelszo,
    ];

    $felh_nev = felhasznaloNevGenerator($felhNevek_ell_tar_tomb, $V_nev, $faker);
    $jelszo_masodik_utolso = $felh_jelszo[1] . substr($felh_jelszo, -1);
    $felhasznalo = new Felhasznalo($felh_nev, $jelszo_masodik_utolso);
    $felhasznalokTomb[] = [
        'ID' => $id,
        'Felh_nev' => $felh_nev,
        'Jelszo' => $jelszo_masodik_utolso,
        'Elofizetesi_Kat' => $felhasznalo->getElofizKategoria()
    ];
}
if (str_ends_with($szemelyek_fajl_utvonal, 'csv')) {
    $fajl = fopen($szemelyek_fajl_utvonal, 'a');
    fputcsv($fajl, array_keys($szemelyekTomb[0]), ';');

    foreach ($szemelyekTomb as $sor) {
        fputcsv($fajl, $sor, ";");
    }
    fclose($fajl);
    $fajl = fopen($felhasznalok_fajl_utvonal, 'a');
    fputcsv($fajl, array_keys($felhasznalokTomb[0]), ';');
    foreach ($felhasznalokTomb as $sor) {
        fputcsv($fajl, $sor, ";");
    }
    fclose($fajl);
} elseif (str_ends_with($szemelyek_fajl_utvonal, 'json')) {
    $elozo_adatok = file_exists($szemelyek_fajl_utvonal) ? json_decode(file_get_contents($szemelyek_fajl_utvonal), true) : [];
    $vegso_adatok = array_merge($elozo_adatok, $szemelyekTomb);
    file_put_contents($szemelyek_fajl_utvonal, json_encode($vegso_adatok, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    $elozo_adatok_felhasznalo = file_exists($felhasznalok_fajl_utvonal) ? json_decode(file_get_contents($felhasznalok_fajl_utvonal), true) : [];
    $vegso_adatok_felhasznalo = array_merge($elozo_adatok_felhasznalo, $felhasznalokTomb);
    file_put_contents($felhasznalok_fajl_utvonal, json_encode($vegso_adatok_felhasznalo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}