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
}