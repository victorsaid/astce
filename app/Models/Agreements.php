<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreements extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'site',
        'category',
        'is_active',
        'phone',
        'email',
        'whatsapp',
    ];

    protected $casts = [
        'photo' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'agreement_user', 'agreement_id', 'user_id');
    }
}
