<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('manage_test')->default(0);
            $table->boolean('add_questions')->default(0);
            $table->boolean('edit_questions')->default(0);
            $table->boolean('delete_questions')->default(0);
            $table->boolean('view_results')->default(0);
            $table->boolean('delete_results')->default(0);
            $table->boolean('do_test')->default(1);
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
            $table->dropColumn('manage_test');
            $table->dropColumn('add_questions');
            $table->dropColumn('edit_questions');
            $table->dropColumn('delete_questions');
            $table->dropColumn('view_results');
            $table->dropColumn('delete_results');
            $table->dropColumn('do_test');
        });
    }
}
