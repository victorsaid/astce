<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Garantir que o registro seja uma instância de User
        if ($record instanceof \App\Models\User) {
            // Remova a formatação do CPF
            $data['document'] = str_replace(['.', '-'], '', $data['document']);

            // Atualize os dados do registro
            $updateData = [
                'name' => $data['name'] ?? $record->name,
                'email' => $data['email'] ?? $record->email,
                'gender' => $data['gender'] ?? $record->gender,
                'birth_date' => $data['birth_date'] ?? $record->birth_date,
                'marital_status' => $data['marital_status'] ?? $record->marital_status,
                'education_level' => $data['education_level'] ?? $record->education_level,
                'photo' => $data['photo'] ?? $record->photo,
                'blood_type' => $data['blood_type'] ?? $record->blood_type,

            ];

            // Atualizar a senha somente se fornecida
            if (!empty($data['password'])) {
                //dd($data['password']);
                $updateData['password'] = bcrypt($data['password']);
            }

            $record->update($updateData);

            // Atualizar ou criar informações relacionadas
            if (isset($data['employee'])) {
                $record->employee()->updateOrCreate([], $data['employee']);
            }

            // Notificar o usuário
            \Filament\Notifications\Notification::make()
                ->title('Usuário atualizado com sucesso!')
                ->body('As informações do usuário foram atualizadas corretamente.')
                ->success()
                ->send();
        }

        return $record;
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
