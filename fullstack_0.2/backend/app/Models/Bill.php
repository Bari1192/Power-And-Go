<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps=false;

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
    public function autok(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'auto_azon');
    }
}
