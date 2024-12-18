<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dolgozo extends Model
{
    use HasFactory;
    protected $table = 'dolgozok';
    protected $primaryKey = 'dolgozo_id';
    public $timestamps = true;
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
        return $this->belongsTo(Person::class, 'szemely_id_fk', 'id');
    }
}
