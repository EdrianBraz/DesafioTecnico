<?php 
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class S3UploadTest extends TestCase
{
    public function test_upload_de_capa_para_s3()
    {
        Storage::fake('s3'); // Simula o S3
    
        $controller = new \App\Http\Controllers\LivroController();
        
        // URL de teste (pode ser qualquer imagem pública)
        $fakeImageUrl = 'https://via.placeholder.com/150';
    
        // Chamar o método para salvar a capa
        $capaUrl = $controller->baixarESalvarCapa($fakeImageUrl, 1);
    
        // Nome esperado do arquivo no mock do S3
        $fileName = "capas/livro_1_*.jpg";
    
        // Verifica se o arquivo foi salvo no storage fake
        Storage::disk('s3')->assertExists($fileName);
    }
    
    public function testUploadDeCapaParaS3()
    {
        Storage::fake('s3'); // Simula o S3 para o teste

        $controller = new \App\Http\Controllers\LivroController();
        $s3Url = $controller->baixarESalvarCapa('https://via.placeholder.com/150', 1);

        // Verifica se o arquivo foi salvo no fake S3
        Storage::disk('s3')->assertExists('capas/livro_1_'.time().'.jpg');

        $this->assertNotNull($s3Url, 'A URL da capa não deve ser nula');
    }
}
