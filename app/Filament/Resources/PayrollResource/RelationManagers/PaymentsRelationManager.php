<?php

namespace App\Filament\Resources\PayrollResource\RelationManagers;

use App\Models\User;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PaymentsRelationManager extends RelationManager {
    protected static string $relationship = 'payments';
    protected static ?string $title = 'Pagamentos dos Associados';

    public function form(Forms\Form $form): Forms\Form {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Associado')
                    ->options(User::whereHas('associate', function ($query) {
                        $query->where('is_active', true);
                    })->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('associated_type', User::find($state)?->associate->associated_type?->name ?? 'N/A')
                    ),

                TextInput::make('associated_type')
                    ->label('Tipo de Associado')
                    ->readOnly(),

                TextInput::make('amount')
                    ->label('Valor Pago')
                    ->prefix('R$')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->live(debounce: 1500),
            ])
            ->columns(3);
    }

    public function table(Tables\Table $table): Tables\Table {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Associado')->searchable(),
                TextColumn::make('associated_type')->label('Tipo de Associado'),
                TextColumn::make('amount')->label('Valor Pago')->money('BRL'),
            ])
            ->paginated(10);
    }

    /**
     * Carregar automaticamente os pagamentos com base nos últimos valores registrados.
     */
    protected function getDefaultTableRecords(): array {
        // Buscar a última folha de pagamento
        $lastPayroll = Payroll::latest('id')->first();

        // Se houver uma folha de pagamento anterior, pegar os valores dos usuários
        $previousPayments = $lastPayroll
            ? $lastPayroll->payments->pluck('amount', 'user_id')->toArray()
            : [];

        // Buscar todos os usuários ativos
        return User::whereHas('associate', function ($query) {
            $query->where('is_active', true);
        })
            ->orderBy('name')
            ->get()
            ->map(fn ($user) => [
                'user_id' => $user->id,
                'associated_type' => $user->associate->associated_type?->name ?? 'N/A',
                'amount' => $previousPayments[$user->id] ?? 0, // Se houver valor do mês passado, usa; senão, começa com 0
            ])
            ->toArray();
    }
}
