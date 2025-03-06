<?php

namespace App\Filament\Resources\PayrollResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Payroll;
use App\Models\PayrollPayment;

class PayrollHeaderWidget extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static int $columns = 4;

    protected function getStats(): array
    {
        $lastPayroll = Payroll::latest('date')->first();
        $firstPayroll = Payroll::oldest('date')->first();
        $totalYear = Payroll::whereYear('date', now()->year)->sum('total');
        $totalPayments = Payroll::sum('total');

        $paymentsByType = PayrollPayment::where('payroll_payments.payroll_id', $lastPayroll->id ?? null)
            ->join('associates', 'payroll_payments.user_id', '=', 'associates.user_id') // 🔹 Pulamos a tabela "users"
            ->selectRaw('associates.associated_type_id AS type_id, SUM(payroll_payments.amount) as total')
            ->groupBy('associates.associated_type_id')
            ->orderBy('type_id')
            ->get()
            ->pluck('total', 'type_id');


        $types = [
            1 => 'Efetivos',
            2 => 'Comissionado',
            3 => 'Aposentado',
            4 => 'À disposição',
        ];

        $paymentSummary = collect($types)->map(function ($label, $id) use ($paymentsByType) {
            return "$label: R$ " . number_format($paymentsByType->get($id, 0), 2, ',', '.');
        })->implode(" | ");

        return [
            Card::make('Última Folha de Pagamento', $lastPayroll ? 'R$ ' . number_format($lastPayroll->total, 2, ',', '.') : 'Nenhum registro')
                ->description($lastPayroll ? 'Mês: ' . $lastPayroll->date->translatedFormat('F Y') . " | " . $paymentSummary : 'Nenhuma folha encontrada')
                ->color($lastPayroll ? 'success' : 'danger'),
            Card::make('Total Arrecadado em ' . now()->year, 'R$ ' . number_format($totalYear, 2, ',', '.'))
                ->description('Total arrecadado no ano atual')
                ->color($totalYear > 0 ? 'info' : 'warning'),

            Card::make('Valor Total Arrecadado', 'R$ ' . number_format($totalPayments, 2, ',', '.'))
                ->description($firstPayroll && $lastPayroll
                    ? 'De ' . $firstPayroll->date->format('d/m/Y') . ' até ' . $lastPayroll->date->format('d/m/Y')
                    : 'Nenhum registro encontrado')
                ->color($totalPayments > 0 ? 'primary' : 'secondary'),
        ];
    }
}



