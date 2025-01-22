<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'meeting_id',
        'content'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
