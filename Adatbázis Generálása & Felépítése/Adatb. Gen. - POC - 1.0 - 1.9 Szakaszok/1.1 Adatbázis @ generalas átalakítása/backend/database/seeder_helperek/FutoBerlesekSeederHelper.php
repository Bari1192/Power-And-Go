<?php
namespace Database\SeederHelperek;

use Faker\Factory as Faker;

class FutoBerlesekSeederHelper
{
    private array $hasznaltRendszamok = [];
    private array $hasznaltFelhasznalok = [];
    #Creepy név, de vicces.

    public function generateFutoBerlesek(array $autok, array $kategoriak, array $felhasznalok, int $darab): array
    {
        $faker = Faker::create('hu_HU');
        $berlesek = [];
        $aktualisIdo = new \DateTime();

        // Felhasznalok TÁBLA 20%-ának generálunk FUTO_BERLES-t
        $kivalasztottFelhasznalok = array_rand($felhasznalok, (int)($darab * 0.2));
        if (!is_array($kivalasztottFelhasznalok)) {
            $kivalasztottFelhasznalok = [$kivalasztottFelhasznalok];
        }

        foreach ($kivalasztottFelhasznalok as $index) {
            $felhasznalo = $felhasznalok[$index];

            # Ha a felh. NINCS még bérlése:
            if (in_array($felhasznalo['felh_nev'], $this->hasznaltFelhasznalok, true)) {
                continue;
            }

            # Autó kiválasztása
            $auto = $autok[array_rand($autok)];
            $rendszam = $auto['rendszam'];

            # Ugyanazon autó ne lehessen több bérlésben:
            if (in_array($rendszam, $this->hasznaltRendszamok, true)) {
                continue;
            }

            $katBesorolas = $kategoriak[$rendszam] ?? 5;

            # Bérlés kezdési dátumok beállítása:
            $valoszinuseg = random_int(1, 100);
            if ($valoszinuseg <= 5) {
                $idoKezdet = '-72 hours';
            } elseif ($valoszinuseg <= 15) {
                $idoKezdet = '-24 hours';
            } elseif ($valoszinuseg <= 30) {
                $idoKezdet = '-18 hours';
            } elseif ($valoszinuseg <= 45) {
                $idoKezdet = '-8 hours';
            } elseif ($valoszinuseg <= 65) {
                $idoKezdet = '-4 hours';
            } else {
                $idoKezdet = '-2 hours';
            }

            $berlesKezdete = (clone $aktualisIdo)->modify($idoKezdet);

            $berlesek[] = [
                'rendszam' => $rendszam,
                'kat_besorolas' => $katBesorolas,
                'felh_nev' => $felhasznalo['felh_nev'],
                'berles_kezd_datum' => $berlesKezdete->format('Y-m-d'),
                'berles_kezd_ido' => $berlesKezdete->format('H:i:s'),
            ];
            # Visszaellenőrzésre a tömbbe, hogy ne kerüljön újra bele.
            $this->hasznaltFelhasznalok[] = $felhasznalo['felh_nev'];
            $this->hasznaltRendszamok[] = $rendszam;
        }
        return $berlesek;
    }
}
