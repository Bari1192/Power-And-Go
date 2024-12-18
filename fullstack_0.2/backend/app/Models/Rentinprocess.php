<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rentinprocess extends Model
{
    use HasFactory;
    protected $table = 'rentsinprocess';
    protected $primaryKey = 'id';
    public $timestamps = false; 
    public $incrementing = true;

    protected $fillable = [
        'rendszam',
        'auto_kategoria',
    ];
    public function auto()
    {
        return $this->belongsTo(Car::class, 'auto_azon');
    }

    public function kategoriak()
    {
        return $this->belongsTo(Category::class, 'kategoria', 'id');
    }

    public function felhasznalo()
    {
        return $this->belongsTo(User::class, 'szemely_id', 'id');
    }
}
