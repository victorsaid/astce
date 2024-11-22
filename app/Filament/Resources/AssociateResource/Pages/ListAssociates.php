<?php

namespace App\Filament\Resources\AssociateResource\Pages;

use App\Filament\Resources\AssociateResource;
use App\Models\Associate;
use App\Models\Employee;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAssociates extends ListRecords
{
    protected static string $resource = AssociateResource::class;

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
                ->label('Todos'),
            Tab::make('Actives')
                ->label('Ativos')
                ->badge(Associate::query()->where('is_active', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'M')),
            Tab::make('Inactives')
                ->label('Inativos')
                ->badge(Associate::query()->where('is_active', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'F')),
            Tab::make('Employees')
                ->label('Funcionários')
                ->badge(Employee::query()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where instanceof Employee),
        ];
    }
}
