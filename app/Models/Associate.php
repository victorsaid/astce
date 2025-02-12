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
        'associated_type_id',
        'association_date',
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
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function associated_type()
    {
        return $this->belongsTo(AssociatedType::class);
    }

    public function dependants()
    {
        return $this->hasMany(Dependant::class);
    }

    public function associationPeriods()
    {
        return $this->hasMany(AssociationPeriod::class);
    }

}
