<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'livros:' . md5(json_encode($request->all()));
    
        // Tenta buscar os livros no cache Redis
        if (Redis::exists($cacheKey)) {
            $livros = collect(json_decode(Redis::get($cacheKey)));
        } else {
            // Constrói a query com os filtros
            $query = Livro::query();
    
            if ($request->filled('titulo')) {
                $query->where('titulo', 'like', '%' . $request->titulo . '%');
            }
            if ($request->filled('autor')) {
                $query->where('autor', 'like', '%' . $request->autor . '%');
            }
            if ($request->filled('categoria')) {
                $query->where('categoria', $request->categoria);
            }
    
            // Opções de ordenação
            $ordenarPor = $request->get('ordenar_por', 'titulo'); // Padrão: ordenar por título
            $ordem = $request->get('ordem', 'asc'); // Padrão: ordem ascendente
    
            if ($ordenarPor == 'estoque') {
                $query->orderBy('quantidade_estoque', $ordem);
            } else {
                $query->orderBy('titulo', $ordem);
            }
    
            $livros = $query->get();
            Redis::setex($cacheKey, 600, $livros->toJson());
        }
    
        // Recupera as categorias do cache ou do banco de dados
        if (Redis::exists('categorias_disponiveis')) {
            $categorias = collect(json_decode(Redis::get('categorias_disponiveis')));
        } else {
            $categorias = Livro::distinct()->pluck('categoria');
            Redis::setex('categorias_disponiveis', 600, $categorias->toJson());
        }
    
        return view('livros.index', compact('livros', 'categorias'));
    }
    

    public function create()
    {
        return view('livros.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'             => 'required|string|max:255',
            'autor'              => 'required|string|max:255',
            'isbn'               => 'required|string|max:13|unique:livros,isbn',
            'ano_publicacao'     => 'required|integer',
            'categoria'          => 'required|string|max:255',
            'quantidade_estoque' => 'required|integer',
        ]);

        // Cria o livro no banco
        Livro::create($validated);

        // Remove todas as chaves de cache relacionadas a livros
        $keys = Redis::keys('livros:*');
        foreach ($keys as $key) {
            Redis::del($key);
        }

        // Remove o cache de categorias para manter atualizado
        Redis::del('categorias_disponiveis');

        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso!');
    }
}
