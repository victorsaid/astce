<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgreementPaymentResource\Pages;
use App\Models\AgreementPayroll;
use App\Models\Agreements;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Form;
class AgreementPayrollResource extends Resource
{
    protected static ?string $model = AgreementPayroll::class;

    protected static ?string $modelLabel = 'Pagamento de Convênio';
    protected static ?string $navigationLabel = 'Pagamentos de Convênios';
    protected static ?string $pluralModelLabel = 'Pagamentos de Convênios';
    protected static ?string $navigationGroup = 'Convênios';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->columnSpan(4)
                    ->maxLength(255),
                Select::make('agreement_id')
                    ->label('Convênio')
                    ->options(Agreements::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->columnSpan(3)
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                        if (!$state) {
                            $set('payments', []);
                            return;
                        }

                        $lastAgreementPayroll = AgreementPayroll::where('agreement_id', $state)
                            ->latest('date')
                            ->first();

                        $previousPayments = $lastAgreementPayroll
                            ? $lastAgreementPayroll->payments->pluck('amount', 'user_id')->toArray()
                            : [];

                        $payments = User::whereHas('associate', fn ($q) => $q->where('is_active', true))
                            ->orderBy('name')
                            ->get()
                            ->map(fn ($user) => [
                                'user_id' => $user->id,
                                'enrollment' => $user->associate->enrollment ?? 'N/A',
                                'amount' => $previousPayments[$user->id] ?? 0,
                            ])
                            ->toArray();

                        $set('payments', $payments);
                    }),
                DatePicker::make('date')
                    ->label('Data de Referência')
                    ->required()
                    ->columnSpan(3),
                Forms\Components\Placeholder::make('total')
                    ->label('Total da Folha')
                    ->content(function (Forms\Get $get, Forms\Set $set) {
                        $total = 0;
                        foreach ($get('payments') ?? [] as $i => $item) {
                            $total += floatval($item['amount'] ?? 0);
                        }
                        $set('total', $total);
                        return 'R$ ' . number_format($total, 2, ',', '.');
                    })
                    ->columnSpan(2)
                    ->live(true),

                Forms\Components\Hidden::make('total'),

                Forms\Components\Fieldset::make('Pagamentos dos Usuários')
                    ->columnSpan(12)
                    ->extraAttributes([
                        'style' => 'max-height: 700px; overflow-y: auto;', // Limita a altura e ativa scroll interno
                    ])
                    ->schema([
                        Repeater::make('payments')
                            ->label('Pagamentos')
                            ->hiddenLabel()
                            ->reactive()
                            ->live(true)
                            ->relationship('payments')
                            ->columnSpan(12)
                            ->columns(12)

                            ->schema([
                                Select::make('user_id')
                                    ->label('Usuário')
                                    ->options(
                                        User::whereHas('associate', fn ($q) => $q->where('is_active', true))
                                            ->pluck('name', 'id')
                                    )
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->hiddenLabel()
                                    ->columnSpan(6),

                                TextInput::make('amount')
                                    ->label('Valor')
                                    ->prefix('R$')
                                    ->numeric()
                                    ->hiddenLabel()
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->default(function () {
                                $lastAgreementPayroll = AgreementPayroll::latest('date')->first();
                                //dd($lastAgreementPayroll);
                                $previousPayments = $lastAgreementPayroll
                                    ? $lastAgreementPayroll->payments->pluck('amount', 'user_id')->toArray()
                                    : [];
                                return User::whereHas('associate', fn ($q) => $q->where('is_active', true))
                                    ->orderBy('name')
                                    ->get()
                                    ->map(fn ($user) => [
                                        'user_id' => $user->id,
                                        'enrollment' => $user->associate->enrollment ?? 'N/A',
                                        'amount' => $previousPayments[$user->id] ?? 0, // Mantém os valores anteriores sem sobrescrever
                                    ])
                                    ->toArray();
                            })
                            ->addActionLabel('Adicionar novo pagamento'),
                    ]),
            ])->columns(12);
    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->defaultGroup('agreement.name')
            ->groups([
                Group::make('agreement.name') // Agrupar por Convênio
                ->label('Convênio'),
            ])
            ->columns([
                TextColumn::make('name')
                ->label('Nome')
                ->searchable()
                ->sortable(),
                TextColumn::make('agreement.name')
                    ->label('Convênio')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->badge()
                    ->color('success')
                    ->prefix('R$')
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Data do Pagamento')
                    ->sortable()
                    ->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                Tables\Actions\ViewAction::make(),


            ])
            ->defaultSort('date', 'desc');
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
            'index' => AgreementPayrollResource\Pages\ListAgreementPayroll::route('/'),
            'create' => AgreementPayrollResource\Pages\CreateAgreementPayroll::route('/create'),
            'view' => AgreementPayrollResource\Pages\ViewAgreementPayroll::route('/{record}'),
            'edit' => AgreementPayrollResource\Pages\EditAgreementPayroll::route('/{record}/edit'),
        ];
    }
}
