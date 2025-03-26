<?php

namespace App\Filament\Resources\AgreementPayrollResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgreementPayroll extends ListRecords
{
    protected static string $resource = AgreementPayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
