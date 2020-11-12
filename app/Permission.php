<?php


namespace App;


use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $hidden = ['guard_name', 'created_at', 'updated_at'];
    protected $fillable = ['name', 'guard_name'];

    const MANAGE_USERS = 'manage users',
        MANAGE_ROLES = 'manage roles',
        CHANGE_OTHERS_PASSWORD = 'change others password',
        MANAGE_FAQS = 'manage FAQS',
        MANAGE_STORES = 'manage stores';


    const ALL = [
        self::MANAGE_USERS,
        self::MANAGE_ROLES,
        self::MANAGE_STORES,
        self::MANAGE_FAQS,
        self::CHANGE_OTHERS_PASSWORD,
    ];
}
