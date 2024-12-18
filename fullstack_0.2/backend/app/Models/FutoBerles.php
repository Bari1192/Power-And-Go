<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutoBerles extends Model
{
    use HasFactory;
    protected $table = 'futo_berlesek';
    protected $primaryKey = 'id';
    public $timestamps = true; 
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
        return $this->belongsTo(Kategoria::class, 'auto_kategoria', 'kat_id');
    }

    public function felhasznalo()
    {
        return $this->belongsTo(Felhasznalo::class, 'szemely_id_fk', 'szemely_id');
    }
}
