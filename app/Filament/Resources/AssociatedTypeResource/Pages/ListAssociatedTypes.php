<?php

namespace App\Filament\Resources\AssociatedTypeResource\Pages;

use App\Filament\Resources\AssociatedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssociatedTypes extends ListRecords
{
    protected static string $resource = AssociatedTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
