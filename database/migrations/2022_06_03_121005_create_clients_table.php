<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('telegram_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('delivery')->nullable();
            $table->unsignedBigInteger('shop_id');
            $table->string('session_id')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_notify')->default(false);
            $table->foreign('shop_id')->references('id')->on('shops');
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
        Schema::dropIfExists('clients');
    }
}
