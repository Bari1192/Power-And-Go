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
    public $incrementing = true;
    protected $guarded = ['szemely_id_FK'];
    
    protected $fillable = [
        'felh_egyenleg',
        'jelszo_2_4',
        'felh_nev',
        'elofiz_kat',
    ];
    public function szemely(): BelongsTo
    {
        return $this->belongsTo(Szemely::class, 'szemely_id', 'szemely_id'); 
    }
}
