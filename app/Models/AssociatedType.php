<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociatedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'abble_vote',
    ];

    public function associates()
    {
        return $this->hasMany(Associate::class);
    }
}
