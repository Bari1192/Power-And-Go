<?php

declare(strict_types=1);

namespace Acme\Autok;

use Faker\Factory; // Faker helyes importja

class Autok
{
    private array $autok = [];
    private array $flottaTipusok;
    private array $rendszamok = [];

    public function __construct(array $flottaTipusok)
    {
        $this->flottaTipusok = $flottaTipusok;
    }

    public function generateAutok(int $count): void
    {
        $faker = Factory::create(); // Faker példányosítás
        for ($i = 0; $i < $count; $i++) {
            $flottaKivalasztas = $this->flottaTipusok[array_rand($this->flottaTipusok)];

if (!array_key_exists('flotta_id', $flottaKivalasztas)) {
    die("Hiba: A flotta adat nem tartalmaz 'flotta_id' kulcsot.\n");
}

            do {
                $rendszamUjRegi = random_int(0, 1);
                if ($rendszamUjRegi > 0) {
                    $rendszam = strtoupper($faker->regexify('AA[A-C][A-O]-[0-9]{3}'));
                } else {
                    $rendszam = strtoupper($faker->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
                }
            } while (in_array($rendszam, $this->rendszamok));

            $this->rendszamok[] = $rendszam;

            $gyartasiEv = random_int(2019, 2023);
            $kmOraAllas = $this->kmOraAllasGeneralas($gyartasiEv);

            $this->autok[] = [
                'autok_id' => $i + 1,
                'flotta_id' => $flottaKivalasztas['flotta_id'],
                'rendszam' => $rendszam,
                'gyartasi_ev' => $gyartasiEv,
                'km_ora_allas' => $kmOraAllas,
            ];
        }
    }

    private function kmOraAllasGeneralas(int $gyartasiEv): int
    {
        switch ($gyartasiEv) {
            case 2019:
                return random_int(50_000, 60_000);
            case 2020:
                return random_int(40_000, 60_000);
            case 2021:
                return random_int(30_000, 40_000);
            case 2022:
                return random_int(25_000, 35_000);
            case 2023:
                return random_int(20_000, 30_000);
            default:
                return 0;
        }
    }

    public function generateSQL(): string
    {
        $sql = "CREATE TABLE IF NOT EXISTS `autok` (
    `autok_id` INT AUTO_INCREMENT PRIMARY KEY,
    `flotta_id` INT,
    `rendszam` VARCHAR(20) UNIQUE,
    `gyartasi_ev` INT,
    `km_ora_allas` INT,
    FOREIGN KEY (`flotta_id`) REFERENCES `flotta_tipusok`(`flotta_id`)
);\n\n";

        $sql .= "INSERT INTO `autok` (`autok_id`, `flotta_id`, `rendszam`, `gyartasi_ev`, `km_ora_allas`)\nVALUES\n";

        $values = [];
        foreach ($this->autok as $auto) {
            $values[] = sprintf(
                "(%d, %d, '%s', %d, %d)",
                $auto['autok_id'],
                $auto['flotta_id'],
                $auto['rendszam'],
                $auto['gyartasi_ev'],
                $auto['km_ora_allas']
            );
        }

        $sql .= implode(",\n", $values) . ";\n";

        return $sql;
    }
}
