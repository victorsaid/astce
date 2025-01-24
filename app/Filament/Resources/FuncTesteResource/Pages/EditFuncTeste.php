<?php

namespace App\Filament\Resources\FuncTesteResource\Pages;

use App\Filament\Resources\FuncTesteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuncTeste extends EditRecord
{
    protected static string $resource = FuncTesteResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['document'] = str_replace(['.', '-'], '', $data['document']);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
