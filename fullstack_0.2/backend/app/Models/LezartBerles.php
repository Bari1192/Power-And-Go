<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LezartBerles extends Model
{
    use HasFactory;

    protected $table = 'lezart_berlesek';
    protected $primaryKey = 'lezart_berles_id';
    
    public $incrementing = true;
    protected $fillable = [];

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
        return $this->belongsTo(Felhasznalo::class, 'szemely_id_fk', 'szemely_id'); // FK helyes neve
    }
}
