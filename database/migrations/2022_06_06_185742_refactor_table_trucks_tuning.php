<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorTableTrucksTuning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trucks_tuning', function (Blueprint $table) {
            $table->rename('official_tuning');
            $table->string('type')->default('truck');
            $table->string('vendor')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('official_tuning', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('vendor')->change();
            $table->rename('trucks_tuning');
        });
    }
}
