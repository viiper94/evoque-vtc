<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNullableAtConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convoys', function (Blueprint $table) {
            $table->string('server')->nullable()->change();
            $table->string('communication')->nullable()->change();
            $table->string('communication_link')->nullable()->change();
            $table->string('communication_channel')->nullable()->change();
            $table->string('route')->nullable()->change();
            $table->string('start_city')->nullable()->change();
            $table->string('rest_city')->nullable()->change();
            $table->string('finish_city')->nullable()->change();
            $table->string('trailer_public')->default(0)->change();
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
            $table->string('server')->nullable(false)->change();
            $table->string('communication')->nullable(false)->change();
            $table->string('communication_link')->nullable(false)->change();
            $table->string('communication_channel')->nullable(false)->change();
            $table->text('route')->nullable(false)->change();
            $table->string('start_city')->nullable(false)->change();
            $table->string('rest_city')->nullable(false)->change();
            $table->string('finish_city')->nullable(false)->change();
            $table->string('trailer_public')->default(1)->change();
        });
    }
}
