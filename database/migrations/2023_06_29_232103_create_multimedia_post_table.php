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
