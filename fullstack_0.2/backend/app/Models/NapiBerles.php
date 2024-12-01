<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NapiBerles extends Model
{
    use HasFactory;
    protected $table = 'napi_berlesek';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [];

    public function arazasok(): BelongsTo
    {
        return $this->belongsTo(Arazas::class, 'arazas_id', 'id');
    }
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'auto_tipus', 'kat_id');
    }
}
