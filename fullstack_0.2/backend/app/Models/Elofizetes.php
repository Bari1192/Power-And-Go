<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elofizetes extends Model
{
    use HasFactory;
    protected $table = 'elofizetesek';
    protected $primaryKey = 'elofiz_id';
    public $incrementing = true;

    protected $fillable = [
        'elofiz_nev',
        'havi_dij',
        'eves_dij',
    ];
}
