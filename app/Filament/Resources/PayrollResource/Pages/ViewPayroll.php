<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Filament\Resources\PayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\ActionGroup::make([
                Actions\Action::make('Exportar Folha de Pagamento')
                    ->label('Exportar Folha de Pagamento')
                    ->icon('fas-file-pdf')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        return redirect()->route('pdf.payrollExport', ['payroll' => $this->record->id]);
                    }),
                Actions\Action::make('Declaração de Associado')
                    ->label('Declaração de Associado')
                    ->icon('fas-file-pdf')
                    ->color('success')
                    ->requiresConfirmation()
                    ->url(
                        fn(): string => route('pdf.memberDeclaration', ['user' => $this->record->id])
                    ),
            ])
                ->label('Mais Ações') // Nome do grupo de ações
                ->icon('fas-ellipsis-vertical') // Ícone do botão
                ->color('primary'), // Cor do botão principal
        ];
    }
}
