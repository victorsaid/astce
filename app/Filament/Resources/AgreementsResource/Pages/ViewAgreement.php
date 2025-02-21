<?php

namespace App\Filament\Resources\AgreementsResource\Pages;

use App\Filament\Resources\AgreementsResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewAgreement extends ViewRecord
{
    protected static string $resource = AgreementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Exportar Conveniados')
                ->icon('fas-file-pdf')
                ->color('danger')
                ->label('Exportar Conveniados')
                ->requiresConfirmation()
                ->url(
                    fn(): string => route('pdf.beneficiariesAgreement', ['agreement' => $this->record->id])
                ),
        ];
    }
}
