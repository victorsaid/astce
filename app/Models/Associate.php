<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associate extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'phone_id',
        'position_id',
        'name',
        'photo',
        'gender',
        'birth_date',
        'blood_type',
        'marital_status',
        'document',
        'education_level',
        'enrollment',
        'is_active',
    ];

    protected $casts = [
        'photo' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

}
