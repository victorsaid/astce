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
<h1>Associados</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nome</th>
{{--        <th>Descrição</th>--}}
{{--        <th>Data</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
{{--            <td>{{ $meeting->description }}</td>--}}
{{--            <td>{{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y H:i') }}</td>--}}
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
