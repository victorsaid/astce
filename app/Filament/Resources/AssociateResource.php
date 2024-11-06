<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssociateResource\Pages;
use App\Filament\Resources\AssociateResource\RelationManagers;
use App\Models\Associate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssociateResource extends Resource
{
    protected static ?string $model = Associate::class;
    protected static ?string $navigationLabel = 'Associados';

    protected static ?string $pluralModelLabel = 'Associados';

    protected static ?string $modelLabel = 'Associado';

    protected static ?string $slug = 'associados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('member_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('position_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('enrollment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('association_date')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('member_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('association_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
            'index' => Pages\ListAssociates::route('/'),
            'create' => Pages\CreateAssociate::route('/create'),
            'view' => Pages\ViewAssociate::route('/{record}'),
            'edit' => Pages\EditAssociate::route('/{record}/edit'),
        ];
    }
}
