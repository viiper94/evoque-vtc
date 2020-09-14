<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nickname')->nullable();
            $table->dateTime('join_date')->nullable();
            $table->integer('role_id')->default(0);
            $table->date('birth_date')->nullable();
            $table->integer('convoys')->default(0);
            $table->integer('scores')->default(0);
            $table->integer('money')->default(0);
            $table->integer('vacations')->default(0);
            $table->date('on_vacation_till')->nullable();
            $table->string('plate')->nullable();
            $table->boolean('visible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
