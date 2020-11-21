<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function(Blueprint $table){
            $table->boolean('edit_members')->default(0)->after('manage_members');
            $table->boolean('edit_members_activity')->default(0)->after('manage_members');
            $table->boolean('edit_members_rp_stats')->default(0)->after('manage_members');
            $table->boolean('fire_members')->default(0)->after('manage_members');
            $table->boolean('set_members_activity')->default(0)->after('manage_members');
            $table->boolean('reset_members_activity')->default(0)->after('manage_members');

            $table->boolean('manage_applications')->default(0);
            $table->boolean('view_recruitments')->default(0);
            $table->boolean('claim_recruitments')->default(0);
            $table->boolean('delete_recruitments')->default(0);
            $table->boolean('make_applications')->default(1);
            $table->boolean('edit_applications')->default(1);
            $table->boolean('view_applications')->default(0);
            $table->boolean('claim_applications')->default(0);
            $table->boolean('delete_applications')->default(0);

            $table->boolean('view_all_convoys')->default(0)->after('manage_convoys');
            $table->boolean('book_convoys')->default(1)->after('manage_convoys');
            $table->boolean('add_convoys')->default(0)->after('manage_convoys');
            $table->boolean('edit_convoys')->default(0)->after('manage_convoys');
            $table->boolean('delete_convoys')->default(0)->after('manage_convoys');

            $table->boolean('manage_tab')->default(0);
            $table->boolean('add_tab')->default(1);
            $table->boolean('edit_tab')->default(1);
            $table->boolean('accept_tab')->default(0);
            $table->boolean('delete_tab')->default(0);

            $table->boolean('add_reports')->default(1)->after('manage_rp');
            $table->boolean('view_all_reports')->default(0)->after('manage_rp');
            $table->boolean('delete_own_reports')->default(1)->after('manage_rp');
            $table->boolean('delete_all_reports')->default(0)->after('manage_rp');
            $table->boolean('accept_reports')->default(0)->after('manage_rp');

            $table->boolean('manage_rules')->default(0);
            $table->boolean('add_rules')->default(0);
            $table->boolean('edit_rules')->default(0);
            $table->boolean('delete_rules')->default(0);
            $table->boolean('view_rules_changelog')->default(1);

            $table->boolean('manage_roles')->default(0);
            $table->boolean('view_roles')->default(0);
            $table->boolean('add_roles')->default(0);
            $table->boolean('edit_roles')->default(0);
            $table->boolean('edit_roles_permissions')->default(0);
            $table->boolean('delete_roles')->default(0);

            $table->boolean('manage_users')->default(0);
            $table->boolean('view_users')->default(0);
            $table->boolean('set_user_as_member')->default(0);

            $table->dropColumn('manage_table');
            $table->dropColumn('do_rp');
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
            $table->dropColumn('edit_members');
            $table->dropColumn('edit_members_activity');
            $table->dropColumn('edit_members_rp_stats');
            $table->dropColumn('fire_members');
            $table->dropColumn('set_members_activity');
            $table->dropColumn('reset_members_activity');

            $table->dropColumn('manage_applications');
            $table->dropColumn('view_recruitments');
            $table->dropColumn('claim_recruitments');
            $table->dropColumn('delete_recruitments');
            $table->dropColumn('make_applications');
            $table->dropColumn('view_applications');
            $table->dropColumn('claim_applications');
            $table->dropColumn('delete_applications');

            $table->dropColumn('view_all_convoys');
            $table->dropColumn('book_convoys');
            $table->dropColumn('add_convoys');
            $table->dropColumn('edit_convoys');
            $table->dropColumn('delete_convoys');

            $table->dropColumn('manage_tab');
            $table->dropColumn('add_tab');
            $table->dropColumn('edit_tab');
            $table->dropColumn('accept_tab');
            $table->dropColumn('delete_tab');

            $table->dropColumn('add_reports');
            $table->dropColumn('view_all_reports');
            $table->dropColumn('delete_own_reports');
            $table->dropColumn('delete_all_reports');
            $table->dropColumn('accept_reports');

            $table->dropColumn('manage_rules');
            $table->dropColumn('add_rules');
            $table->dropColumn('edit_rules');
            $table->dropColumn('delete_rules');
            $table->dropColumn('view_rules_changelog');

            $table->dropColumn('manage_roles');
            $table->dropColumn('view_roles');
            $table->dropColumn('add_roles');
            $table->dropColumn('edit_roles');
            $table->dropColumn('edit_roles_permissions');
            $table->dropColumn('delete_roles');

            $table->dropColumn('manage_users');
            $table->dropColumn('view_users');
            $table->dropColumn('set_user_as_member');

            $table->boolean('manage_table')->default(0);
            $table->boolean('do_rp')->default(0);
        });
    }
}
