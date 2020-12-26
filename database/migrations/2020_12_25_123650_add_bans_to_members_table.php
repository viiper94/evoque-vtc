<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBansToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('tmp_banned')->default('0');
            $table->dateTime('tmp_banned_until')->nullable();
            $table->boolean('tmp_bans_hidden')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('tmp_banned');
            $table->dropColumn('tmp_banned_until');
            $table->dropColumn('tmp_bans_hidden');
        });
    }
}
