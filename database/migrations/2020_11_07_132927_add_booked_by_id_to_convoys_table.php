<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookedByIdToConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convoys', function (Blueprint $table) {
            $table->integer('booked_by_id')->nullable()->after('lead');
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
            $table->dropColumn('booked_by_id');
        });
    }
}
