<?php

namespace App\Filament\Resources\AgreementResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class AgreementUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users'; // Nome do relacionamento no modelo Agreement

    protected static ?string $title = 'Beneficiários';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('associate.enrollment')
                    ->label('Matrícula')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-mail'),

                TextColumn::make('document')
                    ->label('CPF')

                    ->formatStateUsing(fn ($state) =>
                        substr($state, 0, 3) . '.' .
                        substr($state, 3, 3) . '.' .
                        substr($state, 6, 3) . '-' .
                        substr($state, 9, 2)
                    ),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
