<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion')->nullable();
            $table->string('responsable')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_finalizacion')->nullable();

            $table->unsignedBigInteger('acta_id')->nullable()->unsigned();
            $table->foreign('acta_id')
                ->references('id')
                ->on('actas');
            
            $table->unsignedBigInteger('estado_id')->nullable()->unsigned();
            $table->foreign('estado_id')
                ->references('id')
                ->on('estados');

            $table->unsignedBigInteger('actividad_id')->nullable()->unsigned();
            $table->foreign('actividad_id')
                ->references('id')
                ->on('actividades');

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
        Schema::dropIfExists('tareas');
    }
}
