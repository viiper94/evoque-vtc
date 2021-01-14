<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGalleryToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('manage_gallery')->default(0);
            $table->boolean('upload_screenshots')->default(1);
            $table->boolean('upload_without_moderation')->default(1);
            $table->boolean('toggle_visibility')->default(0);
            $table->boolean('delete_screenshots')->default(0);
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
            $table->dropColumn('manage_gallery');
            $table->dropColumn('upload_screenshots');
            $table->dropColumn('upload_without_moderation');
            $table->dropColumn('toggle_visibility');
            $table->dropColumn('delete_screenshots');
        });
    }
}
