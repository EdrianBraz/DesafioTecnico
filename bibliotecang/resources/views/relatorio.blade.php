@extends('layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Livros Mais Emprestados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
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

        .container {
            width: 80%;
            margin: auto;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: white;
            background-color: blue;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
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
                    <td>{{ $item->titulo }}</td>
                    <td>{{ $item->autor }}</td>
                    <td>{{ $item->total_emprestimos }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <a href="{{ route('relatorio.pdf') }}" class="btn">Baixar PDF</a>
    </div>
</body>

</html>
@endsection