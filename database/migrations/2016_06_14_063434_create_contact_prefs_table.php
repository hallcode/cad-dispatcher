<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactPrefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_prefs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->morphs('parent');
            $table->boolean('is_global_pref')->default(false);
            $table->boolean('recieve_sms');
            $table->boolean('recieve_email');
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
        Schema::drop('contact_prefs');
    }
}
