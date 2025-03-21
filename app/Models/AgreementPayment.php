<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agreement_id',
        'value',
        'payment_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agreement()
    {
        return $this->belongsTo(Agreements::class);
    }
}
