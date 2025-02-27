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
        p{
            font-size:  10px;
        }
    </style>
</head>
<body>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ base_path('public/storage/logo/logo_astce.jpeg') }}"
         style="width: 150px; height: auto;"
         alt="Logo">
</div>
<h1>Folha de pagamento</h1>
<h3><strong>{{$payroll->name}}</strong></h3>
<h3><strong>Data da folha: </strong>{{ \Carbon\Carbon::parse($payroll->date)->format('d/m/Y') }}</h3>
<p>Emitido em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Associado</th>
        <th>Matrícula</th>
        <th>Valor</th>
        {{--        <th>Data</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach ($payments as $payment)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $payment->user->name }}</td>
            <td>{{ $payment->user->associate->enrollment }}</td>
            <td>{{ $payment->amount }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3" class="text-end"><strong>Total:</strong></td>
        <td><strong>{{ $payroll->total }}</strong></td>
    </tr>
    </tbody>

</table>
</body>
</html>
