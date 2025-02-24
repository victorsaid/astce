<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgreementResource\RelationManagers\AgreementUsersRelationManager;
use App\Filament\Resources\AgreementsResource\Pages;
use App\Filament\Resources\AgreementsResource\RelationManagers;
use App\Models\Agreements;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgreementsResource extends Resource
{
    protected static ?string $model = Agreements::class;
    protected static ?string $modelLabel = 'Convênio';
    protected static ?string $pluralModelLabel = 'Convênios';
    protected static ?string $slug = 'convenios';
    protected static ?string $navigationGroup = 'Convênios';
    protected static ?string $navigationIcon = 'fas-tree-city';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('photo')
                    ->label('Foto')
                    ->imageEditor()
                    ->avatar()
                    ->directory('agreements_photos')
                    ->preserveFilenames()
                    ->disk('public'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome')
                    ->maxLength(255),
                Forms\Components\MarkdownEditor::make('description')
                    ->required()
                    ->label('Descrição')
                    ->maxLength(255),

                Forms\Components\TextInput::make('site')
                    ->prefix('https://')
                    ->label('Site')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->label('Tipo')
                    ->options([
                        'Saude' => 'Saude e Bem Estar',
                        'Educacao' => 'Educacao e Capacitacao',
                        'Plano de Saude' => 'Plano de Saude',
                        'Lazer' => 'Lazer e Entretenimento',
                        'Compras' => 'Compras e Servicos',

                    ]),
                Forms\Components\ToggleButtons::make('is_active')
                    ->required()
                    ->default(true)
                    ->inline()
                    ->label('Ativo')
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
            ->defaultSort('name')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('site')
                    ->label('Site')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            AgreementUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgreements::route('/'),
            'create' => Pages\CreateAgreements::route('/create'),
            'edit' => Pages\EditAgreements::route('/{record}/edit'),
            'view' => Pages\ViewAgreement::route('/{record}'),
        ];
    }
}
