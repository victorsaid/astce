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
        }

        td {
            border: none; /* Remove qualquer borda */
        }

        th {
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .assinatura {
            text-align: center;
            vertical-align: middle; /* Centraliza verticalmente */
        }
        .linha-assinatura {
            width: 300px;
            border-top: 1px solid #000;
            margin: 30px auto 5px auto;
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
    <div class="container">
        <h1 style="text-align: center;">Declaração de Associado</h1>
        <p style="text-align: right; margin: 50px 0px;">
            <strong>São Luís, {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}</strong>
        </p>


        <p style="text-align: justify">
            Declaramos, para os devidos fins, que o(a) associado(a) <strong>{{ $user->name }}</strong>,
            portador(a) do CPF <strong>{{ $user->document }}</strong> e matrícula <strong>{{ $user->associate->enrollment }}</strong>,
            é membro regular da <strong>ASTCE - Associação dos Servidores do Tribunal de Contas do Estado do Maranhão</strong>, tendo como seus dependentes: {{$dependants}},
            encontra-se em dia com suas obrigações estatutárias e gozando de plenos direitos.
        </p>
    </div>

    <table>
        <tr>
            <td class="assinatura">
                <br><br><br><br><br><br>
                <div class="linha-assinatura"></div>
                <p style="margin-bottom: 2px;"><strong>{{$employee->name}}</strong></p>
                <p style="margin-top: 2px;">
                    @if($employee->hasRole('Super_admin'))
                        Administrador
                    @else
                        {{$employee->employee->position}}
                    @endif
                </p>
            </td>
        </tr>
    </table>
    <div class="footer">
        <p style="margin-bottom: 2px"><strong>Associação dos Servidores do Tribunal de Contas do Estado do Maranhão - ASTCE/MA</strong></p>
        <p style="margin-top: 2px">Av. Carlos Cunha, s/nº - Jaracaty, São Luís - MA. CEP: 65.076-820 | Tel: (98) 2016-6055 | CNPJ: 05.092.067/0001-17 | Whatsapp: (98) 98271-0176</p>
    </div>
    </body>
</html>
