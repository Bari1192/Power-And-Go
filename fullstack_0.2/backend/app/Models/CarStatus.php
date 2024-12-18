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
        "status_name",
        "status_descrip",
        "created",
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'status', 'id');
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
