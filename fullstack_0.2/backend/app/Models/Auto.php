<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auto extends Model
{
    use HasFactory;

    protected $table = 'autok';
    protected $primaryKey = 'autok_id';
    public $incrementing = true;

    protected $fillable = [
        'rendszam',
        'kategoria_besorolas_fk',
        'felsz_id_fk',
        'flotta_id_fk',
        'km_ora_allas',
        'gyartasi_ev',
    ];
    public function flotta(): BelongsTo
    {
        return $this->belongsTo(Flotta_tipusok::class, 'flotta_id_fk', 'flotta_id');
    }

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_besorolas_fk', 'kat_besorolas');
    }
    public function toltes_kw_becsles()
    {
        return round($this->flotta_tipusok->teljesitmeny * ($this->toltes_szazalek / 100), 1);
        ### teljesitmeny kw (pl: 36) * generált töltési % (pl 76) -> ami 0,76-os szorzás => visszajön a 27,36 => 
        ### round 1 => ~27,4 kW töltés lesz a kocsiban.
    }
    public function toltes_becsult_hatotavja()
    {
        $egyKilowattHanyKm = ($this->flotta_tipusok->hatotav / $this->flotta_tipusok->teljesitmeny);
        return round($egyKilowattHanyKm * $this->toltes_kw, 1);
        ### hatotav (265 km) / teljesitmeny (36kw) * toltes_kw_becslessel (27,4) ==> ~201,6944
        ### round 1 => 201,7 km becsült hatótáv még.
    }
}
