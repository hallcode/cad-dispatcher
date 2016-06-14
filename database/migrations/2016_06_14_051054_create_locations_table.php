<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('formatted_address');
            $table->string('type');
            $table->text('notes')->nullable();
            $table->string('number')->nullable();
            $table->string('street')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_town')->nullable();
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
            $table->double('lat', 10, 10)->nullable();
            $table->double('lng', 10, 10)->nullable();
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
        Schema::drop('locations');
    }
}
