<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Livros Mais Emprestados</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Relatório de Livros Mais Emprestados</h1>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Total de Empréstimos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relatorio as $item)
                <tr>
                    <td>{{ $item['titulo'] }}</td>
                    <td>{{ $item['autor'] }}</td>
                    <td>{{ $item['total_emprestimos'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
