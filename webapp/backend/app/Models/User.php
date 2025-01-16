<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;


class User extends Model implements AuthenticatableContract
{
    use HasFactory, HasApiTokens, Notifiable, Authenticatable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id',
        'person_id',
        'felh_egyenleg',
        'user_name',
        'jelszo_2_4',
        'elofiz_id',
        'password',
    ];

    # JSON válaszból elrejtve!
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'elofiz_id');
    }

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_user_rents', 'user_id', 'car_id')
            ->withPivot([
                'id',
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
                'rentstatus',
                'szamla_kelt',
            ])
            ->as('rent_details');
    }
}