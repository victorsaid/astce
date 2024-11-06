<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
    protected $fillable = ['ddd', 'number', 'type', 'observation'];

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }
}
