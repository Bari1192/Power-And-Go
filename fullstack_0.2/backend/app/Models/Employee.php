<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'dolgozok';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'terulet',
        'munkakor',
        'beosztas',
        'munkaido',
        'fizetes_ossz',
        'belepes_datum',
    ];
    public function szemely(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'szemely_azon');
    }
}
