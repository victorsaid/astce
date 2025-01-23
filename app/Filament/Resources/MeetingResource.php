<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingResource\Pages;
use App\Filament\Resources\MeetingResource\RelationManagers;
use App\Models\Meeting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Mail;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'fas-group-arrows-rotate';
    protected static ?string $modelLabel = 'Reunião';
    protected static ?string $pluralModelLabel = 'Reuniões';
    protected static ?string $navigationGroup = 'Reuniões';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título da reunião')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date')
                    ->label('Data da reunião')
                    ->format('DD/MM/YYYY HH:mm')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição da reunião')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('participants')
                    ->label('Participantes')
                    ->multiple()
                    ->relationship('participants', 'name')
                    ->preload(),

//                Forms\Components\Select::make('participants')
//                ->multiple()
//                ->relationship('users', 'name')
//                ->preload(),
//                Forms\Components\TextInput::make('attachments'),
//                Forms\Components\TextInput::make('photos'),

                Forms\Components\Repeater::make('topics')
                    ->relationship('topics') // Associa o Repeater à relação HasMany
                    ->label('Pautas')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título da pauta')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description')
                            ->label('Descrição da pauta')
                            ->maxLength(65535),
                        Forms\Components\MarkdownEditor::make('content')
                            ->label('Descrição da pauta')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                Forms\Components\FileUpload::make('attachments')
                    ->label('Anexos')
                    ->acceptedFileTypes(['application/pdf']) // Aceitar apenas PDFs
                    ->openable()
                    ->directory('meeting_attachments') // Subdiretório para salvar as fotos
                    ->disk('public') // Usa o disco padrão 'public'
                    ->visibility('private')
                    ->maxFiles(5) // Permite até 5 arquivos
                    ->preserveFilenames()
                    ->columnSpan(1),
                Forms\Components\FileUpload::make('photos')
                    ->label('Fotos')
                    ->openable()
                    ->downloadable()
                    ->preserveFilenames()
                    ->maxFiles(5)
                    ->multiple()
                    ->directory('meeting_photos') // Subdiretório para salvar as fotos
                    ->disk('public') // Usa o disco padrão 'public'
                    ->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
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
                Tables\Actions\Action::make('sendEmail')
                    ->form([
                        TextInput::make('subject')->required(),
                        RichEditor::make('body')->required(),
                    ])
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
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'view' => Pages\ViewMeeting::route('/{record}'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
