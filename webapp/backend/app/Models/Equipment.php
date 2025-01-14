<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;
    protected $table = 'equipments';
    protected $primaryKey = 'id';
    public $timestamps = false; 
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
        return $this->hasMany(Car::class);
    }
}
