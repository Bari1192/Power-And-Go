<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;
    protected $table = 'prices';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [];
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'auto_besorolas', 'kat_besorolas');
    }

    public function elofizetes(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'elofiz_azon', 'elofiz_id');
    }
}
