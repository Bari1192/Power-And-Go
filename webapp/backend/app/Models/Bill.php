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
        "id",
        "user_id",
        "person_id",
        "car_id",
        "rent_start",
        "rent_close",
        "distance",
        "parking_minutes",
        "driving_minutes",
        "total_cost",
        "bill_type",
        "invoice_date",
        "invoice_status",
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
