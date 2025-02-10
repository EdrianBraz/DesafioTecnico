<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'ano_publicacao',
        'quantidade_estoque',
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_livro');
    }
}
