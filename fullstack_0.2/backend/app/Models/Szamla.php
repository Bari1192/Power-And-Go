<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szamla extends Model
{
    use HasFactory;
    protected $table='szamlak';
    protected $primaryKey='szamla_id';
    public $incrementing=true;
    protected $fillable=[];
}
