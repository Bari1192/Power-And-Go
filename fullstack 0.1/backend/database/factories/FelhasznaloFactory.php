<?php

namespace Database\Factories;

use App\Models\Felhasznalo;
use App\Models\Szemely;
use Illuminate\Database\Eloquent\Factories\Factory;
class FelhasznaloFactory extends Factory
{
    protected $model = Felhasznalo::class;
    private array $felhNevekEllTar = [];
    private array $elofizKategoriak = ['Power', 'Power-Plus', 'Power-Premium', 'Power-VIP'];

    public function definition(): array
    {
        ### CSAK olyan személyek, AKIKNEK VAN jogosítványuk!!!
        $szemely = Szemely::whereNotNull('jogos_szam')
            ->whereNotNull('jogos_erv_kezdete')
            ->whereNotNull('jogos_erv_vege')
            ->inRandomOrder()
            ->first();

        return [
            'szemely_id' => $szemely->szemely_id,
            'felh_egyenleg' => 0,
            'jelszo_2_4' => $this->jelszoMasodikNegyedik($szemely->szemely_jelszo), 
            'felh_nev' => $this->felhasznaloNevGenerator($szemely->v_nev),
            'elofiz_kat' => $this->elofizetesBesorolasa(),
        ];
    }
    public function felhasznaloNevGenerator(string $V_nev): string
    {
        do {
            $tipusValaszto = random_int(0, 1);

            ### [Vezetéknév + Szám] ###
            if ($tipusValaszto === 0) {
                $felhNev_vege = fake()->numberBetween(1000, 9999);
                $keszFelhNev = $V_nev . $felhNev_vege;

                ### [Random szó + Szám] ###
            } else {
                $felhNev_eleje = fake()->word;
                $felhNev_vege = fake()->numberBetween(100, 999);
                $keszFelhNev = $felhNev_eleje . $felhNev_vege;
            }

            # Speciális magyar karakterekre kell lecserélni a vezetéknevet!
            $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');
            # Vissza Ell. a DB-ben (UNIQE):
            $foglaltFelhNev = Felhasznalo::where('felh_nev', '=', $keszFelhNev)->exists();

        } while ($foglaltFelhNev || in_array($keszFelhNev, $this->felhNevekEllTar));

        $this->felhNevekEllTar[] = $keszFelhNev;

        return $keszFelhNev;
    }
    public function elofizetesBesorolasa(): string
    {
        $randomKey = array_rand($this->elofizKategoriak);
        return $this->elofizKategoriak[$randomKey];
    }
    private function jelszoMasodikNegyedik(string $jelszo): string
    {
        return $jelszo[1] . $jelszo[3];
    }

}
