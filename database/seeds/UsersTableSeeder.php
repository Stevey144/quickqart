<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permission::ALL as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        foreach (Role::ALL as $role) {
            Role::query()->firstOrCreate(['name' => $role]);
        }

        $adminRole = Role::query()->where([
            'name' => Role::SYSADMIN,
        ])->first();

        $storeAdmin = Role::query()->where([
            'name' => Role::STORE_ADMIN,
        ])->first();

        $adminRole->syncPermissions(Permission::query()->get());
        $storeAdmin->syncPermissions(Permission::query()->whereIn('name', [Permission::MANAGE_STORES])->get());

        $user = User::query()->updateOrCreate(
            ['email' => 'admin@quick-orders.com', 'name' => 'Administrator'],
            ['password' => 'secret', 'email_verified_at' => now()]
        );

        $user->syncRoles($adminRole);
    }
}
