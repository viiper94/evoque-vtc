<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rp_reports', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
        Schema::table('recruitment', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
        Schema::table('convoys', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
        Schema::table('tabs', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rp_reports', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
        Schema::table('recruitment', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
        Schema::table('convoys', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
        Schema::table('tabs', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
}
