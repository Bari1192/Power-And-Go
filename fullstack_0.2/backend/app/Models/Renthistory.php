<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renthistory extends Model
{
    use HasFactory;

    protected $table = 'renthistories';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        "id",
        "auto_id",
        "auto_kategoria",
        "szemely_id_fk",
        "nyitas_toltes_szazalek",
        "nyitas_toltes_kw",
        "zaras_toltes_szazalek",
        "zaras_toltes_kw",
        "berles_kezd_datum",
        "berles_kezd_ido",
        "berles_veg_datum",
        "berles_veg_ido",
        "megtett_tavolsag",
        "parkolas_kezd",
        "parkolas_veg",
        "parkolasi_perc",
        "vezetesi_perc",
        "berles_osszeg",
    ];

    public function auto()
    {
        return $this->belongsTo(Car::class, 'auto_azon','id');
    }

    public function kategoriak()
    {
        return $this->belongsTo(Category::class, 'auto_kat', 'id');
    }

    public function felhasznalo()
    {
        return $this->belongsTo(User::class, 'szemely_azon', 'id');
    }
}
