<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTruckWithTuningToConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convoys', function (Blueprint $table) {
            $table->integer('truck_with_tuning')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convoys', function (Blueprint $table) {
            $table->dropColumn('truck_with_tuning');
        });
    }
}
