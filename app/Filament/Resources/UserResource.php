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
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMessageMail;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;




class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Associado';
    protected static ?string $pluralModelLabel = 'Associados';
    protected static ?string $slug = 'associados';
    protected static ?string $navigationGroup = 'Usuários';
    protected static ?string $navigationIcon = 'fas-user-tie';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1) //grid maior
                ->schema([
                    Wizard::make([
                        // Passo 1: Informações Pessoais
                        Wizard\Step::make('Informações Pessoais')
                            ->schema([
                                Grid::make(3) // Dividindo em 2 colunas para melhorar layout
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
                                    ,
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
                                Repeater::make('contacts')
                                    ->label('Contatos')
                                    ->relationship('phone')
                                    ->schema([
                                        Grid::make(3)->schema([ // Organizando em 4 colunas
                                            TextInput::make('number')
                                                ->label('Telefone')
                                                ->required()
                                                ->placeholder('(xx)xxxxx-xxxx')
                                                ->mask('(99)99999-9999')
                                                ->dehydrateStateUsing(fn($state) => preg_replace('/[^0-9]/', '', $state)), // Remove caracteres não numéricos
                                            Select::make('type')
                                                ->label('Tipo de contato')
                                                ->required()
                                                ->options([
                                                    'Celular' => 'Celular',
                                                    'Residencial' => 'Residencial',
                                                    'Comercial' => 'Comercial',
                                                ]),
                                            TextInput::make('observation')
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
                                    Fieldset::make('Endereço')
                                        ->relationship('address', 'address')
                                        ->schema([
                                            TextInput::make('zip_code')
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
                                            TextInput::make('state')
                                                ->label('Estado')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('city')
                                                ->label('Cidade')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('neighborhood')
                                                ->label('Bairro')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('street')
                                                ->label('Rua')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('number')
                                                ->label('Número')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('complement')
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
                                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // Criptografa a senha somente se fornecida
                                        ->required(false) // Torna o campo não obrigatório
                                        ->dehydrated(true), // Sempre envia o valor do campo, mesmo vazio


                                    Hidden::make('role')
                                        ->label('Perfil')
//                                        ->relationship('roles', 'name',
//                                            fn(Builder $query) => auth()->user()->hasRole(['Admin', 'Super_admin']) ? null :
//                                                $query->whereNotIn('name', ['Admin', 'Super_admin'])
//                                        )
                                        //->required()
                                        //->preload()
                                        //->multiple()
                                    ,
                                ]),
                            ]), //fecha step 4
                            Wizard\Step::make('Associados') //passo 5
                                ->schema([
                                Fieldset::make('Informações sobre o Associado')
                                    ->relationship('associate', 'associate')
                                    ->schema([
                                        TextInput::make('enrollment')
                                            ->label('Matrícula')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('associated_type_id')
                                            ->label('Tipo de Membro')
                                            ->relationship('associated_type', 'name')
                                            ->required()
                                            ->preload(),

                                        Select::make('position_id')
                                            ->label('Cargo')
                                            ->required()
                                            ->relationship('position', 'name'),

                                        DatePicker::make('association_date')
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

                                    ]),
                                ]),
                    ]), //fecha wizard

                ]),  //fecha grid
            ]); //fecha schema do form
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('document')
                    ->label('CPF')
                    ->searchable()
                    ->formatStateUsing(fn ($state) =>
                        substr($state, 0, 3) . '.' .
                        substr($state, 3, 3) . '.' .
                        substr($state, 6, 3) . '-' .
                        substr($state, 9, 2)
                    ),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('gender')
                    ->searchable()
                    ->label('Gênero')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('birth_date')
                    ->date()
                    ->label('Data de Nascimento')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('phone.number')
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
                IconColumn::make('associate.is_active')
                    ->label('Ativo?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->label('Perfil')
                ,
                TextColumn::make('blood_type')
                    ->label('Tipo Sanguíneo')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'A+' => 'success',
                        'A-' => 'danger',
                        'B+' => 'info',
                        'B-' => 'warning',
                        'AB+' => 'primary',
                        'AB-' => 'gray',
                        'O+' => 'success',
                        'O-' => 'danger',
                        default => 'secondary', // Cor padrão caso o valor não corresponda
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('marital_status')
                    ->label('Estado Civil')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('education_level')
                    ->label('Escolaridade')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Escolaridade'),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('sendEmail')
                    ->label('Enviar E-mail')
                    ->icon('heroicon-o-envelope')
                    ->form([
                        TextInput::make('subject')
                            ->label('Assunto')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('message')
                            ->label('Mensagem')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (array $data, $record): void {
                        // Envia o e-mail para o registro atual
                        Mail::to($record->email)->send(new UserMessageMail($data['subject'], $data['message']));

                        // Notificação de sucesso
                        Notification::make()
                            ->title('E-mail enviado com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('sendBulkEmail')
                        ->label('Enviar E-mails')
                        ->icon('heroicon-o-envelope')
                        ->deselectRecordsAfterCompletion()
                        ->form([
                            Forms\Components\Textarea::make('message')
                                ->label('Mensagem')
                                ->required()
                                ->rows(3),
                            Forms\Components\Textarea::make('remarks')
                                ->label('Observações')
                                ->hint('As observações não serão enviadas no e-mail.'),
                        ])
                        ->action(function (array $data, Collection $records): void { // Use Collection aqui
                            // Itera sobre os registros selecionados
                            foreach ($records as $record) {
                                Mail::to($record->email)->send(
                                    new \App\Mail\UserMessageMail('Mensagem em Massa', $data['message'])
                                );
                            }

                            // Notificação de sucesso
                            Notification::make()
                                ->title('E-mails enviados com sucesso!')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DependantsRelationManager::make()
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

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole(['admin', 'Super_admin'])
            ? parent::getEloquentQuery()->whereHas('associate')
            : parent::getEloquentQuery()
                ->whereHas('employee') // Filtra apenas usuários que têm relação com employee
                ->whereHas('roles', fn($query) => $query->whereNotIn('name', ['Admin', 'Super_admin']));
    }


}
