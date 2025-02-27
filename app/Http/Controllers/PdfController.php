<?php

namespace App\Http\Controllers;

use App\Models\Agreements;
use App\Models\Meeting;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function pdfMeetings(User $user)
    {
        $meetings = Meeting::all();
        //dd($meetings);

        $pdf = Pdf::loadView('pdf.example', ['meetings' => $meetings]);
        $pdf->set_option('isRemoteEnabled', true);
        return $pdf->download('example.pdf');
    }

    public function exportMeetingPdf(Meeting $meeting)
    {
        // Carrega os tópicos relacionados
        $meeting->load('topics', 'participants'); // Certifique-se de que a relação 'topics' está configurada no modelo Meeting

        // Renderiza o PDF
        $pdf = Pdf::loadView('pdf.meeting', ['meeting' => $meeting]);

        // Retorna o PDF para download
        return $pdf->stream("reuniao_{$meeting->id}.pdf");
    }

    public function pdfUsers(Request $request)
    {
        $orderBy = $request->get('order_by', 'name'); // Ordenação padrão
        $onlyActive = filter_var($request->get('only_active', true), FILTER_VALIDATE_BOOLEAN); // Converte para booleano

        // Obtém a query dos usuários
        $users = $this->getUsersQuery($onlyActive, $orderBy)->get();

        $pdf = Pdf::loadView('pdf.pdf_users', ['users' => $users]);
        $pdf->set_option('isRemoteEnabled', true);

        return $pdf->stream('pdf_users.pdf');
    }

    private function getUsersQuery(bool $onlyActive, string $orderBy)
    {
        $query = User::query()
            ->join('associates', 'users.id', '=', 'associates.user_id') // Usa JOIN para garantir que o usuário tenha associado
            ->select('users.*', 'associates.enrollment', 'associates.is_active');
    //dd($query->toSql());
        // Se for para filtrar apenas usuários ativos
        if ($onlyActive) {
            $query->where('associates.is_active', true);
        }

        // Aplicar ordenação corretamente
        if ($orderBy === 'enrollment') {
            $query->orderBy('associates.enrollment', 'asc');
        } else {
            $query->orderBy('users.name', 'asc');
        }
        return $query;
    }
    public function memberDeclaration(User $user)
    {
        $employee = Auth::user();
        $pdf = Pdf::loadView('pdf.member_declaration', ['user' => $user, 'employee' => $employee]);
        $pdf->set_option('isRemoteEnabled', true);

        return $pdf->stream('member_declaration.blade.php');
    }

    public function beneficiariesAgreement(Agreements $agreement)
    {
        $agreement->load('users');
        //dd($agreement->users);
        $pdf = Pdf::loadView('pdf.beneficiaries_agreement', ['agreement' => $agreement]);
        $pdf->set_option('isRemoteEnabled', true);

        return $pdf->stream('beneficiaries_agreement.blade.php');
    }

    public function payrollExport(Payroll $payroll, Request $request)
    {
        $payroll->load('payments.user.associate'); // Carrega os relacionamentos corretamente

        // Obtém o critério de ordenação da requisição
        $orderBy = $request->query('order_by', 'name');

        // Aplica a ordenação
        $sortedPayments = $payroll->payments->sortBy(fn($payment) =>
        $orderBy === 'enrollment' ? $payment->user->associate->enrollment : $payment->user->name
        );

        // Gera o PDF com os pagamentos ordenados
        $pdf = Pdf::loadView('pdf.pdf_payrollExport', [
            'payroll' => $payroll,
            'payments' => $sortedPayments,
        ]);

        $pdf->set_option('isRemoteEnabled', true);
        return $pdf->stream('pdf_payrollExport.pdf');

    }
}
