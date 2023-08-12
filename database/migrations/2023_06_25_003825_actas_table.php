<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_sesion')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_finalizacion')->nullable();
            $table->string('modalidad_encuentro')->nullable();
            $table->string('asistentes')->nullable();
            $table->string('temas')->nullable();

            $table->unsignedBigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('empresa_id')->nullable()->unsigned();
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actas');
    }
}
