<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTuningToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('manage_tunings')->default(0);
            $table->boolean('add_tunings')->default(0);
            $table->boolean('edit_tunings')->default(0);
            $table->boolean('delete_tunings')->default(0);
            $table->boolean('view_tunings')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('manage_tunings');
            $table->dropColumn('add_tunings');
            $table->dropColumn('edit_tunings');
            $table->dropColumn('delete_tunings');
            $table->dropColumn('view_tunings');
        });
    }
}
