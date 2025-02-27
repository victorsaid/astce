<?php

namespace App\Filament\Resources\PayrollResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Payroll;
use App\Models\PayrollPayment;

class PayrollStatus extends BaseWidget
{
    protected function getStats(): array
    {
        $lastPayroll = Payroll::latest('date')->first();

        //$totalPayments = $lastPayroll ? PayrollPayment::where('payroll_id', $lastPayroll->id)->sum('amount') : 0;
        $totalPayments = Payroll::all()->sum('total');

        return [
            Card::make('Última Folha de Pagamento', $lastPayroll ? 'R$ ' . number_format($lastPayroll->total, 2, ',', '.') : 'Nenhum registro')
                ->description($lastPayroll ? 'Data: ' . $lastPayroll->date->format('d/m/Y') : 'Nenhuma folha encontrada')
                ->color($lastPayroll ? 'success' : 'danger'),

            Card::make('Total de Pagamentos', 'R$ ' . number_format($totalPayments, 2, ',', '.'))
                ->description('Total em pagamentos')
                ->color($totalPayments > 0 ? 'info' : 'warning'),
        ];
    }
}
