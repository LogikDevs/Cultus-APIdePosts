<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment');
            $table->unsignedBigInteger('fk_id_user');
            $table->unsignedBigInteger('fk_id_post');
            $table->string('text');

            $table->foreign('fk_id_user')->references('id')->on('users');
            $table->foreign('fk_id_post')->references('id_post')->on('post');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
    
}
