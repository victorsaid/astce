<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
//use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                ->schema([
                    Forms\Components\Wizard::make([
                        // Passo 1: Informações Pessoais
                        Wizard\Step::make('Informações Pessoais')
                            ->schema([
                                Forms\Components\Grid::make() // Dividindo em 2 colunas para melhorar layout
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nome Completo')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\FileUpload::make('photo')
                                        ->label('Foto')
                                        ->imageEditor()
                                        ->avatar()
                                        ->directory('profile_photos')
                                        ->preserveFilenames()
                                        ->disk('public'),
                                    Forms\Components\TextInput::make('document')
                                        ->label('CPF')
                                        ->placeholder('000.000.000-00')
                                        ->required()
                                        ->mask('999.999.999-99')
                                        ->unique(User::class, 'document', ignoreRecord: true)
                                        ,

                                    Forms\Components\Select::make('gender')
                                        ->label('Gênero')
                                        ->required()
                                        ->options([
                                            'M' => 'Masculino',
                                            'F' => 'Feminino',
                                        ]),

                                    Forms\Components\DatePicker::make('birth_date')
                                        ->label('Data de Nascimento')
                                        ->required(),

                                    Forms\Components\Select::make('blood_type')
                                        ->label('Tipo Sanguíneo')
                                        ->options([
                                            'A+' => 'A+',
                                            'A-' => 'A-',
                                            'B+' => 'B+',
                                            'B-' => 'B-',
                                            'AB+' => 'AB+',
                                            'AB-' => 'AB-',
                                            'O+' => 'O+',
                                            'O-' => 'O-',
                                        ]),
                                    Forms\Components\Select::make('marital_status')
                                        ->label('Estado Civil')
                                        ->required()
                                        ->options([
                                            'solteiro' => 'Solteiro(a)',
                                            'casado' => 'Casado(a)',
                                            'divorciado' => 'Divorciado(a)',
                                            'viuvo' => 'Viúvo(a)',
                                        ]),

                                    Forms\Components\Select::make('education_level')
                                        ->required()
                                        ->label('Nível de Escolaridade')
                                        ->options([
                                            'fundamental' => 'Fundamental',
                                            'medio' => 'Médio',
                                            'superior' => 'Superior',
                                            'pos' => 'Pós-Graduação',
                                            'mestrado' => 'Mestrado',
                                            'doutorado' => 'Doutorado'
                                        ]),
                                ]),
                            ]), // Step 1
                        // Passo 2: Informações de endereço
                        Wizard\Step::make('Endereço')
                            ->schema([
                                Forms\Components\Grid::make() // Organizando o layout em duas colunas
                                ->schema([
                                    Forms\Components\Fieldset::make('Endereço')
                                        ->relationship('address', 'address')
                                    ->schema([
                                        Forms\Components\TextInput::make('zip_code')
                                            ->label('CEP')
                                            ->suffixAction(
                                                fn ($state, $set) =>
                                                Action::make('search-action')
                                                    ->icon('heroicon-o-magnifying-glass')
                                                    ->action(function () use ($state, $set) {
                                                        if (blank($state)) {
                                                            Filament::notify('danger', 'Digite o CEP para buscar o endereço');
                                                            return;
                                                        }

                                                        try {
                                                            $cepData = Http::withoutVerifying() // Desabilita a verificação do SSL
                                                            ->get("https://viacep.com.br/ws/{$state}/json/")
                                                                ->throw()
                                                                ->json();
                                                        } catch (\Exception $e) {
                                                            Filament::notify('danger', 'Erro ao buscar o endereço');
                                                            return;
                                                        }

                                                        $set('neighborhood', $cepData['bairro'] ?? null);
                                                        $set('street', $cepData['logradouro'] ?? null);
                                                        $set('city', $cepData['localidade'] ?? null); // Correção: cidade é 'localidade'
                                                        $set('state', $cepData['uf'] ?? null); // Correção: estado é 'uf'
                                                    })
                                            )
                                            ->mask('99999-999'),
                                        Forms\Components\TextInput::make('state')
                                            ->label('Estado')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('city')
                                            ->label('Cidade')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('neighborhood')
                                            ->label('bairro')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('street')
                                            ->label('Rua')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('number')
                                            ->label('Número')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('complement')
                                            ->label('Complemento')
                                            ->maxLength(255),
                                    ])
                                ])
                            ]),

                        // Passo 3: Informações de Acesso
                        Wizard\Step::make('Informações de Acesso')
                            ->schema([
                                Forms\Components\Grid::make() // Organizando o layout em duas colunas
                                ->schema([
                                    Forms\Components\TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->required()
                                        ->maxLength(255),

                                    Forms\Components\DateTimePicker::make('email_verified_at')
                                        ->label('Email Verificado Em'),

                                    Forms\Components\TextInput::make('password')
                                        ->label('Senha')
                                        ->password()
                                        ->maxLength(255)
                                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // Apenas criptografa se o campo estiver preenchido
                                        ->required(fn(Page $livewire) => $livewire instanceof Pages\CreateUser) // Senha obrigatória apenas na criação
                                        ->dehydrated(fn($state) => filled($state)), // Evita que o campo seja enviado se estiver vazio
                                ]),
                            ]), // Step 2
                    ])

                ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('blood_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marital_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('education_level')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
