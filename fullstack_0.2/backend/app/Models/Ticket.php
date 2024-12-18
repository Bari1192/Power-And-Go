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


    public $fillable = [
        'id',
        'description',
        'car_id',
        'status_id',
        'created_at',
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
