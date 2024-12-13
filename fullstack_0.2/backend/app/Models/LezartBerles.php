<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LezartBerles extends Model
{
    use HasFactory;

    protected $table = 'lezart_berlesek';
    protected $primaryKey = 'lezart_berles_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "lezart_berles_id",
        "auto_azonosito",
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
        return $this->belongsTo(Auto::class, 'auto_azonosito', 'autok_id');
    }

    public function kategoriak()
    {
        return $this->belongsTo(Kategoria::class, 'auto_kategoria', 'kat_id');
    }

    public function felhasznalo()
    {
        return $this->belongsTo(Felhasznalo::class, 'szemely_id_fk', 'szemely_id');
    }
}
