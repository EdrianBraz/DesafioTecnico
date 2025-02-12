<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    // 🔹 Método para obter os dados do relatório
    private function obterRelatorio()
    {
        return DB::table('emprestimos')
            ->join('livros', 'emprestimos.livro_id', '=', 'livros.id')
            ->select('livros.titulo', 'livros.autor', DB::raw('COUNT(emprestimos.id) as total_emprestimos'))
            ->groupBy('livros.id', 'livros.titulo', 'livros.autor')
            ->orderByDesc('total_emprestimos')
            ->get();
    }

    // 🔹 Exibe o relatório na tela
    public function index()
    {
        $relatorio = $this->obterRelatorio();
        return view('relatorio', compact('relatorio'));
    }

    // 🔹 Gera e baixa o PDF sem layout de navegação
    public function gerarPdf()
    {
        $relatorio = $this->obterRelatorio();

        // Renderiza uma view exclusiva para PDF sem cabeçalho e menu
        $pdf = Pdf::loadView('relatorio-pdf', compact('relatorio'));
        return $pdf->download('relatorio_emprestimos.pdf');
    }

    
}
