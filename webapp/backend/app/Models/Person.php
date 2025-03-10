<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'person_password',
        'id_card',
        'driving_license',
        'license_start_date',
        'license_end_date',
        'firstname',
        'lastname',
        'birth_date',
        'phone',
        'email',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function dolgozo()
    {
        return $this->hasOne(Employee::class, 'person_id', 'id');
    }
}
