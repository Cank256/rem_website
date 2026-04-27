<?php

namespace App\Traits;

trait HasResourcePermissions
{
    public static function canViewAny(): bool
    {
        $permission = 'view_' . static::getPermissionKey();
        return auth()->user()->can($permission) || auth()->user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        $permission = 'create_' . static::getPermissionKey();
        return auth()->user()->can($permission) || auth()->user()->isAdmin();
    }

    public static function canEdit($record): bool
    {
        $permission = 'edit_' . static::getPermissionKey();
        return auth()->user()->can($permission) || auth()->user()->isAdmin();
    }

    public static function canDelete($record): bool
    {
        $permission = 'delete_' . static::getPermissionKey();
        return auth()->user()->can($permission) || auth()->user()->isAdmin();
    }

    protected static function getPermissionKey(): string
    {
        // Override this method in resources if needed
        // Default: converts "SermonResource" to "sermons"
        $modelClass = static::getModel();
        $modelName = class_basename($modelClass);
        return \Illuminate\Support\Str::plural(\Illuminate\Support\Str::snake($modelName));
    }
}
