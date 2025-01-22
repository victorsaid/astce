<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependant extends Model
{
    use HasFactory;

    protected $fillable = [
        'associate_id',
        'name',
        'birth_date',
        'relation',
    ];

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }
}
