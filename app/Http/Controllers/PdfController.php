<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function pdfUsers()
    {
        $users = User::whereHas('associate', function ($query) {
            $query->where('is_active', true); // Filtra associados ativos
        })
            ->with(['associate' => function ($query) {
                $query->where('is_active', true); // Carrega apenas associados ativos
            }])
            ->get();

        $pdf = Pdf::loadView('pdf.pdf_users', ['users' => $users]);
        $pdf->set_option('isRemoteEnabled', true);
        return $pdf->stream('pdf_users.pdf');
    }
}
