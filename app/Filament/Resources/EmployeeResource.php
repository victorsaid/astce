<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuncTesteResource\Pages;
use App\Filament\Resources\FuncTesteResource\RelationManagers;
use App\Models\FuncTeste;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Wizard;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Usuários';
    protected static ?string $modelLabel = 'Funcionários';
    protected static ?string $pluralModelLabel = 'Funcionários';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'fas-user-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1) //grid maior
                ->schema([
                    Forms\Components\Wizard::make([
                        // Passo 1: Informações Pessoais
                        Wizard\Step::make('Informações Pessoais')
                            ->schema([
                                Forms\Components\Grid::make(3) // Dividindo em 2 colunas para melhorar layout
                                ->schema([
                                    FileUpload::make('photo')
                                        ->label('Foto')
                                        ->imageEditor()
                                        ->avatar()
                                        ->directory('profile_photos')
                                        ->preserveFilenames()
                                        ->disk('public'),
                                    TextInput::make('document')
                                        ->label('CPF')
                                        ->placeholder('000.000.000-00')
                                        ->required()
                                        ->mask('999.999.999-99') // Máscara para CPF
                                        ->dehydrated(true) // Sempre envia o valor do campo, mesmo vazio
                                        ->rule(function (Forms\Get $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                // 🔹 Remove pontuações do CPF
                                                $cpfSanitizado = preg_replace('/[^0-9]/', '', $value);

                                                // 🔍 Obtém o ID do registro sendo editado (se houver)
                                                $recordId = $get('id'); // Obtém o ID no contexto do Filament

                                                // 🚨 Se estamos editando, não aplicamos a validação
                                                if (!empty($recordId)) {
                                                    return;
                                                }

                                                // 🚨 Apenas faz a validação se estivermos na criação
                                                $isAssociate = User::where('document', $cpfSanitizado)
                                                    ->whereHas('employee')
                                                    ->exists();

                                                if ($isAssociate) {
                                                    $fail('Este CPF já está sendo usado por um funcionario.');
                                                }
                                            };
                                        })
                                        ->suffixAction(
                                            Action::make('search')
                                                ->icon('heroicon-o-magnifying-glass')
                                                ->action(function (Forms\Set $set, $state, Forms\Get $get, $livewire) {
                                                    if (blank($state)) {
                                                        Notification::make()
                                                            ->title('Digite o CPF para buscar')
                                                            ->danger()->send();
                                                        return;
                                                    }else{
                                                        $cpfSanitizado = preg_replace('/[^0-9]/', '', $state);
                                                        $userData = User::where('document', $cpfSanitizado)->first();
                                                        if ($userData && $userData->employee) {
                                                            // 🔴 Exibe erro no campo CPF
                                                            $livewire->addError('document', 'Este CPF já está sendo usado por um associado.');

                                                            // 🔴 Limpa o campo CPF para evitar continuação do cadastro
                                                            $set('document', '');

                                                            // 🔴 Envia notificação de erro
                                                            Notification::make()
                                                                ->title('CPF já está sendo usado por um funcionário')
                                                                ->danger()
                                                                ->send();

                                                            return;
                                                        }
                                                    }
                                                    try {
                                                        $cpfSanitizado = preg_replace('/[^0-9]/', '', $state);
                                                        $userData = User::where('document', $cpfSanitizado)->firstOrFail();
                                                        $set('name', $userData->name ?? '');
                                                        $set('gender', $userData->gender ?? '');
                                                        $set('birth_date', $userData->birth_date?? '');
                                                        $set('blood_type', $userData->blood_type ?? '');
                                                        $set('marital_status', $userData->marital_status ?? '');
                                                        $set('education_level', $userData->education_level?? '');
                                                        $set('email', $userData->email ?? '');
                                                        $firstPhone = $userData->phone ? $userData->phone->first() : null;
                                                        ///seta os contatos
                                                        $set('contacts', $firstPhone ? [
                                                            [
                                                                'number' => $firstPhone->number,
                                                                'type' => $firstPhone->type,
                                                                'observation' => $firstPhone->observation,
                                                            ]
                                                        ]: []);
                                                        // seta o endereço
                                                        $set('address', [
                                                            'zip_code' => $userData->address->zip_code,
                                                            'state' => $userData->address->state,
                                                            'city' => $userData->address->city,
                                                            'neighborhood' => $userData->address->neighborhood,
                                                            'street' => $userData->address->street,
                                                            'number' => $userData->address->number,
                                                            'complement' => $userData->address->complement,
                                                        ]);

                                                    }catch (ModelNotFoundException $e){
                                                        Notification::make()
                                                            ->title('CPF não encontrado')
                                                            ->danger()->send();
                                                    }
                                                })
                                        ),
                                    TextInput::make('name')
                                        ->label('Nome Completo')
                                        ->required()
                                        ->maxLength(255),
                                    Select::make('gender')
                                        ->label('Gênero')
                                        ->required()
                                        ->options([
                                            'masculino' => 'Masculino',
                                            'feminino' => 'Feminino',
                                            'nao_binario' => 'Não Binário',
                                            'nao_informar' => 'Não Informar',
                                        ]),
                                    DatePicker::make('birth_date')
                                        ->label('Data de Nascimento')
                                        ->date('d/m/Y')
                                        ->required(),
                                    Select::make('blood_type')
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
                                    Select::make('marital_status')
                                        ->label('Estado Civil')
                                        ->required()
                                        ->options([
                                            'solteiro' => 'Solteiro(a)',
                                            'casado' => 'Casado(a)',
                                            'divorciado' => 'Divorciado(a)',
                                            'viuvo' => 'Viúvo(a)',
                                            'uniao_estavel' => 'União Estável',
                                        ]),
                                    Select::make('education_level')
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
                            ]), // fim Step 1
                        Wizard\Step::make('Contatos') //passo 2
                        ->schema([
                            Forms\Components\Repeater::make('contacts')
                                ->label('Contatos')
                                ->relationship('phone')
                                ->schema([
                                    Forms\Components\Grid::make(3)->schema([ // Organizando em 4 colunas
                                        Forms\Components\TextInput::make('number')
                                            ->label('Telefone')
                                            ->required()
                                            ->placeholder('(xx)xxxxx-xxxx')
                                            ->mask('(99)99999-9999')
                                            ->dehydrateStateUsing(fn($state) => preg_replace('/[^0-9]/', '', $state)), // Remove caracteres não numéricos
                                        Forms\Components\Select::make('type')
                                            ->label('Tipo de contato')
                                            ->required()
                                            ->options([
                                                'Celular' => 'Celular',
                                                'Residencial' => 'Residencial',
                                                'Comercial' => 'Comercial',
                                            ]),
                                        Forms\Components\TextInput::make('observation')
                                            ->label('Observação')
                                            ->maxLength(255),
                                    ]),
                                ])
                                ->columns(1) // Define uma repetição por linha

                        ]),//fim Passo 2: Informações de endereço
                        Wizard\Step::make('Endereço') //passo 3
                        ->schema([
                            Forms\Components\Grid::make() // Organizando o layout em duas colunas
                            ->schema([
                                Forms\Components\Fieldset::make('Endereço')
                                    ->relationship('address', 'address')
                                    ->schema([
                                        Forms\Components\TextInput::make('zip_code')
                                            ->label('CEP')
                                            ->suffixAction(
                                                fn($state, $set) => Action::make('search-action')
                                                    ->icon('heroicon-o-magnifying-glass')
                                                    ->action(function () use ($state, $set) {
                                                        if (blank($state || strlen($state) < 9)) {
                                                            Notification::make()
                                                                ->title('Digite o CEP completo para buscar o endereço')
                                                                ->danger()
                                                                ->send();
                                                            return;
                                                        }

                                                        try {
                                                            $cepData = Http::withoutVerifying() // Desabilita a verificação do SSL
                                                            ->get("https://viacep.com.br/ws/{$state}/json/")
                                                                ->throw()
                                                                ->json();
                                                        } catch (\Exception $e) {
                                                            Notification::make()
                                                                ->title('Erro ao buscar o CEP. Verifique se o CEP está correto e tente novamente.')
                                                                ->danger()
                                                                ->send();
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
                                            ->label('Bairro')
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
                        ]), //fim passo 3

                        // Passo 4: Informações de Acesso
                        Wizard\Step::make('Informações de Acesso')
                            ->schema([
                                Grid::make() // Organizando o layout em duas colunas
                                ->schema([
                                    TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->required() // E-mail é obrigatório apenas para novos registros
                                        ->unique(
                                            modifyRuleUsing: function ($rule, callable $get) {
                                                $document = str_replace(['.', '-'], '', $get('document')); // Remove máscara do CPF
                                                return $rule->whereNot('document', $document);
                                            }
                                        )
                                        ->maxLength(255),

                                    Hidden::make('email_verified_at')
                                        ->label('Email Verificado Em'),

                                    TextInput::make('password')
                                        ->label('Senha')
                                        ->password()
                                        ->maxLength(255)
                                        //->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // Criptografa a senha somente se fornecida
                                        ->required(false) // Torna o campo não obrigatório
                                        ->dehydrated(true),
                                    Hidden::make('role')
                                        ->label('Perfil'),
                                ]),
                            ]), //fecha step 4
                        Wizard\Step::make('Funcionário') //passo 5
                        ->schema([
                            Forms\Components\Fieldset::make('funcionarios')
                                ->relationship('employee', 'employee')
                                ->schema([
                                    DatePicker::make('hire_date')
                                        ->label('Data de Contratação')
                                        ->date('d/m/Y')
                                        ->required(),
                                    TextInput::make('salary')
                                        ->label('Salário')
                                        ->required()
                                        ->prefix('R$') // Exibe o símbolo da moeda
                                        ->numeric() // Garante que apenas números sejam aceitos
                                        ->default(0.00) // Define um valor padrão inicial
                                        ,
                                    TextInput::make('position')
                                        ->label('Cargo')
                                        ->required()
                                        ->maxLength(255),

                                    Forms\Components\ToggleButtons::make('commission_member')
                                        ->label('Membro da Comissão?')
                                        ->default(true)
                                        ->inline()
                                        ->options([
                                            0 => 'Não',
                                            1 => 'Sim',
                                        ])
                                        ->icons([
                                            0 => 'heroicon-o-x-mark',
                                            1 => 'heroicon-o-check',
                                        ])
                                        ->colors([
                                            0 => 'danger',
                                            1 => 'success',
                                        ]),
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
                                        ]),

                                ]),
                        ]),
                    ])->skippable(), //fecha wizard

                ]),  //fecha grid
            ]); //fecha schema do form
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('document')
                    ->label('CPF')
                    ->searchable()
                    ->copyable()
                    ->formatStateUsing(fn ($state) =>
                        substr($state, 0, 3) . '.' .
                        substr($state, 3, 3) . '.' .
                        substr($state, 6, 3) . '-' .
                        substr($state, 9, 2)
                    ),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable()
                    ->label('Gênero')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->label('Data de Nascimento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone.number')
                    ->icon('fas-square-phone') // Ícone do Heroicons
                    ->color('success') // Cor opcional para destacar o ícone
                    ->label('Telefone')
                    ->searchable()
                    ->formatStateUsing(fn ($state) =>
                    preg_replace('/^(\d{2})(\d{4,5})(\d{4})$/', '($1) $2-$3', $state))
                    ->url(fn ($record) => $record->phone
                        ? 'https://api.whatsapp.com/send/?phone=55' . preg_replace('/[^0-9]/', '', $record->phone->number)
                        : null
                    )
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('marital_status')
                    ->label('Estado Civil')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('education_level')
                    ->label('Escolaridade')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Escolaridade'),
//                Tables\Columns\TextColumn::make('roles.name')
//                    ->searchable()
//                    ->label('Perfil')
//                ,
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function ($record){
                        if($record->associate){
                            return Notification::make()
                                ->title('Erro ao excluir')
                                ->body('Este funcionário está associado a um associado. Não é possível excluir.')
                                ->danger()
                                ->send();
                        }elseif($record->employee){
                            $record->associate->delete();
                            $record->removeRole('Employee');
                            return Notification::make()
                                ->title('Funcionário excluído com sucesso!')
                                ->success()
                                ->send();
                        }
                        return false;
                    })
                ,
                Tables\Actions\ViewAction::make(),

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

    //Oculta o resource de funcionário para um funcionário
    public static function canAccess(): bool
    {
        return Auth::check() && (Auth::user()->roles->pluck('name')->diff(['Employee', 'Associate'])->isNotEmpty());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployee::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
            'view' => Pages\ViewEmployee::route('/{record}'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole(['admin', 'Super_admin'])
            ? parent::getEloquentQuery()->whereHas('employee')
            : parent::getEloquentQuery()
                ->whereHas('employee') // Filtra apenas usuários que têm relação com employee
                ->whereHas('roles', fn($query) => $query->whereNotIn('name', ['Admin', 'Super_admin']));
    }

}
