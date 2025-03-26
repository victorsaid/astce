<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'document',
        'hire_date',
        'salary',
        'position',
        'is_active',
        'commission_member'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
