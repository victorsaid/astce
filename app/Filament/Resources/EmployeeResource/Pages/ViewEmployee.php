<?php

namespace App\Filament\Resources\FuncTesteResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                //->label('Editar')
                ->icon('fas-user-edit'),

            Actions\ActionGroup::make([
//                Actions\Action::make('Exportar PDF')
//                    ->label('Exportar PDF')
//                    ->icon('fas-file-pdf')
//                    ->color('danger')
//                    ->requiresConfirmation()
//                    ->action(function () {
//                        return redirect()->route('pdf.employees');
//                    }),
//                Actions\Action::make('Declaração de Funcionário')
//                    ->label('Declaração de Funcionário')
//                    ->icon('fas-file-pdf')
//                    ->color('success')
//                    ->requiresConfirmation()
//                    ->url(
//                        fn(): string => route('pdf.employeeDeclaration', ['user' => $this->record->id])
//                    ),
            ])
                ->label('Mais Ações') // Nome do grupo de ações
                ->icon('fas-ellipsis-vertical') // Ícone do botão
                ->color('primary'), // Cor do botão principal
        ];
    }
}
