<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_offers', function (Blueprint $table) {
            $table->id();  
            $table->decimal('buyer_offered_amount', $precision = 8, $scale = 2)->nullable();
            $table->decimal('seller_offered_amount', $precision = 8, $scale = 2)->nullable();
            $table->integer('status');
            $table->integer('buyer_id')->comment('User ID');
            $table->integer('event_id');
            $table->integer('type_id')->default(0);
            $table->integer('created_by_id')->unsigned()->nullable();
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
        Schema::dropIfExists('event_offers');
    }
};
