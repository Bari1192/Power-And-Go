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
        'reversing_camera',
        'lane_keep_assist',
        'adaptive_cruise_control',
        'parking_sensors',
        'multifunction_wheel',
    ];
    public function autok(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
