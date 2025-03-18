<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f4f4f4;
            text-align: center;
            font-size: 12px;
            padding: 10px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ base_path('public/storage/logo/logo_astce.jpeg') }}"
         style="width: 150px; height: auto;"
         alt="Logo">
</div>
<h1>Beneficiários do Convênio</h1>
<p style="font-size: 10px;">Emitido em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
<div style="">
    <p><strong>Convênio:</strong> {{ $agreement->name }}</p>
    <p><strong>Descrição:</strong> {{ $agreement->description }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Matrícula</th>
        {{--        <th>Data</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach ($agreement->users as $user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->associate->enrollment }}</td>
            {{--            <td>{{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y H:i') }}</td>--}}
        </tr>
    @endforeach
    </tbody>
</table>
<div class="footer">
    <p style="margin-bottom: 2px"><strong>Associação dos Servidores do Tribunal de Contas do Estado do Maranhão - ASTCE/MA</strong></p>
    <p style="margin-top: 2px">Av. Carlos Cunha, s/nº - Jaracaty, São Luís - MA. CEP: 65.076-820 | Tel: (98) 2016-6055 | CNPJ: 05.092.067/0001-17 | Whatsapp: (98) 98271-0176</p>
</div>
</body>
</html>
