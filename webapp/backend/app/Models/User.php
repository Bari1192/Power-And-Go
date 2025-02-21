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
        'account_balance',
        'user_name',
        'password_2_4',
        'sub_id',
        'password',
    ];

    # JSON válaszból elrejtve!
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class,'person_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'sub_id');
    }

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_user_rents', 'user_id', 'car_id')
            ->withPivot([
                'start_percent',
                'start_kw',
                'end_percent',
                'end_kw',
                'rent_start',
                'rent_close',
                'distance',
                'parking_minutes',
                'driving_minutes',
                'rental_cost',
                'rentstatus',
                'invoice_date',
            ])
            ->as('rent_details');
    }
}
