<?php

namespace App\Filament\Resources\PayrollResource\Widgets;

namespace App\Filament\Resources\PayrollResource\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Payroll;
use App\Models\PayrollPayment;
use App\Models\User;
use App\Models\Associate;

class PayrollFooterWidget extends ChartWidget
{
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Folhas de Pagamento Mensais';
    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $payrolls = Payroll::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $payrolls->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total da Folha',
                    'data' => $payrolls->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ]
            ],
        ];
    }
}
