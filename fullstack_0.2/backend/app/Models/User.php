<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id',
        'szemely_id',
        'felh_egyenleg',
        'felh_nev',
        'jelszo_2_4',
        'elofiz_id',
    ];

    public function szemely(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'szemely_id');
    }

    public function elofizetes(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'elofiz_id');
    }
}
