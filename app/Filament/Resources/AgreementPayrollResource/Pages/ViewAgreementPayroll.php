<?php

namespace App\Filament\Resources\AgreementPayrollResource\Pages;

use App\Filament\Resources\AgreementPayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAgreementPayroll extends ViewRecord
{
    protected static string $resource = AgreementPayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\ActionGroup::make([
                Actions\Action::make('Exportar Folha')
                    ->label('Exportar Folha')
                    ->icon('fas-file-pdf')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\Select::make('order_by')
                            ->label('Ordenar por')
                            ->options([
                                'name' => 'Nome (Ordem Alfabética)',
                                'enrollment' => 'Matrícula',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('pdf.payrollAgreementExport', [
                            'payroll' => $this->record->id,
                            'order_by' => $data['order_by'],
                        ]);
                    }),
            ])
                ->label('Mais Ações') // Nome do grupo de ações
                ->icon('fas-ellipsis-vertical') // Ícone do botão
                ->color('primary'), // Cor do botão principal


        ];
    }
}
