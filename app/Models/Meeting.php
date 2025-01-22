<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        //'participants',
        'attachments',
        'photos',

    ];

    protected $casts = [
        //'date' => 'datetime',
        //'participants' => 'array',
        'attachments' => 'array',
        'photos' => 'array',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function participants()
    {
        return $this->belongsToMany(User::class, 'meeting_user', 'meeting_id', 'user_id');
    }
}
