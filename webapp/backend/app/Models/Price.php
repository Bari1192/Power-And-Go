<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;
    protected $table = 'prices';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'sub_id',
        'category_class',
        'rental_start',
        'driving_minutes',
        'discounted_driving',
        'parking_minutes',
        'reserv_minutes',
        'disc_parking_minutes',
        'daily_fee',
        'daily_km_limit',
        'km_fee',
        'airport_out_fee',
        'airport_in_fee',
        'airport_out_terminal_fee',
        'airport_in_terminal_fee',
        'zone_opening_fee',
        'zone_closing_fee',
        'three_hour_fee',
        'six_hour_fee',
        'twelve_hour_fee',
        'weekend_daily_fee',
    ];
    public function category_id(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_class', 'category_class');
    }

    public function elofizetes(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'sub_id', 'sub_id');
    }
}
