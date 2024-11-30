<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutoBerles extends Model
{
    use HasFactory;
    protected $table = 'futo_berlesek';
    protected $primaryKey = 'futo_id';
    public $incrementing = true;

    protected $fillable = [
        'rendszam',
        'auto_kategoria',
    ];
    public function auto()
    {
        return $this->belongsTo(Auto::class, 'auto_azonosito', 'autok_id');
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
