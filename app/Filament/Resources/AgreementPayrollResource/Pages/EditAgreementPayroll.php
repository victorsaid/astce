<?php

namespace App\Filament\Resources\AgreementPayrollResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgreementPayroll extends EditRecord
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
