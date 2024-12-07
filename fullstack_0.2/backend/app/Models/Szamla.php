<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szamla extends Model
{
    use HasFactory;
    protected $table = 'szamlak';
    protected $primaryKey = 'szamla_id';
    public $incrementing = true;

    protected $fillable = [
        'szamla_tipus',
        'osszeg',
        'megtett_tavolsag',
        'parkolasi_perc',
        'vezetesi_perc',
        'berles_kezd_datum',
        'berles_kezd_ido',
        'berles_veg_datum',
        'berles_veg_ido',
        'szamla_kelt',
        'szamla_status',
    ];
}
