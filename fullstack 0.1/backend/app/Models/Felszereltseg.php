<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Felszereltseg extends Model
{
    use HasFactory;
    protected $table = 'felszereltsegek';
    protected $primaryKey = 'felsz_id';
    public $incrementing = true;

    protected $fillable = [
        'tolatokamera',
        'savtarto',
        'tempomat',
        'tolatoradar',
        'multif_kormany',
    ];
    public function autok(): HasMany
    {
        return $this->hasMany(Auto::class, 'felsz_id_fk');
        # Nem kell kifejteni [PK] -> [FK], automatán látja, hogy a felsz_id-ról megy az FK.
    }
}
