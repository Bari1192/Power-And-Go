<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';

    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [];


    public function autok(): HasMany
    {
        return $this->hasMany(Car::class, 'kategoria', 'kat_besorolas');
    }
    public function arazasok(): HasMany
    {
        return $this->hasMany(Price::class, 'auto_besorolas', 'id');
    }
    public function napiBerlesek(): HasMany
    {
        return $this->hasMany(Dailyrental::class, 'auto_tipus', 'id');
    }
}
