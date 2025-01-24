<?php

namespace App\Filament\Resources\FuncTesteResource\Pages;

use App\Filament\Resources\FuncTesteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFuncTestes extends ListRecords
{
    protected static string $resource = FuncTesteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
