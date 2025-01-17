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
    protected $fillable = [
        "category_class",
        "motor_power",
    ];


    public function autok(): HasMany
    {
        return $this->hasMany(Car::class, 'category_id', 'category_class');
    }
    public function arazasok(): HasMany
    {
        return $this->hasMany(Price::class, 'category_class', 'id');
    }
    public function napiBerlesek(): HasMany
    {
        return $this->hasMany(Dailyrental::class, 'category_class', 'id');
    }
}
