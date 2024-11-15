<?php
declare(strict_types=1);

namespace Acme\Felszereltsegek;
class Felszereltseg
{
    private string $rendszam;
    private string $tipus;
    private array $felszerelesek;

    public function __construct(string $rendszam, string $tipus, int $besorolas, string $gyarto)
    {
        $this->rendszam = $rendszam;
        $this->tipus = $tipus;
        $this->felszerelesek = $this->generateFelszereltseg($besorolas, $gyarto);
    }
    public function getRendszam(): string
    {
        return $this->rendszam;
    }

    public function getTipus(): string
    {
        return $this->tipus;
    }

    public function getFelszerelesek(): array
    {
        return $this->felszerelesek;
    }

    // Felszereltség generálása a besorolas és a gyártó alapján
    private function generateFelszereltseg(int $besorolas, string $gyarto): array
    {
        $felszerelesek = [
            'Tolatokamera' => 'Nincs',
            'Tolatoradar' => 'Nincs',
            'Multifunkcionalis_Kormany' => 'Nincs',
            'Savtarto' => 'Nincs',
            'Tempomat' => 'Nincs'
        ];

        # 18 kW esetén 50%-ban teljes felszereltség, 50%-ban részleges
        # 36 kW esetén 80%-ban teljes felszereltség, 20%-ban részleges
        # Minden más esetben teljes felszereltség
        if ($besorolas === 1) {
            if (random_int(1, 100) <= 50) {
                foreach ($felszerelesek as $key => &$value) {
                    $value = $key;
                }
            } else {
                $felszerelesek['Tolatoradar'] = 'Tolatoradar';
                $felszerelesek['Tempomat'] = 'Tempomat';
            }
        } elseif ($besorolas === 2 && in_array($gyarto, ['VW', 'Skoda'])) {
            if (random_int(1, 100) <= 80) {
                foreach ($felszerelesek as $key => &$value) {
                    $value = $key;
                }
            } else {
                $felszerelesek['Multifunkcionalis_Kormany'] = 'Multifunkcionalis_Kormany';
                $felszerelesek['Tolatoradar'] = 'Tolatoradar';
                $felszerelesek['Tempomat'] = 'Tempomat';
                $felszerelesek['Savtarto'] = 'Savtarto';
            }
        } else {
            foreach ($felszerelesek as $key => &$value) {
                $value = $key;
            }
        }

        return $felszerelesek;
    }
}