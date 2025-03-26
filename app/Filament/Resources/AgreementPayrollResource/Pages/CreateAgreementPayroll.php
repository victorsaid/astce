<?php

namespace App\Filament\Resources\AgreementPayrollResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use App\Models\AgreementPayroll;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreementPayroll extends CreateRecord
{
    protected static string $resource = AgreementPayrollResource::class;


}
