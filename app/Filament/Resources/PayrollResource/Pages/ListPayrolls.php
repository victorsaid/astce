<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Filament\Resources\PayrollResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;



    protected function getHeaderWidgets(): array
    {
        return [
            PayrollResource\Widgets\PayrollHeaderWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            PayrollResource\Widgets\PayrollFooterWidget::class,
        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

//            Actions\ActionGroup::make([
//
//                Actions\Action::make('Exportar usuários')
//                    ->label('Exportar Associados')
//                    ->icon('fas-file-pdf')
//                    ->color('danger')
//                    ->form([
//                        Select::make('order_by')
//                            ->label('Ordenar por')
//                            ->options([
//                                'name' => 'Nome (A-Z)',
//                                'enrollment' => 'Matrícula (Menor para Maior)',
//                            ])
//                            ->default('name')
//                            ->required(),
//
//                        Select::make('only_active')
//                            ->label('Filtrar por')
//                            ->options([
//                                '1' => 'Apenas Associados Ativos',
//                                '0' => 'Todos os Associados',
//                            ])
//                            ->default('1')
//                            ->required(),
//                    ])
//                    ->action(function (array $data) {
//                        return redirect()->route('pdf.users', [
//                            'order_by' => $data['order_by'],
//                            'only_active' => $data['only_active'],
//                        ]);
//                    }),
//            ])
//                ->label('Mais Ações') // Nome do grupo de ações
//                ->icon('fas-ellipsis-vertical') // Ícone do botão
//                ->color('primary'), // Cor do botão principal
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
