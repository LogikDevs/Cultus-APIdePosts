<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_post', function (Blueprint $table) {
        /*
            $table->id('fk_id_post');
            $table->id('fk_id_group');
            
            $table->foreign('fk_id_post')->references('id_post')->on('post');
            $table->foreign('fk_id_group')->references('id_group')->on('groups');

            $table->timestamps();
            $table->softDeletes();
        */
        //NO HAY GRUPOS AUN
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_post');
    }
}
