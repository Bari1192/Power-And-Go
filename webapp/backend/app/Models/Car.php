<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'plate',
        'power_percent',
        'power_kw',
        'estimated_range',
        'status',
        'category_id',
        'equipment_class',
        'fleet_id',
        'odometer',
        'manufacturing_year',
    ];
    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }
    public function category_id(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_class');
    }

    public function carstatus(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class, 'status');
    }

    public function lezartberlesek(): HasMany
    {
        return $this->hasMany(Renthistory::class, 'car_id');
    }
    public function szamlak(): HasMany
    {
        return $this->hasMany(Bill::class, 'car_id');
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'car_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'car_user_rents', 'car_id', 'user_id')
            ->withPivot([
                'id',
                'start_percent',
                'start_kw',
                'end_percent',
                'end_kw',
                'rent_start_date',
                'rent_start_time',
                'rent_end_date',
                'rent_end_time',
                'driving_distance',
                'parking_start',
                'parking_end',
                'parking_minutes',
                'driving_minutes',
                'rental_cost',
                'rentstatus',
                'invoice_date',
            ])
            ->as('rent_details');
    }
}
