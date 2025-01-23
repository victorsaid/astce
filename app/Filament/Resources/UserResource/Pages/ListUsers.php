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
            Actions\CreateAction::make(),

            Actions\Action::make('Exportar usuários')
                ->label('Exportar usuários')
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
            ->label('Todos'),
            Tab::make('male')
                ->icon('fas-male')
                ->label('Masculino')
                ->badge(User::query()->where('gender', 'M')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'M')),
            Tab::make('Female')
                ->label('Feminino')
                ->icon('fas-female')
                ->badge(User::query()->where('gender', 'F')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'F')),
        ];
    }
}
