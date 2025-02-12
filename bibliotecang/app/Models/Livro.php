<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'ano_publicacao',
        'quantidade_estoque',
        'capa', 
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_livro');
    }

    protected static function booted()
    {
        // Evento que dispara quando o livro é criado ou atualizado
        static::saved(function ($livro) {
            // Verifica se o livro foi criado ou atualizado sem capa
            if (!$livro->capa) {
                // Atribui a imagem de fallback
                $livro->capa = $livro->salvarImagemFallback();
                $livro->save();
            }
        });
    }

    /**
     * Salva a imagem de fallback no S3.
     *
     * @return string|null
     */
    private function salvarImagemFallback()
    {
        try {
            // Caminho da imagem de fallback local (coloque ela em public/images/fallback.jpg)
            $caminhoImagemFallback = public_path('images/fallback.jpg');

            if (!file_exists($caminhoImagemFallback)) {
                throw new \Exception('Imagem de fallback não encontrada.');
            }

            // Lê a imagem e faz o upload para o S3
            $nomeArquivo = "capas/livro_{$this->id}_fallback.jpg";
            Storage::disk('s3')->put($nomeArquivo, file_get_contents($caminhoImagemFallback));

            // Retorna a URL pública da imagem salva
            return Storage::disk('s3')->url($nomeArquivo);
        } catch (\Exception $e) {
            \Log::error("Erro ao salvar imagem de fallback no S3: " . $e->getMessage());
            return null;
        }
    }
}
