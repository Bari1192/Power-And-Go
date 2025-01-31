<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarUserRentParking extends Model
{
    use HasFactory;
    protected $table = 'car_user_rent_parkings';
    public $timestamps=false;

    public $fillable = [
        'rent_id',
        'parking_start',
        'parking_end',
        'parking_minutes',
    ];
    public function car_user_rents(): BelongsTo
    {
        return $this->belongsTo(CarUserRent::class, 'rent_id', 'id');
    }
}
