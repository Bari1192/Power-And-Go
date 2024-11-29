<?php

namespace Database\Factories;

use App\Models\Auto;
use App\Models\Felhasznalo;
use App\Models\LezartBerles;
use Illuminate\Database\Eloquent\Factories\Factory;
class LezartBerlesFactory extends Factory
{
    protected $model = LezartBerles::class;
    private array $rendszamokBerlesei = [];

    public function definition(): array
    {
        $auto = Auto::inRandomOrder()->first();
        $felhasznalo = Felhasznalo::inRandomOrder()->first();
        do {
            $berlesKezdete = fake()->dateTimeBetween('-180 days', 'now');
            $idokulonbseg = random_int(1, 100) <= 85 ? random_int(60, 3600) : random_int(86400, 259200);
            $berlesVege = (clone $berlesKezdete)->modify("+{$idokulonbseg} seconds");

            $atszokik = false;

            if (isset($this->rendszamokBerlesei[$auto->rendszam])) {
                foreach ($this->rendszamokBerlesei[$auto->rendszam] as $berles) {
                    if (
                        ($berlesKezdete < $berles['vege'] && $berlesKezdete > $berles['kezdet']) ||
                        ($berlesVege > $berles['kezdet'] && $berlesVege < $berles['vege'])
                    ) {
                        $atszokik = true;
                        break;
                    }
                }
            }
        } while ($atszokik);

        // Rendszám időszakainak frissítése
        $this->rendszamokBerlesei[$auto->rendszam][] = [
            'kezdet' => $berlesKezdete,
            'vege' => $berlesVege,
        ];

        return [
            'auto_azonosito' => $auto->autok_id,
            'auto_kategoria' => $auto->kategoria_besorolas_fk,
            'szemely_id_fk' => $felhasznalo->szemely_id, // FK helyes elnevezése
            'berles_kezd_datum' => $berlesKezdete->format('Y-m-d'),
            'berles_kezd_ido' => $berlesKezdete->format('H:i:s'),
            'berles_veg_datum' => $berlesVege->format('Y-m-d'),
            'berles_veg_ido' => $berlesVege->format('H:i:s'),
            'megtett_tavolsag' => $this->megtettTavolsagGenerator($berlesKezdete, $berlesVege),
        ];
    }

    private function megtettTavolsagGenerator(\DateTime $kezdet, \DateTime $veg): int
    {
        $idoKulonbseg = $veg->getTimestamp() - $kezdet->getTimestamp();

        if ($idoKulonbseg <= 1800) {
            return random_int(5, 10);
        } elseif ($idoKulonbseg <= 3600) {
            return random_int(10, 20);
        } elseif ($idoKulonbseg <= 7200) {
            return random_int(10, 35);
        } elseif ($idoKulonbseg <= 14400) {
            return random_int(25, 45);
        } elseif ($idoKulonbseg <= 28800) {
            return random_int(35, 60);
        } elseif ($idoKulonbseg <= 86400) {
            return random_int(40, 80);
        } else {
            return random_int(100, 150);
        }
    }
}