<?php
declare(strict_types=1);

namespace Acme\Kategoriak;
class Kategoria
{
    private string $rendszam;
    private string $tipus;
    private int $besorolas;
    public function __construct(string $rendszam, string $tipus, int $teljesitmeny)
    {
        $this->rendszam = $rendszam;
        $this->tipus = $tipus;
        $this->besorolas = $this->kategoriaBesorolas($teljesitmeny);
    }
    public function getRendszam(): string
    {
        return $this->rendszam;
    }

    public function getTipus(): string
    {
        return $this->tipus;
    }

    public function getBesorolas(): int
    {
        return $this->besorolas;
    }
    private function kategoriaBesorolas(int $teljesitmeny): int
    {
        if ($teljesitmeny == 18) {
            return 1;
        } elseif ($teljesitmeny == 36) {
            return 2;
        } elseif ($teljesitmeny == 45) {
            return 3;
        } elseif ($teljesitmeny == 50) {
            return 4;
        } else {
            return 5;
        }
    }
}