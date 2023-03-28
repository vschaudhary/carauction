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
        Schema::create('dealerships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street_name');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('website')->nullable();
            $table->string('car_stock')->nullable();
            $table->string('status')->nullable();
            $table->string('type_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('created_by_id')->unsigned()->nullable();
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
        Schema::dropIfExists('dealerships');
    }
};
