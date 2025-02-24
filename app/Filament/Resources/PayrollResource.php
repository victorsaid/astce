<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Payroll;
use App\Models\PayrollPayment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        DatePicker::make('month')
                            ->label('Mês')
                            ->required()
                            ->date(),

                        Forms\Components\Placeholder::make('total')
                            ->label('Total')
                            ->content(fn ($get) => collect($get('payments') ?? [])->sum('amount')) // Atualiza dinamicamente
                            ->live(), // Garante que o valor seja atualizado dinamicamente
                    ]),

                Fieldset::make('Pagamentos')
                    ->columns(1)
                    ->schema([
                        Repeater::make('payments')
                            ->relationship('payments')
                            ->schema([
                                Select::make('user_id')
                                    ->label('Usuário')
                                    ->options(
                                        User::whereHas('associate', function ($query) {
                                            $query->where('is_active', true); // Filtra apenas usuários de associados ativos
                                        })
                                            ->pluck('name', 'id')
                                            ->toArray()
                                    )
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                                TextInput::make('amount')
                                    ->label('Valor')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->live() // Atualiza dinamicamente
                                    ->debounce(300)
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                    $set('total', collect($get('payments') ?? [])->sum('amount')) // Atualiza o total automaticamente
                                    )
                                ,
                            ])
                            ->columns(2)
                            ->default(fn () =>
                            User::whereHas('associate', function ($query) {
                                $query->where('is_active', true);
                            })
                                ->get()
                                ->map(fn ($user) => [
                                    'user_id' => $user->id,
                                    'amount' => 0, // Inicialmente zero para novos registros
                                ])
                                ->toArray()
                            ), // Impede adicionar usuários manualmente

//                        Forms\Components\Placeholder::make('total')
//                            ->label('Total')
//                            ->content(fn ($record) => $record->payments->sum('amount')), // Calcula o total
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('month')->label('Mês')->date(),
                TextColumn::make('total')->label('Total')->money('BRL'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            PayrollResource\RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
