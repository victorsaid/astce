<?php

namespace App\Filament\Resources\AgreementPaymentResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgreementPayment extends EditRecord
{
    protected static string $resource = AgreementPayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
