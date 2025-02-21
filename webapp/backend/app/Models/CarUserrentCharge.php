<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarUserrentCharge extends Model
{
    use HasFactory;
    protected $table = "car_userrent_charges";
    public $timestamps = false;

    public $fillable = [
        'rent_id',
        'charging_start_date',
        'charging_end_date',
        'start_percent',
        'end_percent',
        'start_kw',
        'end_kw',
        'charged_kw',
        'credits',
    ];
    public function car_user_rent(): BelongsTo
    {
        return $this->belongsTo(CarUserRent::class, 'rent_id', 'id');
    }
}
