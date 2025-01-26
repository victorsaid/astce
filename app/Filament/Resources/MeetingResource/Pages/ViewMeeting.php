<?php

namespace App\Filament\Resources\MeetingResource\Pages;

use App\Filament\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewMeeting extends ViewRecord
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Exportar Reunião')
                ->icon('fas-file-pdf')
                ->color('danger')
                ->label('Exportar')
                ->requiresConfirmation()
                ->url(
                    fn(): string => route('pdf.export', ['meeting' => $this->record->id])
                ),
        ];
    }
}
