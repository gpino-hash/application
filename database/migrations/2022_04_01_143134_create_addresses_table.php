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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->string("country");
            $table->string("state");
            $table->string("city");
            $table->text("address");
            $table->enum("type", ["house", "work", "other"]);
            $table->text("tags")->nullable();
            $table->string("status");

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
        Schema::dropIfExists('addresses');
    }
};
