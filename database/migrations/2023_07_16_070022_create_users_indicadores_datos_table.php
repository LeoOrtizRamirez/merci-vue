<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersIndicadoresDatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_indicadores_datos', function (Blueprint $table) {
            $table->id();
            $table->string('mes')->nullable();
            $table->string('data_1')->nullable();
            $table->string('data_2')->nullable();

            $table->unsignedBigInteger('user_indicadore_id')->nullable()->unsigned();
            $table->foreign('user_indicadore_id')
                ->references('id')
                ->on('user_indicadores');
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
        Schema::dropIfExists('users_indicadores_datos');
    }
}
