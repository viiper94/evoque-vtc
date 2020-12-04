<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKbColsToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('manage_kb')->default('0');
            $table->boolean('view_private')->default('1');
            $table->boolean('view_hidden')->default('0');
            $table->boolean('add_article')->default('0');
            $table->boolean('edit_own_article')->default('0');
            $table->boolean('edit_all_articles')->default('0');
            $table->boolean('delete_own_article')->default('0');
            $table->boolean('delete_all_articles')->default('0');
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
            $table->dropColumn('manage_kb');
            $table->dropColumn('view_private');
            $table->dropColumn('view_hidden');
            $table->dropColumn('add_article');
            $table->dropColumn('edit_own_article');
            $table->dropColumn('edit_all_articles');
            $table->dropColumn('delete_own_article');
            $table->dropColumn('delete_all_articles');
        });
    }
}
