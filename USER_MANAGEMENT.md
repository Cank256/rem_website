# User and Role Management

This church website includes a comprehensive user and role management system built with Laravel, Filament, and Spatie Laravel Permission.

## Features

- **User Management**: Create, edit, and delete users with different roles
- **Role Management**: Define custom roles with specific permissions
- **Permission Management**: Granular control over what users can do
- **Admin Dashboard**: Easy-to-use Filament interface for managing users and roles

## Default Roles

The system comes with three pre-configured roles:

### 1. Admin
- Full access to all features
- Can manage users, roles, and permissions
- Can create, edit, and delete all content

### 2. Editor
- Can manage all content (sermons, events, blog posts, galleries, live streams)
- Cannot manage users, roles, or permissions
- Perfect for content managers and staff members

### 3. User
- Read-only access to content
- Can view sermons, events, blog posts, galleries, and live streams
- Cannot create or edit content

## Permissions

The system includes the following permissions:

### User Management
- `view_users` - View user list
- `create_users` - Create new users
- `edit_users` - Edit existing users
- `delete_users` - Delete users

### Role Management
- `view_roles` - View role list
- `create_roles` - Create new roles
- `edit_roles` - Edit existing roles
- `delete_roles` - Delete roles

### Permission Management
- `view_permissions` - View permission list
- `create_permissions` - Create new permissions
- `edit_permissions` - Edit existing permissions
- `delete_permissions` - Delete permissions

### Content Management
For each content type (sermons, events, blog_posts, galleries, live_streams):
- `view_{content}` - View content
- `create_{content}` - Create new content
- `edit_{content}` - Edit existing content
- `delete_{content}` - Delete content

## Getting Started

### 1. Run Migrations and Seeders

If you haven't already, run the migrations and seed the database:

```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

### 2. Create an Admin User

Use the provided script to create an admin user:

```bash
bash create-admin.sh
```

This will:
- Prompt you for name, email, and password
- Create the user with admin role
- Assign all permissions to the user

### 3. Access the Admin Dashboard

Navigate to `/admin` and log in with your admin credentials.

## Managing Users

### Creating a New User

1. Go to **User Management > Users** in the admin dashboard
2. Click **New User**
3. Fill in the user details:
   - Name
   - Email
   - Role (admin, editor, or user)
   - Password
4. Optionally assign additional Spatie roles
5. Click **Create**

### Editing a User

1. Go to **User Management > Users**
2. Click the **Edit** button next to the user
3. Update the user details
4. Click **Save**

### Deleting a User

1. Go to **User Management > Users**
2. Click the **Delete** button next to the user
3. Confirm the deletion

## Managing Roles

### Creating a New Role

1. Go to **User Management > Roles** in the admin dashboard
2. Click **New Role**
3. Enter the role name (e.g., "moderator", "contributor")
4. Select the guard (usually "web")
5. Check the permissions you want to assign to this role
6. Click **Create**

### Editing a Role

1. Go to **User Management > Roles**
2. Click the **Edit** button next to the role
3. Update the role name or permissions
4. Click **Save**

### Deleting a Role

1. Go to **User Management > Roles**
2. Click the **Delete** button next to the role
3. Confirm the deletion

**Note**: Deleting a role will remove it from all users who have that role.

## Managing Permissions

### Creating a New Permission

1. Go to **User Management > Permissions** in the admin dashboard
2. Click **New Permission**
3. Enter the permission name (e.g., "publish_newsletter")
4. Select the guard (usually "web")
5. Optionally assign the permission to existing roles
6. Click **Create**

### Editing a Permission

1. Go to **User Management > Permissions**
2. Click the **Edit** button next to the permission
3. Update the permission name or role assignments
4. Click **Save**

### Deleting a Permission

1. Go to **User Management > Permissions**
2. Click the **Delete** button next to the permission
3. Confirm the deletion

## Using Permissions in Code

### Check if User Has Permission

```php
// In a controller or view
if (auth()->user()->can('edit_sermons')) {
    // User can edit sermons
}

// Using the hasPermissionTo method
if (auth()->user()->hasPermissionTo('edit_sermons')) {
    // User can edit sermons
}
```

### Check if User Has Role

```php
// Check for a specific role
if (auth()->user()->hasRole('admin')) {
    // User is an admin
}

// Check for multiple roles
if (auth()->user()->hasAnyRole(['admin', 'editor'])) {
    // User is either an admin or editor
}

// Using helper methods
if (auth()->user()->isAdmin()) {
    // User is an admin
}

if (auth()->user()->isEditor()) {
    // User is an editor
}
```

### Protecting Routes

```php
// In routes/web.php
Route::middleware(['auth', 'permission:edit_sermons'])->group(function () {
    Route::get('/sermons/edit', [SermonController::class, 'edit']);
});

// Or using role middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});
```

### Protecting Filament Resources

Filament resources automatically check permissions using the `canViewAny()`, `canCreate()`, `canEdit()`, and `canDelete()` methods. These are already implemented in the resources.

## Access Control

### Admin Panel Access

Only users with the `admin` or `editor` role can access the Filament admin panel. This is controlled in the `User` model's `canAccessPanel()` method.

### Resource Visibility

- **User Management**: Only admins can view and manage users, roles, and permissions
- **Content Management**: Admins and editors can manage content, but editors cannot delete users or modify roles

## Best Practices

1. **Principle of Least Privilege**: Only grant users the permissions they need
2. **Use Roles**: Assign permissions to roles, then assign roles to users
3. **Regular Audits**: Periodically review user permissions and remove unnecessary access
4. **Strong Passwords**: Enforce strong password requirements for all users
5. **Email Verification**: Consider enabling email verification for new users

## Troubleshooting

### User Cannot Access Admin Panel

1. Check if the user has the `admin` or `editor` role
2. Verify the user's email is verified
3. Check the `canAccessPanel()` method in the `User` model

### Permission Not Working

1. Clear the permission cache: `php artisan permission:cache-reset`
2. Verify the permission exists in the database
3. Check if the user or their role has the permission assigned

### Role Not Showing in Dropdown

1. Run the seeder: `php artisan db:seed --class=RolePermissionSeeder`
2. Check if the role exists in the `roles` table
3. Verify the guard name matches (usually "web")

## Additional Resources

- [Spatie Laravel Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Filament Documentation](https://filamentphp.com/docs)
- [Laravel Authorization Documentation](https://laravel.com/docs/authorization)
