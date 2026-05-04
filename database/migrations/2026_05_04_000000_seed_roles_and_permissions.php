<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define resources that need permissions
        $resources = [
            'sermons',
            'events',
            'blog_posts',
            'galleries',
            'gallery_images',
            'live_streams',
            'users',
            'roles',
            'permissions',
        ];

        // Define permission actions
        $actions = ['view', 'create', 'edit', 'delete'];

        // Create permissions for each resource
        $permissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = "{$action}_{$resource}";
                $permissions[$permissionName] = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create Admin role with all permissions
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions(Permission::all());

        // Create Editor role with limited permissions
        $editorRole = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        // Editor can manage content but not users, roles, or permissions
        $editorPermissions = [
            // Sermons
            'view_sermons',
            'create_sermons',
            'edit_sermons',
            'delete_sermons',
            // Events
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            // Blog Posts
            'view_blog_posts',
            'create_blog_posts',
            'edit_blog_posts',
            'delete_blog_posts',
            // Galleries
            'view_galleries',
            'create_galleries',
            'edit_galleries',
            'delete_galleries',
            // Gallery Images
            'view_gallery_images',
            'create_gallery_images',
            'edit_gallery_images',
            'delete_gallery_images',
            // Live Streams
            'view_live_streams',
            'create_live_streams',
            'edit_live_streams',
            'delete_live_streams',
        ];

        $editorRole->syncPermissions(
            Permission::whereIn('name', $editorPermissions)->get()
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Delete all roles
        Role::whereIn('name', ['admin', 'editor'])->delete();

        // Delete all permissions
        $resources = [
            'sermons',
            'events',
            'blog_posts',
            'galleries',
            'gallery_images',
            'live_streams',
            'users',
            'roles',
            'permissions',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::where('name', "{$action}_{$resource}")->delete();
            }
        }
    }
};
