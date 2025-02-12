<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Obtém os livros mais emprestados
        $livrosMaisEmprestados = DB::table('emprestimos')
            ->join('livros', 'emprestimos.livro_id', '=', 'livros.id')
            ->select('livros.id', 'livros.titulo', 'livros.capa', DB::raw('COUNT(emprestimos.id) as total_emprestimos'))
            ->groupBy('livros.id', 'livros.titulo', 'livros.capa')
            ->orderByDesc('total_emprestimos')
            ->limit(5) // Limita para os 5 mais emprestados
            ->get();

        // Passa a variável para a view
        return view('home', compact('livrosMaisEmprestados'));
    }
}
