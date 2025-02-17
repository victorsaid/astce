<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssociateTotal extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Associados', User::whereHas('associate')->count())
            ->description('Total de associados ativos')
            ->icon('heroicon-o-users')
            ->color('success'),




        ];
    }
}
