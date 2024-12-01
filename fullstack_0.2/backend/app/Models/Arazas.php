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
    public $timestamps = true; 
    public $incrementing = true;

    protected $fillable = [];
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'auto_besorolas', 'kat_id');
    }
    public function elofizetes(): BelongsTo
    {
        return $this->belongsTo(Elofizetes::class, 'elofiz_id', 'elofiz_id');
    }
    public function napiBerlesek():HasMany{
        return $this->hasMany(NapiBerles::class,'arazas_id','id');
    }
}
