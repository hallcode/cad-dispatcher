<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAddressFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn([
                'number',
                'street',
                'district',
                'postal_town',
                'country',
                'post_code',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('number')->nullable();
            $table->string('street')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_town')->nullable();
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
        });
    }
}
