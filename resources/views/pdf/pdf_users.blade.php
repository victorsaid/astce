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
    </style>
</head>
<body>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ base_path('public/storage/logo/logo_astce.jpeg') }}"
         style="width: 150px; height: auto;"
         alt="Logo">
</div>
<h1>Associados</h1>
<p>Emitido em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
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
    @foreach ($users as $user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->associate->enrollment }}</td>
{{--            <td>{{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y H:i') }}</td>--}}
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
