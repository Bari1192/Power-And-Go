<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    use HasFactory;
    protected $table = 'fleets';
    protected $primaryKey = 'id';
    public $timestamps = false; 
    public $incrementing = true;

    protected $fillable = [
        'id',
        'gyarto',
        'tipus',
        'hatotav',
        'teljesitmeny',
        'vegsebesseg',
        'gumimeret',
    ];
    public function autok(): HasMany
    {
        return $this->hasMany(Car::class, 'flotta_azon', 'id');
    }
}
