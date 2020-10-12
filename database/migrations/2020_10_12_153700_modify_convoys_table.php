<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convoys', function (Blueprint $table){
            $table->string('start_city');
            $table->string('start_company')->nullable();
            $table->string('rest_city');
            $table->string('rest_company')->nullable();
            $table->string('finish_city');
            $table->string('finish_company')->nullable();
            $table->string('alt_trailer_image')->nullable();
            $table->string('alt_trailer')->nullable();
            $table->text('alt_trailer_tuning')->nullable();
            $table->text('alt_trailer_paint')->nullable();
            $table->string('alt_cargo')->nullable();
            $table->text('dlc')->nullable();

            $table->dropColumn('start');
            $table->dropColumn('rest');
            $table->dropColumn('finish');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convoys', function (Blueprint $table){
            $table->dropColumn('start_city');
            $table->dropColumn('start_company');
            $table->dropColumn('rest_city');
            $table->dropColumn('rest_company');
            $table->dropColumn('finish_city');
            $table->dropColumn('finish_company');
            $table->dropColumn('alt_trailer_image');
            $table->dropColumn('alt_trailer');
            $table->dropColumn('alt_trailer_tuning');
            $table->dropColumn('alt_trailer_paint');
            $table->dropColumn('alt_cargo');
            $table->dropColumn('dlc');

            $table->string('start');
            $table->string('rest');
            $table->string('finish');
        });
    }
}
