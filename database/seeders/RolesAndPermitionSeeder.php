<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'access_panel']);

        Permission::create(['name' => 'user_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'user_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'user_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'user_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'employee_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'employee_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'employee_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'employee_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'associate_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'associate_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'associate_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'associate_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'position_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'position_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'position_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'position_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'associated_type_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'associated_type_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'associated_type_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'associated_type_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'permission_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'permission_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'permission_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'permission_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'role_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'role_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'role_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'role_delete', 'guard_name' => 'web']);

        Permission::create(['name' => 'meeting_read', 'guard_name' => 'web']);
        Permission::create(['name' => 'meeting_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'meeting_update', 'guard_name' => 'web']);
        Permission::create(['name' => 'meeting_delete', 'guard_name' => 'web']);


        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //role Employee
        $role = Role::create(['name' => 'Employee', 'guard_name' => 'web']);
        $role->givePermissionTo('access_panel',
                'user_read', 'user_create', 'user_update', 'user_delete',
                'employee_read', 'employee_create', 'employee_update', 'employee_delete',
                'associate_read', 'associate_create', 'associate_update', 'associate_delete',
                'meeting_read', 'meeting_create', 'meeting_update', 'meeting_delete');


        // role Associate
        $role = Role::create(['name' => 'Associate', 'guard_name' => 'web']);
        $role->givePermissionTo('access_panel');

        // role Admin
        $role = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $role->givePermissionTo('access_panel',
                'user_read', 'user_create', 'user_update', 'user_delete',
                'employee_read', 'employee_create', 'employee_update', 'employee_delete',
                'associate_read', 'associate_create', 'associate_update', 'associate_delete',
                'meeting_read', 'meeting_create', 'meeting_update', 'meeting_delete');

        //role Super_admin
        $role = Role::create(['name' => 'Super_admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());


        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('Super_admin');
        }
    }
}
