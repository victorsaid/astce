<?php

namespace App\Filament\Resources\AssociatedTypeResource\Pages;

use App\Filament\Resources\AssociatedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssociatedType extends EditRecord
{
    protected static string $resource = AssociatedTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
