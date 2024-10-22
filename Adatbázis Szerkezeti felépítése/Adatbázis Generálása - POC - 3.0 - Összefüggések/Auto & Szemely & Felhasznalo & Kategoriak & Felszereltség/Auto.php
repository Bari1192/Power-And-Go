<?php

declare(strict_types=1);

namespace Acme\Autok;

#1, VW, e-up!, AADT-141, 36, 12, 130, 165/65 R15, 300, 2023
# Azonosító [id] számot nem itt kap!

# Json + osztály kombinációs beolvasás / Magic Getter/Setter
class Auto
{
    private string $gyarto;
    private string $tipus;
    private int $teljesitmeny;
    private int $vegsebesseg;
    private string $gumimeret;
    private int $hatotav;
    private string $rendszam;
    private int $gyartasi_ev;
    private int $km;

    public function __construct(string $gyarto, string $tipus, int $teljesitmeny, int $vegsebesseg, string $gumimeret, int $hatotav, string $rendszam, int $gyartasi_ev)
    {
        $this->gyarto = $gyarto;
        $this->tipus = $tipus;
        $this->teljesitmeny = $teljesitmeny;
        $this->vegsebesseg = $vegsebesseg;
        $this->gumimeret = $gumimeret;
        $this->hatotav = $hatotav;
        $this->rendszam = $rendszam;
        $this->gyartasi_ev = $gyartasi_ev;
        $this->km = $this->kmOraAllasGeneralas($gyartasi_ev);
    }
    public function getGyarto(): string
    {
        return $this->gyarto;
    }

    public function getTipus(): string
    {
        return $this->tipus;
    }

    public function getTeljesitmeny(): int
    {
        return $this->teljesitmeny;
    }

    public function getVegsebesseg(): int
    {
        return $this->vegsebesseg;
    }

    public function getGumimeret(): string
    {
        return $this->gumimeret;
    }

    public function getHatotav(): int
    {
        return $this->hatotav;
    }

    public function getRendszam(): string
    {
        return $this->rendszam;
    }

    public function getGyartasiEv(): int
    {
        return $this->gyartasi_ev;
    }
    public function getKm(): int
    {
        return $this->km;
    }
    #Gyártási év alapján cirka mennyi km. legyen benne.
    private function kmOraAllasGeneralas(int $gyartasi_ev): int
    {
        switch ($gyartasi_ev) {
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
                return 0; #Ha új autó kerül hozzáadásra 2023 után!
        }
    }

}