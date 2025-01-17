<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'bill_type',
        'total_cost',
        'driving_distance',
        'parking_minutes',
        'driving_minutes',
        'rent_start_date',
        'rent_start_time',
        'rent_end_date',
        'rent_end_time',
        'invoice_date',
        'invoice_status',
    ];
    public function cars(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function persons(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
