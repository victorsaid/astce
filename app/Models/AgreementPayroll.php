<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'agreement_id',
        'total',
        'date',
        'value'
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreements::class);
    }
    public function payments() {
        return $this->hasMany(AgreementPayrollPayment::class);
    }
}
