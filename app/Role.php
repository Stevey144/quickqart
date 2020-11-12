<?php


namespace App;


use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    protected $fillable = ['name', 'guard_name', 'description'];

    protected $hidden = ['guard_name', 'created_at', 'updated_at'];

    const SYSADMIN = 'sysadmin', SHOP_OWNER = 'shop owner', STORE_ADMIN = 'store admin', USER_ADMIN = 'user admin';

    const ALL = [self::SYSADMIN, self::SHOP_OWNER, self::STORE_ADMIN, self::USER_ADMIN];
}
