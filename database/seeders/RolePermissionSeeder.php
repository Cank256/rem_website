<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Role management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            
            // Permission management
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            
            // Content management
            'view_sermons',
            'create_sermons',
            'edit_sermons',
            'delete_sermons',
            
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            
            'view_blog_posts',
            'create_blog_posts',
            'edit_blog_posts',
            'delete_blog_posts',
            
            'view_galleries',
            'create_galleries',
            'edit_galleries',
            'delete_galleries',
            
            'view_live_streams',
            'create_live_streams',
            'edit_live_streams',
            'delete_live_streams',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        
        // Admin role - has all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Editor role - can manage content but not users/roles
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            'view_sermons', 'create_sermons', 'edit_sermons', 'delete_sermons',
            'view_events', 'create_events', 'edit_events', 'delete_events',
            'view_blog_posts', 'create_blog_posts', 'edit_blog_posts', 'delete_blog_posts',
            'view_galleries', 'create_galleries', 'edit_galleries', 'delete_galleries',
            'view_live_streams', 'create_live_streams', 'edit_live_streams', 'delete_live_streams',
        ]);

        // User role - can only view content
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'view_sermons',
            'view_events',
            'view_blog_posts',
            'view_galleries',
            'view_live_streams',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
