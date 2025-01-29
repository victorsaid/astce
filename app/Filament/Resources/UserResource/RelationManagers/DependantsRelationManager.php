<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

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
    protected static ?string $title = 'Dependentes';
    protected static ?string $icon = 'fas-children';
    protected static ? string $modelLabel = 'Dependente';
    protected static ? string $pluralModelLabel = 'Dependentes';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome')
                    ->maxLength(255),
                Forms\Components\Select::make('relation')
                    ->required()
                    ->label('Parentesco')
                    ->options([
                        'Conjuge' => 'Conjuge',
                        'Filho(a)' => 'Filho(a)',
                    ])
                ,
                Forms\Components\Datepicker::make('birth_date')
                    ->required()
                    ->label('Data de Nascimento')
                    ->date('d/m/Y'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('relation')
                    ->label('Parentesco'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Data de Nascimento')
                    ->date('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Adicionar Dependente'),
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
