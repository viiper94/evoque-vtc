<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTruckImageToConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convoys', function (Blueprint $table) {
            $table->string('truck_image')->nullable()->after('truck_paint');
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
            $table->dropColumn('truck_paint');
        });
    }
}
