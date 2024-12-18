<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps=false;

    protected $fillable = [
        'rendszam',
        'kategoria_besorolas_fk',
        'felsz_id_fk',
        'flotta_id_fk',
        'km_ora_allas',
        'gyartasi_ev',
        'status',
        'toltes_szazalek',
        'toltes_kw',
        'becsult_hatotav',
    ];
    public function flotta(): BelongsTo
    {
        return $this->belongsTo(Flotta_tipusok::class, 'flotta_azon', 'flotta_id');
    }
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria', 'kat_besorolas');
    }

    public function carstatus(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class);
    }

    public function lezartberlesek(): HasMany
    {
        return $this->hasMany(LezartBerles::class, 'auto_azonosito', 'id');
    }
    public function szamlak(): HasMany
    {
        return $this->hasMany(Szamla::class, 'auto_azon', 'id');
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class,'car_id','id');
    }
}
