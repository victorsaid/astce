<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = ['month', 'total'];

    protected $casts = [
        'month' => 'date:Y-m-d',
    ];

    public function payments() {
        return $this->hasMany(PayrollPayment::class);
    }

    public function calculateTotal() {
        $this->total = $this->payments()->sum('amount');
        $this->save();
    }
}
