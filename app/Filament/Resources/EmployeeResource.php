<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = 'Usuários';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $pluralModelLabel = 'Funcionários';
    protected static ?string $modelLabel = 'Funcionários';
    protected static ?string $slug = 'funcionarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuário')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\DatePicker::make('hire_date')
                    ->label('Data de Contratação')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->required()
                    ->label('Salário')
                    ->numeric()
                    ->prefix('R$')
                ,
                Forms\Components\ToggleButtons::make('is_active')
                    ->label('Funcionário Ativo?')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nome')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hire_date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Data de Contratação'),
                Tables\Columns\TextColumn::make('salary')
                    ->money('BRL', '10')
                    ->sortable()
                    ->label('Salário'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Funcionario Ativo?'),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
