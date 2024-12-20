<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'rendszam',
        'toltes_szaz',
        'toltes_kw',
        'becs_tav',
        'status',
        'kategoria',
        'felszereltseg',
        'flotta_azon',
        'kilometerora',
        'gyartasi_ev',
    ];
    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class, 'flotta_azon', 'id');
    }
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategoria', 'kat_besorolas');
    }

    public function carstatus(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class, 'status');
    }

    public function lezartberlesek(): HasMany
    {
        return $this->hasMany(Renthistory::class, 'auto_azonosito', 'id');
    }
    public function szamlak(): HasMany
    {
        return $this->hasMany(Bill::class, 'auto_azon', 'id');
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'car_id', 'id');
    }

    public function berlok(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'car_user_rents', 'car_id', 'user_id')
            ->withPivot([
                'nyitas_szaz',
                'nyitas_kw',
                'zaras_szaz',
                'zaras_kw',
                'berles_kezd_datum',
                'berles_kezd_ido',
                'berles_veg_datum',
                'berles_veg_ido',
                'megtett_tavolsag',
                'parkolas_kezd',
                'parkolas_veg',
                'parkolasi_perc',
                'vezetesi_perc',
                'berles_osszeg',
                'rentstatus'
            ])
            ->with('person'); // Automatikusan töltsük be a `person` relációt is

    }
}
