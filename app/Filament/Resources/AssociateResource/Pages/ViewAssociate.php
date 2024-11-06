<?php

namespace App\Filament\Resources\AssociateResource\Pages;

use App\Filament\Resources\AssociateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssociate extends ViewRecord
{
    protected static string $resource = AssociateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
