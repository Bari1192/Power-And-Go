<?php
namespace Database\SeederHelperek;

use Faker\Factory as Faker;

class FelhasznalokSeederHelper
{

    private array $felhNevekEllTar = [];
    private array $elofizKategoriak = ['Power', 'Power-Plus', 'Power-Premium', 'Power-VIP'];

    public function getRandomElofizKategoria(): string
    {
        $randomKey = array_rand($this->elofizKategoriak);
        return $this->elofizKategoriak[$randomKey];
    }

    public function felhasznaloNevGenerator(string $V_nev): string
    {
        $faker = Faker::create('hu_HU');
        do {
            $tipusValaszto = random_int(0, 1);
            if ($tipusValaszto === 0) {
                $felhNev_vege = $faker->numberBetween(1000, 9999);
                $keszFelhNev = $V_nev . $felhNev_vege;
                $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');
            } else {
                $felhNev_eleje = $faker->word;
                $felhNev_vege = $faker->numberBetween(100, 999);
                $keszFelhNev = $felhNev_eleje . $felhNev_vege;
                $keszFelhNev = strtr($keszFelhNev, 'áéíóöőúüűÁÉÍÓÖŐÚÜŰ', 'aeiooouuuAEIOOOUUU');
            }
        } while (in_array($keszFelhNev, $this->felhNevekEllTar));

        $this->felhNevekEllTar[] = $keszFelhNev;
        return $keszFelhNev;
    }

    public function generateJelszo24(string $jelszo): string
    {
        return $jelszo[1] . $jelszo[3]; // Második és negyedik számjegy
    }
}