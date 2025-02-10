<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Livro;
use App\Models\Categoria;

class MigrateCategorias extends Command
{
    protected $signature = 'migrate:categorias';
    protected $description = 'Migrar categorias armazenadas como string separada por vírgulas para a relação correta many-to-many';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $livros = Livro::all();

        foreach ($livros as $livro) {
            if (!$livro->categoria) {
                continue; // Ignora se não houver categorias
            }

            // Divide as categorias armazenadas em string separada por vírgulas
            $categoriasArray = explode(',', $livro->categoria);

            // Limpeza (remoção de espaços extras)
            $categoriasArray = array_map('trim', $categoriasArray);

            $categoriaIds = [];

            foreach ($categoriasArray as $nomeCategoria) {
                // Verifica se a categoria já existe ou cria uma nova
                $categoria = Categoria::firstOrCreate(['nome' => $nomeCategoria]);

                // Adiciona o ID da categoria para a associação
                $categoriaIds[] = $categoria->id;
            }

            // Associa todas as categorias ao livro (many-to-many)
            $livro->categorias()->sync($categoriaIds);

            $this->info("Categorias importadas para o livro: {$livro->titulo}");
        }

        $this->info('Todas as categorias foram importadas com sucesso!');
    }
}
