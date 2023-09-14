<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id('id_post');
            $table->unsignedBigInteger('fk_id_user');
            $table->unsignedBigInteger('fk_id_event')->nullable();
            $table->text('text')->nullable()->max(255);
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->dateTime('date');
            $table->integer("votes")->default(0);
            $table->integer("comments")->default(0);
            
            $table->foreign('fk_id_user')->references('id')->on('users');
            $table->foreign('fk_id_event')->references('id')->on('events');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post');
    }

}