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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('model_name');
            $table->string('model_year');
            $table->string('make');
            $table->string('body_type');
            $table->integer('distance_covered')->comment('In KM');
            $table->text('location')->nullable();
            $table->decimal('amount', $precision = 8, $scale = 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('status');
            $table->integer('user_id');
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
        Schema::dropIfExists('vehicles');
    }
};
