<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRpStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rp_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('distance')->default(0)->nullable();
            $table->integer('weight')->default(0)->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->integer('level')->nullable();
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
        Schema::dropIfExists('rp_stats');
    }
}
