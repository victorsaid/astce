<?php

namespace App\Filament\Resources\AgreementPaymentResource\Pages;

use App\Filament\Resources\AgreementPaymentResource;
use App\Models\AgreementPayment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreementPayment extends CreateRecord
{
    protected static string $resource = AgreementPaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Retornamos apenas os dados do cabeçalho (não usados diretamente)
        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        // Cria os pagamentos individualmente
        foreach ($data['payments'] as $payment) {
            AgreementPayment::create([
                'agreement_id' => $data['agreement_id'],
                'user_id' => $payment['user_id'],
                'value' => $payment['value'],
                'payment_date' => $data['payment_date'],
                'status' => $payment['status'] ?? 'pending',
            ]);
        }

        Notification::make()
            ->title('Folha de pagamentos registrada com sucesso!')
            ->success()
            ->send();
    }

    // Evita que o Filament crie um registro principal inútil
    protected function shouldCreateAnother(): bool
    {
        return false;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Retornamos um modelo vazio só pra evitar erro (nunca será usado)
        return new AgreementPayment();
    }
}
