<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Services\LivroService;
use Illuminate\Support\Facades\Cache;

class LivroController extends Controller
{
    protected $livroService;

    public function __construct(LivroService $livroService)
    {
        $this->livroService = $livroService;
    }

    public function index(Request $request)
    {
        $cacheKey = 'livros:' . md5(json_encode($request->all()));

        if (Redis::exists($cacheKey)) {
            $livros = collect(json_decode(Redis::get($cacheKey), true))->map(fn($livro) => new Livro($livro));
        } else {
            $query = Livro::with('categorias');

            if ($request->filled('titulo')) {
                $query->where('titulo', 'like', '%' . $request->titulo . '%');
            }

            if ($request->filled('autor')) {
                $query->where('autor', 'like', '%' . $request->autor . '%');
            }

            if ($request->filled('categorias')) {
                $query->whereHas('categorias', fn($q) => $q->whereIn('categorias.id', $request->categorias));
            }

            if ($request->filled('ordenar_por') && $request->filled('ordem')) {
                $query->orderBy($request->ordenar_por, $request->ordem);
            }

            $livros = $query->get();
        }

        $categorias = Cache::remember('categorias_disponiveis', 600, fn() => Categoria::all());

        return view('livros.index', compact('livros', 'categorias'));
    }

    public function show($id)
    {
        $livro = Livro::with('categorias')->findOrFail($id);
        return view('livros.show', compact('livro'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome', 'asc')->get();
        return view('livros.create', compact('categorias'));
    }

    public function edit($id)
    {
        $livro = Livro::findOrFail($id);
        $categorias = Categoria::orderBy('nome', 'asc')->get();
        return view('livros.edit', compact('livro', 'categorias'));
    }

    public function store(Request $request)
    {
        $livro = $this->livroService->salvarLivro($request);
        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso!');
    }

    public function update(Request $request, Livro $livro)
    {
        $this->livroService->salvarLivro($request, $livro);
        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $livro = Livro::findOrFail($id);
        $this->livroService->excluirLivro($livro);
        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso!');
    }

    public function importarSinopse($isbn)
    {
        $sinopse = $this->livroService->importarSinopse($isbn);

        if ($sinopse) {
            return back()->with('success', 'Sinopse importada com sucesso!');
        }

        return back()->with('error', 'Livro não encontrado no Google Books.');
    }

    public function livrosMaisAlugados()
    {
        $livrosMaisAlugados = Livro::join('emprestimos', 'livros.id', '=', 'emprestimos.livro_id')
            ->select('livros.id', 'livros.titulo', 'livros.autor', 'livros.capa', DB::raw('COUNT(emprestimos.id) as total_emprestimos'))
            ->groupBy('livros.id', 'livros.titulo', 'livros.autor', 'livros.capa')
            ->orderByDesc('total_emprestimos')
            ->limit(10)
            ->get();

        return $livrosMaisAlugados;
    }
}