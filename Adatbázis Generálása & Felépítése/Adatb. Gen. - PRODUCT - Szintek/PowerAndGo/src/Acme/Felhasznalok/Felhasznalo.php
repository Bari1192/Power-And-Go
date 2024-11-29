<?php

declare(strict_types=1);
namespace Acme\Felhasznalok;

class Felhasznalo
{
    private string $felh_nev;
    private string $jelszo_masodik_utolso;
    private array $elofizKategoriak = ["Power", "Power-Plus", "Power-Premium", "Power-VIP"];
    private string $felh_Elofizetese;

    public function __construct(string $felh_nev, string $teljes_jelszo)
    {
        $this->felh_nev = $felh_nev;
        $this->felh_Elofizetese = $this->getRandomElofizKategoria();
        $this->jelszo_masodik_utolso = $teljes_jelszo[1] . substr($teljes_jelszo, -1);
    }
    public function getFelhNev(): string
    {
        return $this->felh_nev;
    }
    public function getJelszo(): string
    {
        return $this->jelszo_masodik_utolso;
    }
    public function getElofizKategoria(): string
    {
        return $this->felh_Elofizetese;
    }
    private function getRandomElofizKategoria(): string
    {
        $randomelofizetes = array_rand($this->elofizKategoriak);
        return $this->elofizKategoriak[$randomelofizetes];
    }
}
