<?php
declare(strict_types=1);
error_reporting(E_ALL);

# Azonosító [id] számot nem itt kap!
# jogositvany_szama, jogositvany_ervenyesseg, jogositvany_lejarata, V_nev, K_nev, Szig_szam, felh_jelszo, szul_datum, telefon, email

class Szemely{
    private string $V_nev;
    private string $K_nev;
    private \DateTime $szul_datum;
    private string $telefon;
    private string $email; 
    private string $Szig_szam;
    private string $jogositvany_szama;
    private \DateTime $jogositvany_ervenyesseg;
    private \DateTime $jogositvany_lejarata;
    private string $felh_jelszo;
    private string $felh_jelszo_megegyszer;


    public function __construct(string $jogositvany_szama, \DateTime $jogositvany_ervenyesseg, \DateTime $jogositvany_lejarata, string $V_nev, string $K_nev, string $Szig_szam, string $felh_jelszo, \DateTime $szul_datum, string $telefon, string $email, string $felh_jelszo_megegyszer)
    {
        $this->jogositvany_szama = $jogositvany_szama;
        $this->jogositvany_ervenyesseg = $jogositvany_ervenyesseg;
        $this->jogositvany_lejarata = $jogositvany_lejarata;
        $this->V_nev = $V_nev;
        $this->K_nev = $K_nev;
        $this->Szig_szam = $Szig_szam;
        $this->felh_jelszo = $felh_jelszo;
        $this->szul_datum = $szul_datum;
        $this->telefon = $telefon;
        $this->email = $email;
        $this->felh_jelszo_megegyszer=$felh_jelszo_megegyszer;
    }
    public function getJogsi(): string
    {
        return $this->jogositvany_szama;
    }
    public function getJogErvenyesseg(): \DateTime
    {
        return $this->jogositvany_ervenyesseg;
    }
    public function getJogLejarat(): \DateTime
    {
        return $this->jogositvany_lejarata;
    }
    public function getVnev(): string
    {
        return $this->V_nev;
    }
    public function getKnev(): string
    {
        return $this->K_nev;
    }
    public function getTeljesNev(): string
    {
        return $this->V_nev . ' ' . $this->K_nev;
    }
    public function getSzigszam(): string
    {
        return $this->Szig_szam;
    }
    public function getFelhJelszo(): string
    {
        return $this->felh_jelszo;
    }
    public function getFelhJelszoUjra(): string{
        if ($this->felh_jelszo === $this->felh_jelszo_megegyszer) {
            return $this->felh_jelszo_megegyszer;
        }else{
            echo"A Megadott jelszavak nem egyeznek!";
            return ''; // Üres string ha nem egyezik!
        }
    }
    public function GetSzulDatum(): \DateTime
    {
        return $this->szul_datum;
    }
    public function GetSzulDatumISO(): string
    {
        return $this->szul_datum->format('Y-m-d');
    }
    public function getTelefon(): string
    {
        return $this->telefon;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

}
