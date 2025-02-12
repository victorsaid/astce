<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'associate_id',
        'start_date',
        'end_date',
    ];

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }
}
