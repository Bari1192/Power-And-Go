<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Arazas extends Model
{
    use HasFactory;
    protected $table = 'arazasok';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'berles_ind',
        'vez_perc',
        'kedv_vez',
        'parkolas_perc',
        'foglalasi_perc',
        'kedv_parkolas_perc',
        'napidij',
        'km_dij',
        'repter_felar',
        'repter_felar_terminal',
        'zona_felar',
    ];
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'auto_besorolas', 'kat_id');
    }
    public function elofizetes(): BelongsTo
    {
        return $this->belongsTo(Elofizetes::class, 'elofiz_azon', 'elofiz_id');
    }
}
