<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{
    public function show($id)
    {
        $livro = Livro::with('categorias')->findOrFail($id);
        return view('livros.show', compact('livro'));
    }


    public function index(Request $request)
    {
        // Criação da chave de cache com os parâmetros de consulta
        $cacheKey = 'livros:' . md5(json_encode($request->all()));

        if (Redis::exists($cacheKey)) {
            $livros = collect(json_decode(Redis::get($cacheKey), true))->map(function ($livro) {
                return new Livro($livro); // Converte stdClass para instância do modelo Livro
            });
        } else {
            $query = Livro::with('categorias');
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

        // Buscar categorias 
        $categoriasCache = Redis::get('categorias_disponiveis');
        if ($categoriasCache) {
            $categorias = collect(json_decode($categoriasCache));
        } else {
            $categorias = \App\Models\Categoria::all();
            Redis::setex('categorias_disponiveis', 600, $categorias->toJson());
        }

        return view('livros.index', compact('livros', 'categorias'));
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
    
    private function buscarCapaLivro($titulo, $isbn = null)
    {
        $query = $isbn ?: $titulo;
    
        // Primeira tentativa com a API do Google Books
        $capaUrl = $this->buscarCapaGoogleBooks($query);
        if ($capaUrl) {
            return $capaUrl;
        }
    
        // Se não encontrar, tenta com a Open Library API
        $capaUrl = $this->buscarCapaOpenLibrary($isbn);
        if ($capaUrl) {
            return $capaUrl;
        }
    
        // Se não encontrar na Open Library, tenta com a Cover API
        $capaUrl = $this->buscarCapaCoverApi($isbn);
        if ($capaUrl) {
            return $capaUrl;
        }
    
        // Se não encontrar em nenhuma das APIs
        Log::error("Capa não encontrada para o livro: " . $query);  // Log de erro
        return null;
    }
    
    private function buscarCapaGoogleBooks($query)
    {
        $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query);
    
        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
                $capaUrl = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
                Log::info("Capa encontrada no Google Books: " . $capaUrl);  // Log da URL da capa
                return $capaUrl;
            }
        }
        return null;
    }
    
    private function buscarCapaOpenLibrary($isbn)
    {
        if ($isbn) {
            // A Open Library permite buscar a capa diretamente via ISBN
            $url = "https://covers.openlibrary.org/b/id/{$isbn}-L.jpg";
            $response = @file_get_contents($url);  // Usando file_get_contents para buscar a capa
    
            if ($response) {
                Log::info("Capa encontrada na Open Library: " . $url);  // Log da URL da capa
                return $url;
            }
        }
        return null;
    }
    
    private function buscarCapaCoverApi($isbn)
    {
        if ($isbn) {
            // A Cover API também pode ser usada com ISBN
            $url = "https://covers.openlibrary.org/b/id/{$isbn}-L.jpg";
            $response = Http::get($url);
    
            if ($response->successful()) {
                Log::info("Capa encontrada na Cover API: " . $url);  // Log da URL da capa
                return $url;
            }
        }
        return null;
    }
    

    public function baixarESalvarCapa($url, $livroId)
    {
        try {
            // Baixa a imagem
            $response = Http::get($url);

            if (!$response->successful()) {
                throw new \Exception('Falha ao baixar a imagem.');
            }

            // Define o nome do arquivo
            $nomeArquivo = "capas/livro_{$livroId}_" . time() . ".jpg";

            // Salva no S3
            Log::info("Iniciando upload para S3: $nomeArquivo");

            Storage::disk('s3')->put($nomeArquivo, $response->body());

            Log::info("Upload concluído: " . Storage::disk('s3')->url($nomeArquivo));

            // Gera a URL pública da imagem armazenada no S3
            $caminhoS3 = Storage::disk('s3')->url($nomeArquivo);

            // Log para depuração
            Log::info("Capa salva no S3: " . $caminhoS3);

            return $caminhoS3; // Retorna a URL pública do arquivo armazenado
        } catch (\Exception $e) {
            Log::error("Erro ao salvar capa no S3: " . $e->getMessage());
            return null; // Se der erro, retorna nulo
        }
    }

    public function importarSinopse($isbn)
    {
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";

        $response = Http::get($apiUrl);
        $data = $response->json();

        if (!empty($data['items'])) {
            $livroInfo = $data['items'][0]['volumeInfo'];

            $sinopse = $livroInfo['description'] ?? 'Sinopse não disponível';

            // Atualizar o livro no banco de dados
            Livro::where('isbn', $isbn)->update(['sinopse' => $sinopse]);

            return back()->with('success', 'Sinopse importada com sucesso!');
        }

        return back()->with('error', 'Livro não encontrado no Google Books.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'ano_publicacao' => 'required|integer|min:1000|max:' . date('Y'),
            'quantidade_estoque' => 'required|integer|min:0',
            'categorias' => 'array'
        ]);

        // Verificar se o livro já existe com o mesmo ISBN
        $livroExistente = Livro::where('isbn', $request->isbn)->first();

        if ($livroExistente) {
            // Se o livro já existe, apenas atualiza a quantidade de estoque
            $livroExistente->quantidade_estoque += $request->quantidade_estoque;
            $livroExistente->save();

            // Associa as categorias ao livro já existente
            $livroExistente->categorias()->sync($request->categorias ?? []);

            // Verifica se o livro já possui capa, caso contrário, atribui a capa de fallback
            if (!$livroExistente->capa) {
                $livroExistente->capa = $livroExistente->salvarImagemFallback();
                $livroExistente->save();
            }

            return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso! Quantidade de estoque aumentada.');
        } else {
            // Se o livro não existir, cria um novo livro
            $livro = Livro::create([
                'titulo' => $request->titulo,
                'autor' => $request->autor,
                'isbn' => $request->isbn,
                'ano_publicacao' => $request->ano_publicacao,
                'quantidade_estoque' => $request->quantidade_estoque,
            ]);

            // Associa as categorias ao livro novo
            $livro->categorias()->attach($request->categorias ?? []);

            // Buscar e salvar a capa automaticamente
            $capaUrl = $this->buscarCapaLivro($request->titulo, $request->isbn);
            if ($capaUrl) {
                $caminhoS3 = $this->baixarESalvarCapa($capaUrl, $livro->id);
                if ($caminhoS3) {
                    $livro->update(['capa' => $caminhoS3]); // Atualiza a URL da capa no banco
                }
            } else {
                // Caso não encontre capa, aplica a imagem de fallback
                $livro->capa = $livro->salvarImagemFallback();
                $livro->save();
            }

            return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso!');
        }
    }


    public function update(Request $request, $id)
    {
        $livro = Livro::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:livros,isbn,' . ($livro->id ?? 'NULL'),
            'ano_publicacao' => 'required|integer|min:1000|max:' . date('Y'),
            'quantidade_estoque' => 'required|integer|min:0',
            'categorias' => 'array'
        ]);

        // Atualiza os dados do livro
        $livro->update([
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'isbn' => $request->isbn,
            'ano_publicacao' => $request->ano_publicacao,
            'quantidade_estoque' => $request->quantidade_estoque,
        ]);

        // Atualiza as categorias associadas ao livro
        $livro->categorias()->sync($request->categorias ?? []);

        // Se o livro não tiver capa, buscar e salvar automaticamente
        if (!$livro->capa) {
            $capaUrl = $this->buscarCapaLivro($request->titulo, $request->isbn);
            if ($capaUrl) {
                $caminhoS3 = $this->baixarESalvarCapa($capaUrl, $livro->id);
                if ($caminhoS3) {
                    $livro->update(['capa' => $caminhoS3]); // Atualiza a URL da capa no banco
                }
            }
        }

        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $livro = Livro::findOrFail($id);

        // Verificar se o livro tem uma capa e deletar do S3
        if ($livro->capa) {
            // Excluir a capa no S3
            $nomeArquivo = basename($livro->capa); // Obtém o nome do arquivo
            Storage::disk('s3')->delete("capas/$nomeArquivo");
            Log::info("Capa excluída do S3: " . $livro->capa);
        }

        // Excluir o livro e suas categorias associadas
        $livro->categorias()->detach(); // Remove todas as associações na tabela pivot
        $livro->delete();

        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso!');
    }

    public function livrosMaisAlugados()
    {
        // Obtém os livros mais emprestados
        $livrosMaisAlugados = Livro::join('emprestimos', 'livros.id', '=', 'emprestimos.livro_id')
            ->select('livros.id', 'livros.titulo', 'livros.autor', 'livros.capa', DB::raw('COUNT(emprestimos.id) as total_emprestimos'))
            ->groupBy('livros.id', 'livros.titulo', 'livros.autor', 'livros.capa')
            ->orderByDesc('total_emprestimos')
            ->limit(10)  // Limita aos 10 mais alugados
            ->get();

        return $livrosMaisAlugados;
    }
};
