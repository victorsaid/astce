<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->color('success')
            ->label('Adicionar Associado'),

            Actions\Action::make('Exportar usuários')
                ->label('Exportar Associados')
                ->icon('fas-file-pdf')
                ->color('danger')
                ->requiresConfirmation()
                ->url(
                    fn(): string => route('pdf.users')
                ),
        ];
    }
    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->icon('fas-users')
                ->badge(User::query()->whereHas('roles', fn($query) => $query->whereNotIn('name', ['Super_admin', 'Admin', 'Employee']))->count())
            ->label('Todos'),
            Tab::make('male')
                ->icon('fas-male')
                ->label('Masculino')
                ->badge(User::query()->where('gender', 'masculino')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'masculino')),
            Tab::make('Female')
                ->label('Feminino')
                ->icon('fas-female')
                ->badge(User::query()->where('gender', 'feminino')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'feminino')),
            Tab::make('Ativos')
                ->label('Ativos')
                ->icon('fas-check-circle')
                ->badge(User::query()->whereHas('associate', fn($query) => $query->where('is_active', true))->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('associate', fn($query) => $query->where('is_active', true))),
            Tab::make('Inativos')
                ->label('Inativos')
                ->icon('fas-circle-xmark')
                ->badge(User::query()->whereHas('associate', fn($query) => $query->where('is_active', false))->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('associate', fn($query) => $query->where('is_active', false))),
        ];
    }
}
