<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultimediaPostTable extends Migration
{

    public function up()
    {
        Schema::create('multimedia_post', function (Blueprint $table) {
            $table->id('id_multimediaPost');
            $table->unsignedBigInteger('fk_id_post');
            $table->string('multimediaLink');
            //el link es unico? porque podria utilizarse el mismo para fotos de dferentes lugares o se suben varias veces?
            //eso no conviene, pero entonces necesito buscar siempre q se vaya a subir una foto

            $table->foreign('fk_id_post')->references('id_post')->on('post');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('multimedia_post');
    }
    
}
