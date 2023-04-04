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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('name');
            $table->string('path');
            $table->string('mime');
            $table->string('driver')->comment('like localhost or any other server where files are stored.');
            $table->string('mediable_type')->comment('Model name like user, veghicle etc.');
            $table->integer('mediable_id')->comment('mediable_type model primary key id');
            $table->integer('status')->default(1)->comment('0 => inactive, 1 => active');
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
        Schema::dropIfExists('media');
    }
};
