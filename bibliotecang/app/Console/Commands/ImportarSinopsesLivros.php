<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Livro;

class ImportarSinopsesLivros extends Command
{
    protected $signature = 'livros:importar-sinopses';
    protected $description = 'Busca e adiciona sinopse dos livros salvos no banco através do Google Books API';

    public function handle()
    {
        $livros = Livro::whereNull('sinopse')->get(); // Apenas livros sem sinopse

        if ($livros->isEmpty()) {
            $this->info('Todos os livros já possuem sinopse.');
            return;
        }

        foreach ($livros as $livro) {
            $isbn = $livro->isbn;
            $titulo = $livro->titulo;
            $this->info("Buscando sinopse para: {$titulo}");

            $sinopse = $this->buscarSinopse($isbn, $titulo);

            if ($sinopse) {
                $livro->update(['sinopse' => $sinopse]);
                $this->info("✅ Sinopse adicionada para: {$titulo}");
            } else {
                $this->warn("⚠️ Sinopse não encontrada para: {$titulo}");
            }

            sleep(1); // Evita excesso de requisições na API
        }

        $this->info('Importação de sinopses concluída!');
    }

    private function buscarSinopse($isbn, $titulo)
    {
        $query = $isbn ? "isbn:$isbn" : urlencode($titulo);
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=$query";

        $response = Http::get($apiUrl);
        $data = $response->json();

        if (!empty($data['items'][0]['volumeInfo']['description'])) {
            return $data['items'][0]['volumeInfo']['description'];
        }

        return null; // Retorna null caso não encontre
    }
}
