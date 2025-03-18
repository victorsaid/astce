<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                //->label('Editar')
                ->icon('fas-user-edit'),

            Actions\ActionGroup::make([
                Actions\Action::make('Declaração de Associado')
                    ->label('Declaração de Associado')
                    ->icon('fas-file-pdf')
                    ->color('success')
                    ->requiresConfirmation()
                    ->url(
                        fn(): string => route('pdf.memberDeclaration', ['user' => $this->record->id])
                    ),
                Actions\Action::make('Declaração de Dependentes')
                    ->label('Declaração de Dependentes')
                    ->icon('fas-file-pdf')
                    ->color('danger')
                    ->form([
                        TextInput::make('dependant')
                        ->label('Nome do(s) dependente(s)')
                        ->required(),
                    ])
                    ->action( function (array $data){
                                return redirect()->route('pdf.memberDependantsDeclaration', [
                                    'user' => $this->record->id,
                                    'dependant' => $data['dependant'],
                                ]);
                            }
                    ),

            ])
                ->label('Mais Ações') // Nome do grupo de ações
                ->icon('fas-ellipsis-vertical') // Ícone do botão
                ->color('primary'), // Cor do botão principal
        ];
    }
}
