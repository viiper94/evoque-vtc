<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    protected $fillable = [
        'group',
        'title',
        'description',
        'min_scores',
        'max_scores'
    ];

    public static $permission_list = [
        'members' => [
            'manage_members',
            'edit_members',
//            'edit_members_activity',
            'edit_members_rp_stats',
            'edit_members_personal_info',
            'fire_members',
            'set_members_activity',
            'reset_members_activity',
            'see_bans',
        ],
        'applications' => [
            'manage_applications',
            'view_recruitments',
            'claim_recruitments',
            'delete_recruitments',
            'make_applications',
            'view_applications',
            'claim_applications',
            'delete_applications',
        ],
        'convoy' => [
            'manage_convoys',
            'view_all_convoys',
            'book_convoys',
            'quick_book_convoys',
            'add_convoys',
            'edit_convoys',
            'delete_convoys',
        ],
        'tab' => [
            'manage_tab',
            'view_tab',
//            'add_tab',
            'edit_tab',
            'accept_tab',
            'delete_tab',
        ],
        'rp' => [
            'manage_rp',
            'add_reports',
            'view_all_reports',
            'delete_own_reports',
            'delete_all_reports',
            'accept_reports',
            'reset_stats',
            'edit_rp_rewards',
        ],
        'rules' => [
            'manage_rules',
            'add_rules',
            'edit_rules',
            'delete_rules',
            'view_rules_changelog',
        ],
        'roles' => [
            'manage_roles',
            'view_roles',
            'add_roles',
            'edit_roles',
            'edit_roles_permissions',
            'delete_roles',
        ],
        'kb' => [
            'manage_kb',
            'view_private',
            'view_hidden',
            'add_article',
            'edit_own_article',
            'edit_all_articles',
            'delete_own_article',
            'delete_all_articles',
        ],
        'users' => [
            'manage_users',
            'view_users',
            'set_user_as_member',
        ],
        'gallery' => [
            'manage_gallery',
            'upload_screenshots',
            'upload_without_moderation',
            'toggle_visibility',
            'delete_screenshots',
        ],
        'test' => [
            'manage_test',
            'add_questions',
            'edit_questions',
            'delete_questions',
            'view_results',
            'delete_results',
            'do_test',
        ],
        'tunings' => [
            'manage_tunings',
            'add_tunings',
            'edit_tunings',
            'delete_tunings',
            'view_tunings',
        ],
    ];

    public function members(){
        return $this->belongsToMany('App\Member', 'role_member');
    }

    public function nextRole(){
        return $this->hasOne('App\Role', 'id', 'next_role');
    }

    public function prevRole(){
        return $this->hasOne('App\Role', 'id', 'prev_role');
    }

}
