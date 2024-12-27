<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table="tickets";
    public $incrementing = true;
    public $timestamps = false; 

    public $fillable = [
        'car_id',
        'status_id',
        'description',
        'szamla_kelt',
    ];
    public function auto(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class);
    }
}
