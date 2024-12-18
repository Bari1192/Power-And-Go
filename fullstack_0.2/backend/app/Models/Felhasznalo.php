<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Felhasznalo extends Model
{
    use HasFactory;

    protected $table = 'felhasznalok';
    protected $primaryKey = 'felh_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'felh_id',
        'szemely_id',
        'felh_egyenleg',
        'felh_nev',
        'jelszo_2_4',
        'elofiz_id',
    ];

    public function szemely(): BelongsTo
    {
        return $this->belongsTo(Szemely::class, 'szemely_id', 'szemely_id');
    }

    public function elofizetes(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'elofiz_id', 'id');
    }
}
