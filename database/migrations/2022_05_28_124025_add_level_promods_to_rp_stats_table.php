<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelPromodsToRpStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rp_stats', function (Blueprint $table) {
            $table->integer('level_promods')->default(0);
            $table->integer('level')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rp_stats', function (Blueprint $table) {
            $table->dropColumn('level_promods');
            $table->integer('level')->nullable();
        });
    }
}
