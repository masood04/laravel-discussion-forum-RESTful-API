<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //create permission
        Permission::create(['name'=>'thread-management']);
        Permission::create(['name'=>'tag-management']);
        Permission::create(['name'=>'answer-management']);

        //assign roles
        $role = Role::create(['name' => 'tag-admin']);
        $role->givePermissionTo('tag-management');

        Role::create(['name' => 'user']);
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

    }
}
