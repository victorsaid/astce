<?php

namespace App\Filament\Resources\AgreementPaymentResource\Pages;

use App\Filament\Resources\AgreementPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgreementPayment extends EditRecord
{
    protected static string $resource = AgreementPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
