<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flotta_tipusok extends Model
{
    use HasFactory;
    protected $table = 'flotta_tipusok';
    protected $primaryKey = 'flotta_id';
    public $timestamps = true; 
    public $incrementing = true;

    protected $fillable = [
        'gyarto',
        'tipus',
        'teljesitmeny',
        'vegsebesseg',
        'gumimeret',
        'hatotav',
    ];
    public function autok(): HasMany
    {
        return $this->hasMany(Auto::class, 'flotta_id_fk', 'flotta_id');
    }
}
