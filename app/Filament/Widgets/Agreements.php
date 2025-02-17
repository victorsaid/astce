<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class Agreements extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Últimos Convênios';
    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Agreements::query()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Cadastrado em')->dateTime('d/m/Y H:i'),
            ]);
    }
}
