<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps=false;
    public $incrementing = true;

    protected $fillable = [
        "person_id",
        "field",
        "role",
        "position",
        "salary_type",
        "salary",
        "hire_date",
    ];
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
