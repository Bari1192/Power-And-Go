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
        $person = Person::whereNotNull('driving_license')
            ->whereNotNull('license_start_date')
            ->whereNotNull('license_end_date')
            ->inRandomOrder()
            ->first();

        $elofizetes = Subscription::inRandomOrder()->first();

        return [
            'person_id' => $person->id,
            'account_balance' => 0,
            'password_2_4' => $this->jelszoMasodikNegyedik($person->person_password),
            'user_name' => $this->felhasznaloNevGenerator($person->firstname),
            'sub_id' => $elofizetes->id,
            'password'=>Hash::make($person->person_password),
        ];
    }
    public function felhasznaloNevGenerator(string $firstname): string
    {
        do {
            $carmodelValaszto = random_int(0, 1);

            ### [Vezetéknév + Szám] ###
            if ($carmodelValaszto === 0) {
                $felhNev_vege = fake()->numberBetween(1000, 9999);
                $keszFelhNev = $firstname . $felhNev_vege;

                ### [Random szó + Szám] ###
            } else {
                $felhNev_eleje = fake()->word;
                $felhNev_vege = fake()->numberBetween(100, 999);
                $keszFelhNev = $felhNev_eleje . $felhNev_vege;
            }

            # Speciális magyar karakterekre kell lecserélni a vezetéknevet!
            $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');
            # Vissza Ell. a DB-ben (UNIQE):
            $foglaltFelhNev = User::where('user_name', '=', $keszFelhNev)->exists();
        } while ($foglaltFelhNev || in_array($keszFelhNev, $this->felhNevekEllTar));

        $this->felhNevekEllTar[] = $keszFelhNev;

        return $keszFelhNev;
    }
    private function jelszoMasodikNegyedik(string $jelszo): string
    {
        return $jelszo[1] . $jelszo[3];
    }
}
