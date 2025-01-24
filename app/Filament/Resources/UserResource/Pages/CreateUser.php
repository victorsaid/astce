<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['document'] = str_replace(['.', '-'], '', $data['document']);
        return $data;
    }

    protected function handleRecordCreation(array $data): \App\Models\User
    {
        // Verificar se o CPF já existe no banco de dados
        $existingUser = \App\Models\User::where('document', $data['document'])->first();

        if ($existingUser) {
            // Atualizar os dados básicos do usuário existente
            $existingUser->update([
                'name' => $data['name'] ?? $existingUser->name,
                'email' => $data['email'] ?? $existingUser->email,
                'gender' => $data['gender'] ?? $existingUser->gender,
                'birth_date' => $data['birth_date'] ?? $existingUser->birth_date,
            ]);

            // Atualizar ou criar informações de associado
            if (isset($data['associate'])) {
                $existingUser->associate()->updateOrCreate([], $data['associate']);
            }

            // Notificar o usuário que o registro foi atualizado
            \Filament\Notifications\Notification::make()
                ->title('Usuário atualizado com sucesso!')
                ->body('O CPF já está vinculado a um funcionário. Informações de associado foram atualizadas.')
                ->success()
                ->send();

            // Retornar o registro existente para impedir a criação de um novo
            return $existingUser;
        }

        // Criar um novo registro se o CPF não existir
        return parent::handleRecordCreation($data);
    }







}
