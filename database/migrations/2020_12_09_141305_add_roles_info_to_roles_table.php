<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolesInfoToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->integer('min_scores')->nullable();
            $table->integer('max_scores')->nullable();
            $table->integer('next_role')->nullable();
            $table->integer('prev_role')->nullable();
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
            $table->dropColumn('min_scores');
            $table->dropColumn('max_scores');
            $table->dropColumn('next_role');
            $table->dropColumn('prev_role');
        });
    }
}
