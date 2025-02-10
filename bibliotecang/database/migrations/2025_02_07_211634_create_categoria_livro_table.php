<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categoria_livro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->constrained()->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    
    public function down()
    {
        Schema::dropIfExists('categoria_livro');
    }
    
};
