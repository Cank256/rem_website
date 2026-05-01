# Roles & Permissions Quick Reference

## 🎯 Quick Overview

| Role | Access Level | Can Manage Users | Can Manage Content | Admin Panel Access |
|------|--------------|------------------|-------------------|-------------------|
| **Admin** | Full | ✅ Yes | ✅ Yes | ✅ Yes |
| **Editor** | Content Only | ❌ No | ✅ Yes | ✅ Yes |
| **User** | Read Only | ❌ No | ❌ No (View Only) | ❌ No |

## 📋 Role Details

### 👑 Admin Role
**Total Permissions**: 32

**Can Do**:
- ✅ Everything in the system
- ✅ Manage users, roles, and permissions
- ✅ Create, edit, and delete all content
- ✅ Access all admin panel features

**Use For**: Site administrators, technical staff

---

### ✏️ Editor Role
**Total Permissions**: 20

**Can Do**:
- ✅ Manage sermons (create, edit, delete)
- ✅ Manage events (create, edit, delete)
- ✅ Manage blog posts (create, edit, delete)
- ✅ Manage galleries (create, edit, delete)
- ✅ Manage live streams (create, edit, delete)

**Cannot Do**:
- ❌ Manage users
- ❌ Manage roles or permissions
- ❌ Access user management features

**Use For**: Content managers, pastors, ministry leaders

---

### 👤 User Role
**Total Permissions**: 5

**Can Do**:
- ✅ View sermons
- ✅ View events
- ✅ View blog posts
- ✅ View galleries
- ✅ View live streams

**Cannot Do**:
- ❌ Create or edit any content
- ❌ Access admin panel
- ❌ Manage users or roles

**Use For**: Regular website users, members

---

## 🔑 Permission Categories

### User Management (4 permissions)
```
view_users
create_users
edit_users
delete_users
```
**Assigned to**: Admin only

### Role Management (4 permissions)
```
view_roles
create_roles
edit_roles
delete_roles
```
**Assigned to**: Admin only

### Permission Management (4 permissions)
```
view_permissions
create_permissions
edit_permissions
delete_permissions
```
**Assigned to**: Admin only

### Sermons (4 permissions)
```
view_sermons
create_sermons
edit_sermons
delete_sermons
```
**Assigned to**: Admin, Editor (all), User (view only)

### Events (4 permissions)
```
view_events
create_events
edit_events
delete_events
```
**Assigned to**: Admin, Editor (all), User (view only)

### Blog Posts (4 permissions)
```
view_blog_posts
create_blog_posts
edit_blog_posts
delete_blog_posts
```
**Assigned to**: Admin, Editor (all), User (view only)

### Galleries (4 permissions)
```
view_galleries
create_galleries
edit_galleries
delete_galleries
```
**Assigned to**: Admin, Editor (all), User (view only)

### Live Streams (4 permissions)
```
view_live_streams
create_live_streams
edit_live_streams
delete_live_streams
```
**Assigned to**: Admin, Editor (all), User (view only)

---

## 🚀 Common Tasks

### Create a New Content Manager
1. Go to **User Management > Users**
2. Click **New User**
3. Fill in details
4. Set **Role** to **Editor**
5. Click **Create**

### Create a New Admin
```bash
bash create-admin.sh
```
Or manually:
1. Go to **User Management > Users**
2. Click **New User**
3. Fill in details
4. Set **Role** to **Admin**
5. Click **Create**

### Change User Role
1. Go to **User Management > Users**
2. Click **Edit** on the user
3. Change the **Role** dropdown
4. Click **Save**

### Create Custom Role
1. Go to **User Management > Roles**
2. Click **New Role**
3. Enter role name (e.g., "moderator")
4. Select desired permissions
5. Click **Create**

---

## 🔒 Security Best Practices

1. **Limit Admin Access**: Only give admin role to trusted individuals
2. **Use Editor Role**: For content managers who don't need user management
3. **Regular Audits**: Review user roles quarterly
4. **Strong Passwords**: Enforce strong passwords for all admin/editor accounts
5. **Remove Unused Accounts**: Delete or disable accounts that are no longer needed

---

## 📞 Need Help?

- **Full Documentation**: See `USER_MANAGEMENT.md`
- **Setup Guide**: See `SETUP_SUMMARY.md`
- **Create Admin**: Run `bash create-admin.sh`
- **List Users**: Run `bash create-admin.sh --list`
- **Update Password**: Run `bash create-admin.sh --update`

---

## 🛠️ Troubleshooting

### User Can't Access Admin Panel
**Solution**: Ensure user has `admin` or `editor` role

### Permission Not Working
**Solution**: Run `php artisan permission:cache-reset`

### Role Not Showing
**Solution**: Run `php artisan db:seed --class=RolePermissionSeeder`

---

**Last Updated**: April 27, 2026
