<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategoria extends Model
{
    use HasFactory;

    protected $table = 'kategoriak';
    protected $primaryKey = 'kat_id';

    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [];


    public function autok(): HasMany
    {
        return $this->hasMany(Auto::class, 'kategoria_besorolas_fk', 'kategoria_besorolas');
    }
    public function arazasok(): HasMany
    {
        return $this->hasMany(Arazas::class, 'auto_besorolas', 'kat_id');
    }
    public function napiBerlesek(): HasMany
    {
        return $this->hasMany(NapiBerles::class, 'auto_tipus', 'kat_id');
    }
}
