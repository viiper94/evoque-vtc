<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('name');
            $table->string('nickname');
            $table->integer('age');
            $table->integer('hours_played');
            $table->string('vk_link');
            $table->string('steam_link');
            $table->string('tmp_link');
            $table->boolean('have_mic')->default(0);
            $table->boolean('have_ts3')->default(0);
            $table->boolean('have_ats')->default(0);
            $table->text('referral')->nullable();
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
        Schema::dropIfExists('recruitment');
    }
}
