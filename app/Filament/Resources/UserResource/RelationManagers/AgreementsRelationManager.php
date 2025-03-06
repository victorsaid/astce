<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use App\Models\Agreements;

class AgreementsRelationManager extends RelationManager
{
    protected static string $relationship = 'agreements';

    protected static ?string $title = 'Convênios';
    protected static ?string $icon = 'fas-tree-city';

    protected static ? string $modelLabel = 'Convênio';
    protected static ? string $pluralModelLabel = 'Convênios';

//    public function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\Select::make('name')
//                    ->required()
//                    ->label('Convênio')
//                    ->
//            ]);
//    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Convênio')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('site')
                    ->label('Site')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Filtrar por Tipo')
                    ->options(Agreements::query()->pluck('category', 'category')->toArray()),
            ])
            ->headerActions([
                AttachAction::make()
                    ->multiple()
                    ->label('Adicionar Convênio')
                    ->color('primary')
                    ->preloadRecordSelect() // Carrega os convênios no select ao abrir o modal
                    ->recordSelectSearchColumns(['name', 'description'])
                    ->recordSelectOptionsQuery(
                        fn (Builder $query) => $query->where('is_active', true) // Apenas convênios ativos
                    )
                    ->recordTitle(fn (Agreements $record) => $record->name),
            ])
            ->actions([
                DetachAction::make()->label('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ])
            ->defaultSort('name');
    }
}
