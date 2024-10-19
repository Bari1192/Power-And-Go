<?php
declare(strict_types=1);
error_reporting(E_ALL);

// kategoriak.json -> Katalogusszerűen az autok JSON formátumban.
$jsonfajl = 'kategoriak.json';
// Tartalmának beolvasása:
$beolvasottJson=file_get_contents($jsonfajl);
$katalogus_tomb=json_decode($beolvasottJson, true);

class Kategoria{
    private ?int $kat_besorolas; // 1-2-3-4-5 VAGY NULL (hibás kw?) lehet //
    private string $tipus; // gyártónak a modell tipusa
    private string $gyarto;
    private int $kw;    

    private function __construct(string $gyarto, string $tipus, int $kw) {
        $this->gyarto = $gyarto;
        $this->tipus = $tipus;
        $this->kw = $kw;
        $this->kat_besorolas = ($this->kw == 65) ? 5 : (($this->kw == 50) ? 4 : (($this->kw == 45) ? 3 : (($this->kw == 36) ? 2 : (($this->kw == 18) ? 1 : null))));
        // 18, 36, 36, 36, 45, 50, 65
    }
    // Kategória besorolási logika
    public function getKategoria(): ?int {
        return $this->kat_besorolas;
    }
}

$fajl=fopen('kategoria_besorolasok.csv', 'w');

foreach ($katalogus_tomb as $auto) {
    $kategoriak = new Kategoria($auto['gyarto'], $auto['tipus'], (int)$auto['teljesitmeny']);
    
    // Adatok írása a CSV fájlba
    fputcsv($fajl, [$auto['gyarto'], $auto['tipus'], $auto['teljesitmeny'], $kategoriak->getKategoria()]);
}
fclose($fajl);