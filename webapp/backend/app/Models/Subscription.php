<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'id',
        'sub_name',
        'sub_monthly',
        'sub_annual',
    ];
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'sub_id', 'id');
    }
}
