<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convoys', function (Blueprint $table) {
            $table->id();
            $table->boolean('public')->default(0);
            $table->boolean('visible')->default(1);
            $table->string('title');
            $table->dateTime('start_time');
            $table->string('start');
            $table->string('rest');
            $table->string('finish');
            $table->string('server');
            $table->string('communication');
            $table->string('lead')->nullable();
            $table->string('truck')->nullable();
            $table->text('truck_tuning')->nullable();
            $table->text('truck_paint')->nullable();
            $table->string('trailer')->nullable();
            $table->text('trailer_tuning')->nullable();
            $table->text('trailer_paint')->nullable();
            $table->string('trailer_image')->nullable();
            $table->string('cargo')->nullable();
            $table->string('route');
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
        Schema::dropIfExists('convoys');
    }
}
