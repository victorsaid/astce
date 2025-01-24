<?php

namespace App\Filament\Resources\FuncTesteResource\Pages;

use App\Filament\Resources\FuncTesteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFuncTeste extends CreateRecord
{
    protected static string $resource = FuncTesteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['document'] = str_replace(['.', '-'], '', $data['document']);
        return $data;
    }
}
