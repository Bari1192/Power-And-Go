<?php

declare(strict_types=1);
namespace Acme\LezartBerlesek;

class LezartBerles
{
    private int $berlesAzonosito;
    private string $rendszam;
    private int $kategoriaBesorolas;
    private \DateTime $berlesKezdete;
    private \DateTime $berlesVege;
    private string $felhasznaloNev;

    public function __construct(
        int $berlesAzonosito,
        string $rendszam,
        int $kategoriaBesorolas,
        \DateTime $berlesKezdete,
        \DateTime $berlesVege,
        string $felhasznaloNev
    ) {
        $this->berlesAzonosito = $berlesAzonosito;
        $this->rendszam = $rendszam;
        $this->kategoriaBesorolas = $kategoriaBesorolas;
        $this->berlesKezdete = $berlesKezdete;
        $this->berlesVege = $berlesVege;
        $this->felhasznaloNev = $felhasznaloNev;
    }

    // Getterek az adatok lekéréséhez
    public function getBerlesAzonosito(): int
    {
        return $this->berlesAzonosito;
    }

    public function getRendszam(): string
    {
        return $this->rendszam;
    }

    public function getKategoriaBesorolas(): int
    {
        return $this->kategoriaBesorolas;
    }

    public function getBerlesKezdete(): \DateTime
    {
        return $this->berlesKezdete;
    }

    public function getBerlesVege(): \DateTime
    {
        return $this->berlesVege;
    }

    public function getFelhasznaloNev(): string
    {
        return $this->felhasznaloNev;
    }
}
