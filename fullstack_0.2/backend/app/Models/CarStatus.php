<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarStatus extends Model
{
    use HasFactory;
    protected $table = 'carstatus';

    protected $fillable = [
        'status_name',
        'status_descrip',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Auto::class, 'status', 'carstatus_id');
    }
}
