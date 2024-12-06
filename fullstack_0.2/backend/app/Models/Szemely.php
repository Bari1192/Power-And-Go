<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szemely extends Model
{
    use HasFactory;
    protected $table = 'szemelyek';
    protected $primaryKey = 'szemely_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'szemely_jelszo',
        'szig_szam',
        'jogos_szam',
        'jogos_erv_kezdete',
        'jogos_erv_vege',
        'v_nev',
        'k_nev',
        'szul_datum',
        'telefon',
        'email',
    ];
    public function felhasznalo()
    {
        return $this->hasOne(Felhasznalo::class, 'szemely_id_FK', 'szemely_id');
    }
    public function dolgozo()
    {
        return $this->hasOne(Dolgozo::class, 'szemely_id_FK', 'szemely_id');
    }
}
