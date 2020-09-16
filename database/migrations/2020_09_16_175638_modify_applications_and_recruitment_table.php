<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyApplicationsAndRecruitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment', function(Blueprint $table){
            $table->string('category')->default('recruitment');
        });
        Schema::table('applications', function(Blueprint $table){
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment', function(Blueprint $table){
        $table->dropColumn('category');
    });
        Schema::table('applications', function(Blueprint $table){
            $table->dropColumn('status');
        });
    }
}
