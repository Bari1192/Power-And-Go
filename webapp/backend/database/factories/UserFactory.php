<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;
    private static array $felhNevekEllTar = [];
    private static $persons = null;
    private static $subscriptions = null;

    public function definition(): array
    {
        // Optimalizáció: csak egyszer töltsük be a személyeket és előfizetéseket
        if (self::$persons === null) {
            self::$persons = Person::whereNotNull('driving_license')
                ->whereNotNull('license_start_date')
                ->whereNotNull('license_end_date')
                ->get();
        }

        if (self::$subscriptions === null) {
            self::$subscriptions = Subscription::select('id')->pluck('id')->toArray();
        }

        // Személy kiválasztása
        $person = self::$persons->random();

        // Előfizetés kiválasztása
        $subid = self::$subscriptions[array_rand(self::$subscriptions)];
        $pw = (string)fake()->regexify('[0-9]{4}');
        return [
            'person_id' => $person->id,
            'sub_id' => $subid,
            'account_balance' => 20_000,
            'user_name' => $this->felhasznaloNevGenerator($person->firstname),
            'vip_discount' => $this->isPowerVipDiscontAvaliable($subid),

            'plant_tree' => $this->plantTreeCampaignSubscribe(),
            'bonus_min_exp' => null, //Carbon::now()->addDays(30)->format('Y-m-d'),
            'password' => $pw,
            'bonus_minutes' => 0,
            'driving_minutes' => $this->plantTreeCampaignSubscribe() ? 0 : null,
            'contributions' => $this->plantTreeCampaignSubscribe() ? 0 : null,
            'password_2_4' => $this->jelszoMasodikNegyedik($pw),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    public function isPowerVipDiscontAvaliable($subid): bool
    {
        return ($subid == 4);
    }

    public function plantTreeCampaignSubscribe(): bool
    {
        return (random_int(1, 5) > 2);
    }

    public function felhasznaloNevGenerator(string $firstname): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $attempts++;
            $whichType = random_int(0, 1);

            if ($whichType === 0) {
                $felhNev_vege = fake()->numberBetween(1000, 9999);
                $keszFelhNev = $firstname . $felhNev_vege;
            } else {
                $felhNev_eleje = fake()->word;
                $felhNev_vege = fake()->numberBetween(100, 999);
                $keszFelhNev = $felhNev_eleje . $felhNev_vege;
            }

            $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');

            // Ha túl sok próbálkozás volt, egyedi azonosítót fűzünk hozzá
            if ($attempts >= $maxAttempts) {
                $keszFelhNev .= '_' . uniqid();
            }
        } while (isset(self::$felhNevekEllTar[$keszFelhNev]) && $attempts < $maxAttempts);

        self::$felhNevekEllTar[$keszFelhNev] = true;
        return $keszFelhNev;
    }

    private function jelszoMasodikNegyedik($pw): string
    {
        if (strlen($pw) >= 4) {
            return ($pw[1] . $pw[3]);
        }
        return '00'; // Fallback érték
    }
}
