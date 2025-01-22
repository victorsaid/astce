<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('Todos')
                ->badge(Employee::query()->count()),
            Tab::make('Actives')
                ->label('Ativos')
                ->badge(Employee::query()->where('is_active', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'M')),
            Tab::make('Inactives')
                ->label('Inativos')
                ->badge(Employee::query()->where('is_active', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'F')),
        ];
    }
}
