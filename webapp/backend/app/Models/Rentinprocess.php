<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rentinprocess extends Model
{
    use HasFactory;
    protected $table = 'car_user_rents';
    protected $primaryKey = 'id';
    public $timestamps = false; 
    public $incrementing = true;

    protected $fillable = [
        'plate',
        'category_id',
    ];
    public function auto()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'person_id', 'id');
    }
}
