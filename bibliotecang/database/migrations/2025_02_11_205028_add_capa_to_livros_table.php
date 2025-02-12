<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapaToLivrosTable extends Migration
{
    public function up()
    {
        Schema::table('livros', function (Blueprint $table) {
            $table->string('capa')->nullable(); // Adiciona a coluna 'capa' do tipo string
        });
    }

    public function down()
    {
        Schema::table('livros', function (Blueprint $table) {
            $table->dropColumn('capa'); // Remove a coluna 'capa' caso seja necessário reverter a migration
        });
    }
}
