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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->decimal("subtotal");
            $table->decimal("tax");
            $table->decimal("other");
            $table->decimal("total");
            $table->foreignUuid("order_items_uuid")->references("uuid")->on("order_items");
            $table->foreignUuid("sites_uuid")->references("uuid")->on("sites");
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
        Schema::dropIfExists('orders');
    }
};
