<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();

            $table->string("width")->nullable();
            $table->string("height")->nullable();
            $table->string("title");
            $table->string("original_url");
            $table->json("thumbnail_settings")->nullable();
            $table->text("tags")->nullable();

            $table->bigInteger("user_information_id");
            $table->foreign("user_information_id")->references("id")->on("user_information");

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
        Schema::dropIfExists('pictures');
    }
};
