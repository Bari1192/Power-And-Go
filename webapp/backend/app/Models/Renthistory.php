<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renthistory extends Model
{
    use HasFactory;

    protected $table = 'car_user_rents';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        "id",
        "car_id",
        "auto_kat",
        "person_id",
        "start_percent",
        "start_kw",
        "end_percent",
        "end_kw",
        "rent_start",
        "rent_close",
        "distance",
        "parking_minutes",
        "driving_minutes",
        "rental_cost",
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'auto_kat', 'id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }
}
