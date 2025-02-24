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
use Filament\Support\Enums\Alignment;
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
use Illuminate\Support\Number;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;
     protected static ?string $modelLabel = 'Folha de Pagamento';
     protected static ?string $navigationLabel = 'Folha de Pagamento';
    protected static ?string $pluralModelLabel = 'Folhas de Pagamento';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('month')
                    ->label('Mês')
                    ->required()
                    ->date(),

                Forms\Components\Placeholder::make('total')
                    ->label('Total')
                    ->content(function (Forms\Get $get, Forms\Set $set) {
                        $total = 0;
                        if (!$repeaters = $get('payments')) {
                            return $total;
                        }
                        foreach ($repeaters as $key => $repeater) {
                            $amount = (float)($get("payments.{$key}.amount") ?? 0); // Garante que sempre seja um número
                            $total += $amount;
                        }
                        $set('total', $total);
                        return Number::currency($total, 'BRL');
                    })
                    ->live(), // Garante que o valor seja atualizado dinamicamente
                Forms\Components\Hidden::make('total')
                    ->label('Total'),

                Fieldset::make('Pagamentos')
                    ->columns(1)
                    ->schema([
                        Repeater::make('payments')
                            ->columns(2)
                            ->label('Todos os Associados')
                            ->hiddenLabel()
                            ->addActionLabel('Adicionar Pagamento')
                            ->relationship('payments')
                            ->schema([
                                Select::make('user_id')
                                    ->label('Associado')
                                    ->hiddenLabel()
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
                                    ->hiddenLabel()
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->live(debounce: 1500),
                            ])
                            ->default(fn() => User::whereHas('associate', function ($query) {
                                $query->where('is_active', true);
                            })
                                ->orderBy('name')
                                ->get()
                                ->map(fn($user) => [
                                    'user_id' => $user->id,
                                    'amount' => 0, // Inicialmente zero para novos registros
                                ])
                                ->toArray()
                            ),
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
