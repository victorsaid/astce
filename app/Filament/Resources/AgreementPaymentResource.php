<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgreementPaymentResource\Pages;
use App\Models\AgreementPayment;
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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Form;
class AgreementPaymentResource extends Resource
{
    protected static ?string $model = AgreementPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('agreement_id')
                    ->label('Convênio')
                    ->options(Agreements::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpan(6),

                DatePicker::make('payment_date')
                    ->label('Data de Referência')
                    ->required()
                    ->columnSpan(6),

                Forms\Components\Placeholder::make('total')
                    ->label('Total da Folha')
                    ->content(function (Forms\Get $get) {
                        $total = 0;
                        foreach ($get('payments') ?? [] as $i => $item) {
                            $total += floatval($item['value'] ?? 0);
                        }
                        return 'R$ ' . number_format($total, 2, ',', '.');
                    })
                    ->columnSpan(4)
                    ->live(),

                Forms\Components\Hidden::make('total'),

                Forms\Components\Fieldset::make('Pagamentos dos Usuários')
                    ->schema([
                        Repeater::make('payments')
                            ->columns(12)
                            ->label('Pagamentos')
                            ->hiddenLabel()
                            ->schema([
                                Select::make('user_id')
                                    ->label('Usuário')
                                    ->options(
                                        User::whereHas('associate', fn ($q) => $q->where('is_active', true))
                                            ->pluck('name', 'id')
                                    )
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->columnSpan(6),

                                TextInput::make('value')
                                    ->label('Valor')
                                    ->prefix('R$')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->default(function () {
                                return User::whereHas('associate', fn ($q) => $q->where('is_active', true))
                                    ->orderBy('name')
                                    ->get()
                                    ->map(fn ($user) => [
                                        'user_id' => $user->id,
                                        'value' => 0,
                                        'status' => 'pending',
                                    ])
                                    ->toArray();
                            })
                            ->minItems(1)
                            ->addActionLabel('Adicionar novo pagamento'),
                    ])
                    ->columnSpan(12),
            ])->columns(12);
    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->groups([
                Group::make('agreement.name') // Agrupar por Convênio
                ->label('Convênio'),
            ])
            ->columns([
                TextColumn::make('agreement.name')
                    ->label('Convênio')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Valor Pago')
                    ->prefix('R$')
                    ->sortable(),

                TextColumn::make('payment_date')
                    ->label('Data do Pagamento')
                    ->date(),
            ])
            ->defaultSort('payment_date', 'desc');
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
            'index' => Pages\ListAgreementPayments::route('/'),
            'create' => Pages\CreateAgreementPayment::route('/create'),
            'view' => Pages\ViewAgreementPayment::route('/{record}'),
            'edit' => Pages\EditAgreementPayment::route('/{record}/edit'),
        ];
    }
}
