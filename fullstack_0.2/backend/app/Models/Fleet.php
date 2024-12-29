<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fleet extends Model
{
    use HasFactory;
    protected $table = 'fleets';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        "id",
        "gyarto",
        "tipus",
        "hatotav",
        "teljesitmeny",
        "vegsebesseg",
        "gumimeret",
    ];
    public function cars(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'flotta_azon', 'id');
    }
}
