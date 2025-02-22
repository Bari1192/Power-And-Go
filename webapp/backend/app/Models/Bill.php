<?php

namespace App\Models;

use Carbon\Carbon;
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
        'id',
        'rent_id',
        'bill_type',
        'user_id',
        'person_id',
        'car_id',
        'rent_start',
        'rent_close',
        'distance',
        'parking_minutes',
        'driving_minutes',
        'total_cost',
        'credits',
        'charged_kw',
        'invoice_date',
        'invoice_status'
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
    public function getRentStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getRentCloseAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
