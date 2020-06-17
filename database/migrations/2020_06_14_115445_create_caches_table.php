<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caches', function (Blueprint $table) {
            $table->id();
            $table->integer('size');
            $table->string('type');
            $table->integer('index_size');
            $table->integer('tag_size');
            $table->integer('bo_size');
            $table->integer('address_size');
            $table->integer('cache_access_time');
            $table->integer('cache_miss_time');

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
        Schema::dropIfExists('caches');
    }
}
