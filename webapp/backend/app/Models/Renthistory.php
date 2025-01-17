<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renthistory extends Model
{
    use HasFactory;

    protected $table = 'renthistories';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        "id",
        "auto_azon",
        "auto_kat",
        "person_id",
        "start_percent",
        "start_kw",
        "end_percent",
        "end_kw",
        "rent_start_date",
        "rent_start_time",
        "rent_end_date",
        "rent_end_time",
        "driving_distance",
        "parking_start",
        "parking_end",
        "parking_minutes",
        "driving_minutes",
        "rental_cost",
    ];

    public function auto()
    {
        return $this->belongsTo(Car::class, 'auto_azon', 'id');
    }

    public function kategoriak()
    {
        return $this->belongsTo(Category::class, 'auto_kat', 'id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }
}
