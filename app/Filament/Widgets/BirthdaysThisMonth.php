<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BirthdaysThisMonth extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Aniversariantes do Mês';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::whereMonth('birth_date', now()->month))
            ->columns([
                TextColumn::make('name')->label('Nome')->sortable(),
                TextColumn::make('birth_date')->label('Data de Nascimento')->dateTime('d/m/Y'),
                TextColumn::make('email')->label('E-mail')->sortable(),
            ]);
    }
}
