<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function(Blueprint $table){
            $table->boolean('admin')->default(0);
            $table->boolean('manage_members')->default(0);
            $table->boolean('manage_convoys')->default(0);
            $table->boolean('manage_table')->default(0);
            $table->boolean('manage_rp')->default(0);
        });
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function(Blueprint $table){
            $table->dropColumn('admin');
            $table->dropColumn('manage_members');
            $table->dropColumn('manage_convoys');
            $table->dropColumn('manage_table');
            $table->dropColumn('manage_rp');
        });
        Schema::table('users', function(Blueprint $table){
            $table->boolean('is_admin')->default(0);
        });
    }
}
