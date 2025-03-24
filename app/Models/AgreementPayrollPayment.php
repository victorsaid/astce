<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementPayrollPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_payroll_id',
        'user_id',
        'amount',
    ];

    public function payroll() {
        return $this->belongsTo(AgreementPayroll::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
