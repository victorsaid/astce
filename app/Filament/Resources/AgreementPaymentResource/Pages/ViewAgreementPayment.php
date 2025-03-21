<?php

namespace App\Filament\Resources\AgreementPaymentResource\Pages;

use App\Filament\Resources\AgreementPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAgreementPayment extends ViewRecord
{
    protected static string $resource = AgreementPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
