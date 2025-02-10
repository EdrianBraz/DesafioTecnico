<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        // Criação da chave de cache com os parâmetros de consulta
        $cacheKey = 'livros:' . md5(json_encode($request->all()));
    
        if (Redis::exists($cacheKey)) {
            $livros = collect(json_decode(Redis::get($cacheKey), true))->map(function ($livro) {
                return new Livro($livro); // Converte stdClass para instância do modelo Livro
            });
        } else {
            $query = Livro::with('categorias'); // Carregar categorias relacionadas
    
            if ($request->filled('titulo')) {
                $query->where('titulo', 'like', '%' . $request->titulo . '%');
            }
    
            if ($request->filled('autor')) {
                $query->where('autor', 'like', '%' . $request->autor . '%');
            }
    
            if ($request->filled('categorias')) {
                $query->whereHas('categorias', function ($q) use ($request) {
                    $q->whereIn('categorias.id', $request->categorias);
                });
            }
    
            if ($request->filled('ordenar_por') && $request->filled('ordem')) {
                $query->orderBy($request->ordenar_por, $request->ordem);
            }
    
            $livros = $query->get();
    
        }
    
        // Buscar categorias corretamente
        $categoriasCache = Redis::get('categorias_disponiveis');
        if ($categoriasCache) {
            $categorias = collect(json_decode($categoriasCache));
        } else {
            $categorias = \App\Models\Categoria::all(); // Buscar todas as categorias do banco
            Redis::setex('categorias_disponiveis', 600, $categorias->toJson());
        }
    
        return view('livros.index', compact('livros', 'categorias'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'             => 'required|string|max:255',
            'autor'              => 'required|string|max:255',
            'isbn'               => 'required|string|max:13|unique:livros,isbn',
            'ano_publicacao'     => 'required|integer',
            'quantidade_estoque' => 'required|integer',
            'categorias'         => 'required|array|exists:categorias,id', // Certifica que as categorias existem
        ]);
    
    // Criar o livro
    $livro = Livro::create($validated);

    // Associar as categorias ao livro
    $livro->categorias()->sync($request->categorias);

    // Limpar cache de categorias
    Redis::del('categorias_disponiveis');
    
    // Atualizar o cache com as novas categorias
    $categorias = \App\Models\Categoria::all();
    Redis::setex('categorias_disponiveis', 300, $categorias->toJson());

    return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso!');
}
    
}
