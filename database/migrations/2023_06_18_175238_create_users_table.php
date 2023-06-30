<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *chema::create('animales', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->integer("cantidad_de_patas");
            $table->string("especie");
            $table->boolean("cola");
            $table->timestamps();
            $table->softDeletes();
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("surname");
            $table->integer("age");
            $table->string("gender")->nullable();
            $table->string("mail");
            $table->string("passwd");
            $table->string("profile_pic")->nullable();
            $table->string("description")->nullable();
            $table->string("homeland");
            $table->string("residence");

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
        Schema::dropIfExists('users');
    }
}
