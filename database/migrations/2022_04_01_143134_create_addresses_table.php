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
            $table->uuid()->primary();

            $table->string("state")->nullable();
            $table->string("city");
            $table->string("postal_code");
            $table->text("address");
            $table->enum("type", ["house", "work", "other"])->comment("type of address.");
            $table->text("tags")->nullable();
            $table->string("status");
            $table->uuidMorphs("addressable");
            $table->foreignUuid("country_uuid")->references("uuid")->on("countries");

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
