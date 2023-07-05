<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id('id_vote');
            $table->unsignedBigInteger('fk_id_user');
            $table->unsignedBigInteger('fk_id_post');
            $table->boolean('upvote')->default(0);
            $table->boolean('downvote')->default(0);

            $table->foreign('fk_id_user')->references('id')->on('users');
            $table->foreign('fk_id_post')->references('id_post')->on('post');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
    
}
