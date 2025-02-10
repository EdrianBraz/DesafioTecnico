<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'livro_id',
        'data_emprestimo',
        'data_devolucao',
    ];

    // Relacionamento com usuário
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relacionamento com livro
    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
}
