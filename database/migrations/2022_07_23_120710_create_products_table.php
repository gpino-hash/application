<?php

use App\Enums\Status;
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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string("title", 60);
            $table->text("description");
            $table->integer("stock")->default(0);
            $table->decimal("price");
            $table->enum("status", Status::values())->default(Status::INACTIVE->value);

            $table->foreignUuid("site_uuid")->references("uuid")->on("sites");

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
        Schema::dropIfExists('products');
    }
};
