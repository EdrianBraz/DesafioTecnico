<?php

namespace App\Services;

use App\Models\Livro;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class LivroService
{
    public function salvarLivro(Request $request, Livro $livro = null)
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:13',
            'ano_publicacao' => 'nullable|integer',
            'quantidade_estoque' => 'required|integer|min:0', // Certifique-se de validar esse campo
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
            'capa' => 'nullable|image|max:2048',
        ]);

        if (!$livro) {
            $livro = new Livro();
        }

        $livro->fill($dados);
        $livro->save();

        if ($request->has('categorias')) {
            $livro->categorias()->sync($dados['categorias']);
        }

        // Upload de capa ou busca automática
        if ($request->hasFile('capa')) {
            $livro->capa = $request->file('capa')->store('capas', 's3');
        } elseif ($livro->isbn) {
            $livro->capa = $this->buscarCapaPorISBN($livro->isbn);
        }

        $livro->save();

        return $livro;
    }

    public function buscarCapaPorISBN($isbn)
    {
        $apis = [
            "https://covers.openlibrary.org/b/isbn/$isbn-L.jpg",
            "https://api.coverapi.com/isbn/$isbn/cover"
        ];

        foreach ($apis as $url) {
            if (@getimagesize($url)) {
                return $url;
            }
        }

        return null;
    }

    public function importarSinopse($isbn)
    {
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
        $response = Http::get($apiUrl);
        $data = $response->json();

        if (!empty($data['items'])) {
            $livroInfo = $data['items'][0]['volumeInfo'];
            $sinopse = $livroInfo['description'] ?? 'Sinopse não disponível';

            Livro::where('isbn', $isbn)->update(['sinopse' => $sinopse]);

            return $sinopse;
        }

        return null;
    }

    public function excluirLivro(Livro $livro)
    {
        if ($livro->capa) {
            Storage::disk('s3')->delete($livro->capa);
        }

        $livro->categorias()->detach();
        $livro->delete();

        return true;
    }
}
