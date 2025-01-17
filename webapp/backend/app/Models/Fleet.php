<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    use HasFactory;
    protected $table = 'fleets';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        "id",
        "manufacturer",
        "carmodel",
        "driving_range",
        "motor_power",
        "top_speed",
        "tire_size",
    ];
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'fleet_id', 'id');
    }
}
