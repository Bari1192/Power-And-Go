<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elofizetes extends Model
{
    use HasFactory;
    protected $table = 'elofizetesek';
    protected $primaryKey = 'elofiz_id';
    public $timestamps = true; 
    public $incrementing = true;

    protected $fillable = [
    ];
}
