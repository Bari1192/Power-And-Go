<?php

declare(strict_types=1);
error_reporting(E_ALL);

namespace Acme\Autok;

#1, VW, e-up!, AADT-141, 36, 12, 130, 165/65 R15, 300, 2023
# Azonosító [id] számot nem itt kap!

class Auto
{
    private string $gyarto;
    private string $tipus;
    private string $rendszam;
    private int $teljesitmeny;
    private int $gyorsulas;
    private int $vegsebesseg;
    private string $gumimeret;
    private int $hatotav;
    private int $gyartasi_ev;

    public function __construct(string $gyarto, string $tipus, string $rendszam, int $teljesitmeny, int $gyorsulas, int $vegsebesseg, string $gumimeret, int $hatotav, int $gyartasi_ev,)
    {
        $this->gyarto = $gyarto;
        $this->tipus = $tipus;
        $this->rendszam = $rendszam;
        $this->teljesitmeny = $teljesitmeny;
        $this->gyorsulas = $gyorsulas;
        $this->vegsebesseg = $vegsebesseg;
        $this->gumimeret = $gumimeret;
        $this->hatotav = $hatotav;
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
    public function getRendszam(): string
    {
        return $this->rendszam;
    }
    public function getTeljesitmeny(): int
    {
        return $this->teljesitmeny;
    }
    public function getGyorsulas(): int
    {
        return $this->gyorsulas;
    }
    public function getSebesseg(): int
    {
        return $this->vegsebesseg;
    }
    public function getGumi(): string
    {
        return $this->gumimeret;
    }
    public function getHatotav(): int
    {
        return $this->hatotav;
    }
    public function getGyartasiEv(): int
    {
        return $this->gyartasi_ev;
    }
}
