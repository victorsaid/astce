<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssociatedTypeResource\Pages;
use App\Filament\Resources\AssociatedTypeResource\RelationManagers;
use App\Models\AssociatedType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssociatedTypeResource extends Resource
{
    protected static ?string $model = AssociatedType::class;
    protected static ?string $navigationGroup = 'Cadastros auxiliares';
    protected static ?string $modelLabel = 'Tipos de associados';
    protected static ?string $pluralModelLabel = 'Tipos de Associados';
    protected static ?string $pluralLabel = 'Tipos de associados';
    protected static ?string $navigationLabel = 'Tipos de Associados';
    protected static ?string $navigationIcon = 'fas-boxes';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\MarkdownEditor::make('description')
                    ->label('Descrição')
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('abble_vote')
                    ->required()
                    ->label('Este tipo de usuário pode votar?')
                    ->default(true)
                    ->inline()
                    ->options([
                        '0' => 'Não',
                        '1' => 'Sim',
                    ])
                    ->icons([
                        '0' => 'heroicon-o-x-mark',
                        '1' => 'heroicon-o-check',
                    ])
                    ->colors([
                        '0' => 'danger',
                        '1' => 'success',
                    ])
                ,
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Descrição'),
                Tables\Columns\IconColumn::make('abble_vote')
                    ->boolean()
                    ->label('Pode votar?'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssociatedTypes::route('/'),
            'create' => Pages\CreateAssociatedType::route('/create'),
            'view' => Pages\ViewAssociatedType::route('/{record}'),
            'edit' => Pages\EditAssociatedType::route('/{record}/edit'),
        ];
    }
}
