<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{

    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_interest");
            $table->unsignedBigInteger("id_user");
            $table->foreign("id_interest")->references("id_label")->on("interest_label");
            $table->foreign("id_user")->references("id")->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
