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

        // Define analytics resources
        $analyticsResources = [
            'page_views',
            'visitor_sessions',
            'analytics_events',
        ];

        // Define permission actions (analytics is read-only for most users)
        $actions = ['view', 'delete']; // No create/edit as these are auto-generated

        // Create permissions for analytics resources
        $permissions = [];
        foreach ($analyticsResources as $resource) {
            foreach ($actions as $action) {
                $permissionName = "{$action}_{$resource}";
                $permissions[$permissionName] = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Add view_analytics_dashboard permission
        $dashboardPermission = Permission::firstOrCreate([
            'name' => 'view_analytics_dashboard',
            'guard_name' => 'web',
        ]);

        // Get Admin role and give it all analytics permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'view_page_views',
                'delete_page_views',
                'view_visitor_sessions',
                'delete_visitor_sessions',
                'view_analytics_events',
                'delete_analytics_events',
                'view_analytics_dashboard',
            ]);
        }

        // Editor role gets view-only access to analytics
        $editorRole = Role::where('name', 'editor')->first();
        if ($editorRole) {
            $editorRole->givePermissionTo([
                'view_page_views',
                'view_visitor_sessions',
                'view_analytics_events',
                'view_analytics_dashboard',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove analytics permissions
        $analyticsPermissions = [
            'view_page_views',
            'delete_page_views',
            'view_visitor_sessions',
            'delete_visitor_sessions',
            'view_analytics_events',
            'delete_analytics_events',
            'view_analytics_dashboard',
        ];

        Permission::whereIn('name', $analyticsPermissions)->delete();
    }
};
