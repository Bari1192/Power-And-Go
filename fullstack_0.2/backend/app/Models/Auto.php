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
    protected $keyType='int';

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
        return $this->belongsTo(Flotta_tipusok::class, 'flotta_id_fk', 'flotta_id');
    }

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_besorolas_fk', 'kat_besorolas');
    }
    public function carstatus(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class, 'status', 'carstatus_id');
    }
}
