<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Livro;
use App\Models\Emprestimo;


class HomeController extends Controller
{

    
    public function index()
    {
        $livrosMaisEmprestados = DB::table('livros')
        ->leftJoin('emprestimos', 'livros.id', '=', 'emprestimos.livro_id') 
        ->select('livros.id', 'livros.titulo', 'livros.capa', 
            DB::raw('COUNT(emprestimos.id) as total_emprestimos'))
        ->groupBy('livros.id', 'livros.titulo', 'livros.capa')
        ->orderByDesc('total_emprestimos') 
        ->get();
    

        // Contagem de registros
        $totalUsuarios = Usuario::count();
        $totalLivros = Livro::count();

        
        $usuarios = Usuario::orderBy('nome', 'asc')->get();
        $livros = Livro::orderBy('titulo', 'asc')->get();

        // Verifica se há uma coluna válida para identificar empréstimos ativos
        $totalEmprestimos = Emprestimo::whereNull('data_devolucao')->count(); 
        $emprestimos = Emprestimo::with(['usuario', 'livro'])->get();

        return view('home', compact('usuarios', 'livros', 'emprestimos', 'livrosMaisEmprestados', 'totalUsuarios', 'totalLivros', 'totalEmprestimos'));
    }
}
