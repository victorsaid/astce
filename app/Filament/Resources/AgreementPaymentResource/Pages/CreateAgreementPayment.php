<?php

namespace App\Filament\Resources\AgreementPaymentResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use App\Models\AgreementPayment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreementPayment extends CreateRecord
{
    protected static string $resource = AgreementPayrollResource::class;


}
