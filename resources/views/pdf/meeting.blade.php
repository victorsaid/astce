<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ata da Reunião - {{ $meeting->title }}</title>
{{--    <style>--}}
{{--        body {--}}
{{--            font-family: Arial, sans-serif;--}}
{{--            margin: 20px;--}}
{{--            line-height: 1.6;--}}
{{--        }--}}
{{--        h1, h2 {--}}
{{--            text-align: center;--}}
{{--            margin-bottom: 20px;--}}
{{--        }--}}
{{--        .section {--}}
{{--            margin-bottom: 20px;--}}
{{--        }--}}
{{--        .content {--}}
{{--            text-align: justify;--}}
{{--        }--}}
{{--    </style>--}}
</head>
<body>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ base_path('public/storage/logo/Logoastce.png') }}"
         style="width: 150px; height: auto;"
         alt="Logo">
</div>

<h2 style="text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 0px;">Ata de Reunião</h2>
<h3 style="font-size: 20px; color: #000000; text-transform: uppercase; margin-bottom: 15px;">{{ $meeting->title }}</h3>

<div style="margin-bottom: 0px; font-size: 16px; color: #000000;">
    <p style="margin: 0; line-height: 1.6;"><strong>Data e Hora:</strong> {{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y H:i') }}</p>
</div>

<div style="font-size: 16px; color: #000000; text-align: justify; line-height: 1.6;">
    <!-- Descrição da Reunião -->
{{--    <strong>Descrição:</strong>--}} {{ $meeting->description }}.

    @if ($meeting->topics->isNotEmpty())
        <!-- Pautas da Reunião -->
        @foreach ($meeting->topics as $topic)
            {{ $topic->title }}: {{ $topic->description }} {{ $topic->content }}
        @endforeach
    @endif
</div>




{{--@if ($meeting->participants)--}}
{{--    <h4 style="margin-top: 20px;">Participantes:</h4>--}}
{{--    <ul>--}}
{{--        @foreach ($meeting->participants as $participant)--}}
{{--            <li>{{ $participant->name }}</li>--}}
{{--        @endforeach--}}
{{--    </ul>--}}
{{--@endif--}}


{{--@if ($meeting->photos)--}}
{{--    <div class="section">--}}
{{--        <p><strong>Anexos:</strong></p>--}}
{{--        <ul>--}}
{{--            @foreach (is_array($meeting->photos) ? $meeting->photos : [$meeting->photos] as $photo)--}}
{{--                <div>--}}
{{--                    <img src="{{ base_path('public/storage/' . $photo) }}" alt="Anexo" style="max-width: 100%; height: auto; margin-bottom: 10px;">--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--@endif--}}

    <div>
        <h2 style="text-align: center; margin-bottom: 40px;">Linhas de Assinatura</h2>

        <table style="width: 100%; text-align: center; margin-top: 50px;">
            <tr>
                <!-- Assinatura 1 -->
                <td style="width: 50%; padding: 0 10px;">
                    <div style="border-top: 1px solid #000; margin: 0 auto; width: 80%;"></div>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">João Silva</p>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Secretário Geral</p>
                </td>

                <!-- Assinatura 2 -->
                <td style="width: 50%; padding: 0 10px;">
                    <div style="border-top: 1px solid #000; margin: 0 auto; width: 80%;"></div>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Maria Oliveira</p>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Secretário de finanças</p>
                </td>
            </tr>
            <br><br><br>
            <tr>
                <!-- Assinatura 3 -->
                <td style="width: 50%; padding: 0 10px;">
                    <div style="border-top: 1px solid #000; margin: 0 auto; width: 80%;"></div>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">João Silva</p>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Primeira Suplente</p>
                </td>

                <!-- Assinatura 4 -->
                <td style="width: 50%; padding: 0 10px;">
                    <div style="border-top: 1px solid #000; margin: 0 auto; width: 80%;"></div>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Maria Oliveira</p>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Segundo Suplente</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
