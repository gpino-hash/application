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
        Schema::create('currencies', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string("name");
            $table->string("description");
            $table->string("symbol", 5);
            $table->string("code", 5);
            $table->integer("decimal_places")->default(2);
            $table->string("decimal_separator")->default(",");
            $table->string("thousands_separator")->default(".");

            $table->foreignUuid("country_uuid")->references('uuid')->on('countries');
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
        Schema::dropIfExists('currencies');
    }
};
