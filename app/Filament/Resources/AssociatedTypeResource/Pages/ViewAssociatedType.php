<?php

namespace App\Filament\Resources\AssociatedTypeResource\Pages;

use App\Filament\Resources\AssociatedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssociatedType extends ViewRecord
{
    protected static string $resource = AssociatedTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
