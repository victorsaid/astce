<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class Users extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Últimos Associados';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::whereHas('associate')->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Cadastrado em')->dateTime('d/m/Y H:i'),
            ]);
    }
}
