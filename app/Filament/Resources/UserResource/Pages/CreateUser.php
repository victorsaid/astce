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
        // Define a senha como o CPF, caso nenhuma senha seja fornecida
        if (empty($data['password'])) {
            //dd($data['document']);
            $data['password'] = bcrypt($data['document']);
        }else{
            $data['password'] = bcrypt($data['password']);
        }
        if (!isset($data['role'])) {

            $data['role'] = 'Associate';
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Verificar se o CPF já existe no banco de dados
        $existingUser = \App\Models\User::where('document', $data['document'])->first();

        if ($existingUser) {
            // Atualizar os dados básicos do usuário existente, mas não alterar a senha
            $existingUser->update([
                'name' => $data['name'] ?? $existingUser->name,
                'email' => $data['email'] ?? $existingUser->email,
                'gender' => $data['gender'] ?? $existingUser->gender,
                'birth_date' => $data['birth_date'] ?? $existingUser->birth_date,
                'marital_status' => $data['marital_status'] ?? $existingUser->marital_status,
                'education_level' => $data['education_level'] ?? $existingUser->education_level,
                'blood_type' => $data['blood_type'] ?? $existingUser->blood_type,
                'photo' => $data['photo'] ?? $existingUser->photo,

            ]);

            // Atualizar ou criar informações de associado
//            if (isset($data['associate'])) {
//                $existingUser->associate()->updateOrCreate([], $data['associate']);
//            }

//            if (isset($data['employee'])) {
//                $existingUser->employee()->updateOrCreate([], $data['employee']);
//            }

            // Notificar o usuário que o registro foi atualizado
            \Filament\Notifications\Notification::make()
                ->title('Usuário atualizado com sucesso!')
                ->body('O CPF já está vinculado a um funcionário. Informações de associado foram atualizadas.')
                ->success()
                ->send();

            $existingUser->assignRole($data['role'] ?? 'Associate');
            // Retornar o registro existente para impedir a criação de um novo
            return $existingUser;
        }

        // Se o CPF não existir, criar um novo registro
        if (!$existingUser) {
            if (empty($data['password'])) {
                $data['password'] = bcrypt($data['document']);
            }

            $newUser = \App\Models\User::create($data);
            $newUser->assignRole($data['role'] ?? 'Associate');

            \Filament\Notifications\Notification::make()
                ->title('Usuário criado com sucesso!')
                ->body("Um novo usuário com CPF {$data['document']} foi criado.")
                ->success()
                ->send();

            return $newUser;
        }

        // Criar um novo registro se o CPF não existir
        return parent::handleRecordCreation($data);
    }


}
