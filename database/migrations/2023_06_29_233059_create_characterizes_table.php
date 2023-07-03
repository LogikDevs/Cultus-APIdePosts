<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characterizes', function (Blueprint $table) {
            $table->id('id_characterizes');
            $table->unsignedBigInteger('fk_id_label');
            $table->unsignedBigInteger('fk_id_post');

            $table->foreign('fk_id_label')->references('id_label')->on('interest_label');
            $table->foreign('fk_id_post')->references('id_post')->on('post');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characterizes');
    }
}
