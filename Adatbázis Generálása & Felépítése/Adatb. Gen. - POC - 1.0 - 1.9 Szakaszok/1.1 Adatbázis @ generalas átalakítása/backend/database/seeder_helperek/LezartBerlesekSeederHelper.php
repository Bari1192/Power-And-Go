<?php

namespace Database\SeederHelperek;

use Faker\Factory as Faker;

class LezartBerlesekSeederHelper
{
    private array $rendszamokBerlesei = [];

    public function generateBerlesek(array $autok, array $kategoriak, array $felhasznalok, int $darab): array
    {
        $faker = Faker::create('hu_HU');
        $berlesek = [];

        for ($i = 0; $i < $darab; $i++) {
            $auto = $autok[array_rand($autok)];
            $rendszam = $auto->rendszam;

            $katBesorolas = $kategoriak[$rendszam] ?? 5;

            $felhasznalo = $felhasznalok[array_rand($felhasznalok)];
            $felhasznaloNev = $felhasznalo->felh_nev;

            do {
                $berlesKezdete = $faker->dateTimeBetween('-180 days', 'now');
                $idokulonbseg = random_int(1, 100) <= 85 ? random_int(60, 3600) : random_int(86400, 259200);
                $berlesVege = (clone $berlesKezdete)->modify("+{$idokulonbseg} seconds");

                $atszokik = false;
                if (isset($this->rendszamokBerlesei[$rendszam])) {
                    foreach ($this->rendszamokBerlesei[$rendszam] as $berles) {
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

            $this->rendszamokBerlesei[$rendszam][] = ['kezdet' => $berlesKezdete, 'vege' => $berlesVege];

            $berlesek[] = [
                'rendszam_fk' => $rendszam,
                'kat_besorolas_fk' => $katBesorolas,
                'felh_nev_fk' => $felhasznaloNev,
                'berles_kezd_datum' => $berlesKezdete->format('Y-m-d'),
                'berles_kezd_ido' => $berlesKezdete->format('H:i:s'),
                'berles_veg_datum' => $berlesVege->format('Y-m-d'),
                'berles_veg_ido' => $berlesVege->format('H:i:s'),
                'megtett_tavolsag' => $this->megtettTavolsagGenerator($berlesKezdete, $berlesVege),
            ];
        }

        return $berlesek;
    }
    public function megtettTavolsagGenerator(\DateTime $kezdet, \DateTime $veg): int
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
