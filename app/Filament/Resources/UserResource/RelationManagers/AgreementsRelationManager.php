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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Convênio')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Filtrar por Tipo')
                    ->options(Agreements::query()->pluck('type', 'type')->toArray()),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Selecionar Convênio')
                    ->preloadRecordSelect() // Carrega os convênios no select ao abrir o modal
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->where('is_active', true))
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
