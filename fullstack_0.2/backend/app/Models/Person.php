<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';
    protected $primaryKey = 'id';
    public $timestamps = false;
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
        return $this->hasOne(User::class, 'szemely_id', 'id');
    }
    public function dolgozo()
    {
        return $this->hasOne(Employee::class, 'szemely_azon', 'id');
    }
}
