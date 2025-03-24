<?php

namespace App\Filament\Resources\AgreementPaymentResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAgreementPayment extends ViewRecord
{
    protected static string $resource = AgreementPayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
