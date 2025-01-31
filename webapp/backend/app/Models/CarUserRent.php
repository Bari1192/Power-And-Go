<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarUserRent extends Model
{
    public $timestamps = false;
    protected $table = 'car_user_rents';

    public function parkings(): HasMany
    {
        return $this->hasMany(CarUserRentParking::class,'rent_id','id');
    }
}
