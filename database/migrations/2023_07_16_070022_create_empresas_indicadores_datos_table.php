<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasIndicadoresDatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_indicadores_datos', function (Blueprint $table) {
            $table->id();
            $table->string('mes')->nullable();
            $table->string('data_1')->nullable();
            $table->string('data_2')->nullable();

            $table->unsignedBigInteger('empresa_indicadore_id')->nullable()->unsigned();
            $table->foreign('empresa_indicadore_id')
                ->references('id')
                ->on('empresa_indicadores');
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
        Schema::dropIfExists('empresas_indicadores_datos');
    }
}
