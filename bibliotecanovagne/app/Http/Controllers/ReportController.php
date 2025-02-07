<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Emprestimo;
use App\Models\Livro;
use Barryvdh\DomPDF\Facade\PDF; 
class ReportController extends Controller
{
    /**
     * Gera o relatório de livros mais emprestados.
     */
    public function relatorioLivrosMaisEmprestados()
    {
        // Consulta que agrupa os empréstimos por livro e conta quantos foram realizados
        $livrosEmprestados = Emprestimo::selectRaw('livro_id, COUNT(*) as total_emprestimos')
            ->groupBy('livro_id')
            ->orderByDesc('total_emprestimos')
            ->get();

        // Carrega os dados do livro com base no relacionamento ou, alternativamente, pode juntar a tabela "livros"
        // Aqui vamos montar um array com os dados do livro e a contagem
        $relatorio = $livrosEmprestados->map(function ($item) {
            $livro = Livro::find($item->livro_id);
            return [
                'titulo'             => $livro ? $livro->titulo : 'Livro não encontrado',
                'autor'              => $livro ? $livro->autor : 'Desconhecido',
                'total_emprestimos'  => $item->total_emprestimos,
            ];
        });

        // Gerar o PDF utilizando a view 'reports.livros_mais_emprestados'
        $pdf = PDF::loadView('reports.livros_mais_emprestados', ['relatorio' => $relatorio]);

        // Retorna o PDF para download ou visualização
        return $pdf->download('relatorio_livros_mais_emprestados.pdf');
        // Caso prefira visualizar no browser, utilize: return $pdf->stream('relatorio.pdf');
    }
}
