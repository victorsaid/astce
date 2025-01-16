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

<div style="margin-bottom: 20px; font-size: 16px; color: #000000;">
    <p style="margin: 0; line-height: 1.6; text-align: justify;">
        <strong>Descrição:</strong> {{ $meeting->description }}
    </p>
</div>


@if ($meeting->participants)
    <h4 style="margin-top: 20px;">Participantes:</h4>
    <ul>
        @foreach ($meeting->participants as $participant)
            <li>{{ $participant->name }}</li>
        @endforeach
    </ul>
@endif

@if ($meeting->topics->isNotEmpty())
    <div style="margin-bottom: 30px;">
        <h3 style="font-size: 18px; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-bottom: 20px;">Pautas da Reunião:</h3>
        @foreach ($meeting->topics as $topic)
            <div style="margin-bottom: 15px;">
                <p style="margin: 0; font-size: 16px; font-weight: bold; color: #444;">{{ $topic->title }}:</p>
                <p style="margin: 5px 0; font-size: 15px; color: #555;">{{ $topic->description }}</p>
                <p style="margin: 5px 0; font-size: 14px; line-height: 1.6; text-align: justify; color: #666;">{{ $topic->content }}</p>
            </div>
        @endforeach
    </div>

@endif

@if ($meeting->photos)
    <div class="section">
        <p><strong>Anexos:</strong></p>
        <ul>
            @foreach (is_array($meeting->photos) ? $meeting->photos : [$meeting->photos] as $photo)
                <div>
                    <img src="{{ base_path('public/storage/' . $photo) }}" alt="Anexo" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                </div>
            @endforeach
        </ul>
    </div>
@endif

    <div>
        <h2 style="text-align: center; margin-bottom: 40px;">Linhas de Assinatura</h2>

        <table style="width: 100%; text-align: center; margin-top: 50px;">
            <tr>
                <!-- Assinatura 1 -->
                <td style="width: 50%; padding: 0 10px;">
                    <div style="border-top: 1px solid #000; margin: 0 auto; width: 80%;"></div>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">João Silva</p>
                </td>

                <!-- Assinatura 2 -->
                <td style="width: 50%; padding: 0 10px;">
                    <div style="border-top: 1px solid #000; margin: 0 auto; width: 80%;"></div>
                    <p style="margin-top: 10px; font-size: 14px; color: #333;">Maria Oliveira</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
