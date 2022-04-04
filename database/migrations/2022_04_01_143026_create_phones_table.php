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
        Schema::create('phones', function (Blueprint $table) {
            $table->id();

            $table->string("phone");
            $table->enum("type", ["residential", "personal"]);
            $table->string("operator");
            $table->string("status");
            $table->text("tags")->nullable();

            $table->bigInteger("user_information_id");
            $table->foreign("user_information_id")->references("id")->on("user_information");

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
        Schema::dropIfExists('phones');
    }
};
