<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_member', function(Blueprint $table){
            $table->integer('member_id');
            $table->integer('role_id');
        });
        Schema::table('members', function(Blueprint $table){
            $table->dropColumn('role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_member', function(Blueprint $table){
            $table->dropColumn('member_id');
            $table->dropColumn('role_id');
        });
        Schema::table('members', function(Blueprint $table){
            $table->integer('role_id');
        });
    }
}
