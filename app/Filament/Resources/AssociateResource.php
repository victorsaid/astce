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
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Associados';
    protected static ?string $pluralModelLabel = 'Associados';
    protected static ?string $navigationGroup = 'Usuários';

    protected static ?string $modelLabel = 'Associado';

    protected static ?string $slug = 'associados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3) // Dividindo o layout em 2 colunas
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Usuário')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpan(1)
                        ->reactive()
//                        ->afterStateUpdated(function ($state, callable $set) {
//                            if ($state) {
//                                $user = \App\Models\User::find($state); // Busca o usuário selecionado
//                                $set('document', $user ? $user->document : ''); // Define o campo 'name' com o nome do usuário
//                            }
//                        })
                    ,
                    Forms\Components\TextInput::make('enrollment')
                        ->label('Matrícula')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('associated_type_id')
                        ->label('Tipo de Membro')
                        ->relationship('associated_type', 'name')
                        ->required()
                        ->preload()
                        ->searchable(),

                    Forms\Components\Select::make('position_id')
                        ->label('Cargo')
                        ->required()
                        ->relationship('position', 'name'),

                    Forms\Components\DatePicker::make('association_date')
                        ->label('Data de Associação')
                        ->required(),

                    Forms\Components\ToggleButtons::make('is_active')
                        ->label('Associado Ativo?')
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
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('Nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make('associated_types.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name')
                    ->numeric()
                    ->label('Cargo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment')
                    ->label('Matrícula')
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
            RelationManagers\DependantsRelationManager::class,
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
