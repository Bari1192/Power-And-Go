<?php

namespace Acme\FlottaTipusok;

class FlottaTipusok
{
    private array $flottaAdatok = [
        ['flotta_id' => 1, 'gyarto' => 'VW', 'tipus' => 'e-up!', 'teljesitmeny' => 18, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 130],
        ['flotta_id' => 2, 'gyarto' => 'VW', 'tipus' => 'e-up!', 'teljesitmeny' => 36, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 300],
        ['flotta_id' => 3, 'gyarto' => 'Skoda', 'tipus' => 'Citigo-e-iV', 'teljesitmeny' => 36, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R16', 'hatotav' => 300],
        ['flotta_id' => 4, 'gyarto' => 'Renault', 'tipus' => 'Kangoo-Z.E.', 'teljesitmeny' => 45, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 285],
        ['flotta_id' => 5, 'gyarto' => 'Opel', 'tipus' => 'Vivaro-e', 'teljesitmeny' => 50, 'vegsebesseg' => 192, 'gumimeret' => '165|65-R16', 'hatotav' => 320],
        ['flotta_id' => 6, 'gyarto' => 'KIA', 'tipus' => 'Niro-EV', 'teljesitmeny' => 65, 'vegsebesseg' => 167, 'gumimeret' => '165|65-R17', 'hatotav' => 350],
    ];

    public function generateSQL(): string
    {
        $sql = "CREATE TABLE IF NOT EXISTS `flotta_tipusok` (
    `flotta_id` INT AUTO_INCREMENT PRIMARY KEY,
    `gyarto` VARCHAR(50),
    `tipus` VARCHAR(50),
    `teljesitmeny` INT,
    `vegsebesseg` INT,
    `gumimeret` VARCHAR(20),
    `hatotav` INT
);\n\n";

        $sql .= "INSERT INTO `flotta_tipusok` (`flotta_id`, `gyarto`, `tipus`, `teljesitmeny`, `vegsebesseg`, `gumimeret`, `hatotav`)\nVALUES\n";

        $values = [];
        foreach ($this->flottaAdatok as $adat) {
            $values[] = sprintf(
                "(%d, '%s', '%s', %d, %d, '%s', %d)",
                $adat['flotta_id'],
                $adat['gyarto'],
                $adat['tipus'],
                $adat['teljesitmeny'],
                $adat['vegsebesseg'],
                $adat['gumimeret'],
                $adat['hatotav']
            );
        }

        $sql .= implode(",\n", $values) . ";\n";

        return $sql;
    }
}
