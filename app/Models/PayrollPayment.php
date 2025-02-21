<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPayment extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_id', 'user_id', 'amount'];

    public function payroll() {
        return $this->belongsTo(Payroll::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
