<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Livro;
use App\Models\Emprestimo;

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
            ->limit(5)
            ->get();

        // Contagem de registros
        $totalUsuarios = Usuario::count();
        $totalLivros = Livro::count();

        // Verifica se há uma coluna válida para identificar empréstimos ativos
        $totalEmprestimos = Emprestimo::whereNull('data_devolucao')->count(); // Conta empréstimos não devolvidos

        // Passa todas as variáveis para a view
        return view('home', compact('livrosMaisEmprestados', 'totalUsuarios', 'totalLivros', 'totalEmprestimos'));
    }
}
