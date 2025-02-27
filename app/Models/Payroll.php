<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'total', 'name'];

    protected $casts = [
        'date' => 'date:d-m-Y',
    ];

    public function payments() {
        return $this->hasMany(PayrollPayment::class);
    }

}
