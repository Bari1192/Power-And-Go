<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    private array $felhNevekEllTar = [];

    public function definition(): array
    {
        ### CSAK olyan személyek, AKIKNEK VAN jogosítványuk!!!
        $szemely = Person::whereNotNull('jogos_szam')
            ->whereNotNull('jogos_erv_kezdete')
            ->whereNotNull('jogos_erv_vege')
            ->inRandomOrder()
            ->first();

        $elofizetes = Subscription::inRandomOrder()->first();

        return [
            'person_id' => $szemely->id,
            'felh_egyenleg' => 0,
            'jelszo_2_4' => $this->jelszoMasodikNegyedik($szemely->szemely_jelszo),
            'felh_nev' => $this->felhasznaloNevGenerator($szemely->v_nev),
            'elofiz_id' => $elofizetes->id,
            'password'=>Hash::make($szemely->szemely_jelszo),
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
            $foglaltFelhNev = User::where('felh_nev', '=', $keszFelhNev)->exists();
        } while ($foglaltFelhNev || in_array($keszFelhNev, $this->felhNevekEllTar));

        $this->felhNevekEllTar[] = $keszFelhNev;

        return $keszFelhNev;
    }
    private function jelszoMasodikNegyedik(string $jelszo): string
    {
        return $jelszo[1] . $jelszo[3];
    }
}
