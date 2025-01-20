<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table="tickets";
    public $incrementing = true;

    public $fillable = [
        'car_id',
        'status_id',
        'description',
        'created_at',
        'status_descrip',
    ];
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class);
    }
}
