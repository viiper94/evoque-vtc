<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunicationStuffToConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convoys', function (Blueprint $table) {
            $table->string('communication_link');
            $table->string('communication_channel');
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
            $table->dropColumn('communication_link');
            $table->dropColumn('communication_channel');
        });
    }
}
