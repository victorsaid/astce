<?php

namespace App\Filament\Resources\AssociateResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DependantsRelationManager extends RelationManager
{
    protected static string $relationship = 'dependants';
    protected static ? string $modelLabel = 'Dependente';
    protected static ? string $pluralModelLabel = 'Dependentes';
    protected static ? string $label = 'Dependentes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                    ->required()
                    ->label('Data de Nascimento'),
                Forms\Components\Select::make('relation')
                    ->label('Parentesco')
                    ->options([
                        'Filho'=>'Filho(a)',
                        'Cônjuge'=>'Cônjuge',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome Completo'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Data de Nascimento')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('relation')
                    ->label('Parentesco'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
