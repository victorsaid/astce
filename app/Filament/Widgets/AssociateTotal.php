<?php

namespace App\Filament\Widgets;

use App\Models\Agreements;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssociateTotal extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Associados', User::whereHas('associate', function ($query){
                $query->where('is_active', 1)->limit(5);
            })->count())
            ->description('Associados ativos')
            ->icon('heroicon-o-users')
            ->color('success'),

            Stat::make('Convênios', Agreements::where('is_active', 1)->count())
            ->description('Convênios Ativos')
            ->icon('fas-tree-city')
            ->color('success'),

        ];
    }
}
