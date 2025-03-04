<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusMinutesTransaction extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = "int";
    protected $table = 'bonus_minutes_transactions';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'amount',
        'source',
        'type',
        'reason',
    ];
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
