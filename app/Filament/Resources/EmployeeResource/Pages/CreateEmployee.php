<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

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
//        if(!isset($data['role'])) {
//            $data['role'] = 'Employee';
//        }
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Verificar se o CPF já existe no banco de dados
        $existingUser = \App\Models\User::where('document', $data['document'])->first();

        if($existingUser) {
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
            // Notificar o usuário que o registro foi atualizado
            \Filament\Notifications\Notification::make()
                ->title('Usuário atualizado com sucesso!')
                ->body('O CPF já está vinculado a um associado. Informações de associado foram atualizadas.')
                ->success()
                ->send();

            return $existingUser;
        }

        // Se o CPF não existir, criar um novo registro
        if (!$existingUser) {
            if (empty($data['password'])) {
                $data['password'] = bcrypt($data['document']);
            }

            $newUser = \App\Models\User::create($data);

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

    protected function afterCreate(): void
    {
        $employee = $this->record;
        //dd($employee->employee->commission_member);
        if($employee->employee->is_active == 1){
            $employee->assignRole($data['role'] ?? 'Employee');
        }
        if($employee->employee->commission_member == 1){
            $employee->assignRole($data['role'] ?? 'Commission_member');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
