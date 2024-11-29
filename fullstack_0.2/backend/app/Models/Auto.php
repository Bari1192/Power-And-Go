<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auto extends Model
{
    use HasFactory;

    protected $table = 'autok';
    protected $primaryKey = 'autok_id';
    public $incrementing = true;

    protected $fillable = [
        'rendszam',
        'flotta_id_fk',
        'kategoria_besorolas_fk',
        'km_ora_allas',
        'gyartasi_ev',
    ];
    public function flotta(): BelongsTo
    {
        return $this->belongsTo(Flotta_tipusok::class, 'flotta_id_fk', 'flotta_id');
    }

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_besorolas_fk', 'kat_id');
    }
}
