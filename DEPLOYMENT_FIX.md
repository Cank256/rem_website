# Fix for "No such file or directory" Error

## Problem

When running `migrate.php`, you got this error:
```
file_put_contents(...storage/framework/views/...): Failed to open stream: No such file or directory
```

## Cause

The `storage/framework/views` directory (and possibly other storage directories) don't exist on the server. This happens because:
1. Empty directories aren't included in ZIP files
2. Git doesn't track empty directories
3. The storage structure needs to be created before running migrations

## Solution

I've created an improved set of deployment scripts that handle this automatically.

---

## New Deployment Order

### 1. Setup Storage Structure FIRST ⭐

**File:** `public/setup-storage.php`

This script:
- Creates all required storage directories
- Sets proper permissions
- Checks if everything is ready
- Must be run BEFORE migrations

**How to use:**
1. Upload `setup-storage.php` to your server's `public/` folder
2. Visit: `https://yourdomain.com/setup-storage.php`
3. You'll see a list of created/existing directories
4. Delete the file immediately

### 2. Run Migrations

**File:** `public/migrate.php`

This improved script:
- Checks if storage directories exist first
- Shows clear error messages
- Verifies .env file exists
- Better error handling

**How to use:**
1. Upload `migrate.php` to `public/` folder
2. Visit: `https://yourdomain.com/migrate.php`
3. Should see "Migrations completed successfully"
4. Delete the file immediately

### 3. Create Storage Link

**File:** `public/storage-link.php`

This improved script:
- Creates the storage symlink
- Verifies it was created
- Provides manual workaround if symlinks don't work

**How to use:**
1. Upload `storage-link.php` to `public/` folder
2. Visit: `https://yourdomain.com/storage-link.php`
3. Should see "Storage link created successfully"
4. Delete the file immediately

### 4. Create Admin User

**File:** `public/create-admin.php`

This improved script:
- Validates email before running
- Checks if user already exists
- Shows clear credentials
- Better error messages

**How to use:**
1. Upload `create-admin.php` to `public/` folder
2. Edit the file and change email/password
3. Visit: `https://yourdomain.com/create-admin.php`
4. Save the credentials shown
5. Delete the file immediately

---

## Quick Fix Steps

If you're already in the middle of deployment:

### Option A: Use the New Scripts (Recommended)

1. **Delete old scripts** from your server:
   - Delete `migrate.php` (if exists)
   - Delete `storage-link.php` (if exists)
   - Delete `create-admin.php` (if exists)

2. **Upload new scripts** from your local `public/` folder:
   - `setup-storage.php` ⭐ NEW
   - `migrate.php` (improved)
   - `storage-link.php` (improved)
   - `create-admin.php` (improved)

3. **Run in order:**
   ```
   1. setup-storage.php
   2. migrate.php
   3. storage-link.php
   4. create-admin.php
   ```

4. **Delete all scripts** after each step

### Option B: Manual Fix (If scripts don't work)

1. **Create directories manually** via File Manager:
   ```
   storage/app/public/
   storage/framework/cache/data/
   storage/framework/sessions/
   storage/framework/testing/
   storage/framework/views/
   storage/logs/
   ```

2. **Set permissions** to 755 for all folders

3. **Create .gitignore** in each folder:
   ```
   *
   !.gitignore
   ```

4. **Then run** `migrate.php`

---

## Testing Locally First

Before uploading to server, test locally:

```bash
cd church-website

# Test setup-storage
php public/setup-storage.php

# Test migrate
php public/migrate.php

# Test storage-link
php public/storage-link.php

# Test create-admin (edit email/password first)
php public/create-admin.php
```

All should work without errors.

---

## What Each Script Does

### setup-storage.php
```
✅ Creates: storage/app/public
✅ Creates: storage/framework/cache/data
✅ Creates: storage/framework/sessions
✅ Creates: storage/framework/testing
✅ Creates: storage/framework/views
✅ Creates: storage/logs
✅ Checks permissions
✅ Creates .gitignore files
```

### migrate.php
```
✅ Checks storage directories exist
✅ Checks .env file exists
✅ Runs database migrations
✅ Shows clear error messages
✅ Provides troubleshooting tips
```

### storage-link.php
```
✅ Creates storage/app/public if missing
✅ Creates symlink: public/storage -> storage/app/public
✅ Verifies symlink was created
✅ Provides manual workaround if needed
```

### create-admin.php
```
✅ Validates email is changed
✅ Checks if user already exists
✅ Creates admin user
✅ Shows credentials clearly
✅ Provides login URL
```

---

## Troubleshooting

### Still getting "No such file or directory"?

1. **Check if directories exist:**
   - Use File Manager
   - Navigate to `storage/framework/`
   - Verify `views/` folder exists

2. **Check permissions:**
   - Right-click `storage/` folder
   - Change Permissions
   - Set to 755
   - Check "Recurse into subdirectories"

3. **Try manual creation:**
   - Create each directory manually
   - Set permissions to 755
   - Then run migrate.php

### Database connection errors?

1. **Check .env file:**
   ```env
   DB_HOST=localhost  (or 127.0.0.1)
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

2. **Verify in cPanel:**
   - Database exists
   - User exists
   - User has ALL PRIVILEGES

### Symlink not working?

Some shared hosts don't allow symlinks. If `storage-link.php` fails:

1. **Manual workaround:**
   - Create folder: `public/storage/`
   - When you upload images via admin, they go to `storage/app/public/`
   - Manually copy them to `public/storage/` to display

2. **Or ask hosting support** to enable symlink support

---

## Files Location

All scripts are in your local project:
```
church-website/public/
├── setup-storage.php    ⭐ Run this FIRST
├── migrate.php          Run second
├── storage-link.php     Run third
└── create-admin.php     Run fourth
```

---

## Security Reminder

⚠️  **CRITICAL:** Delete each script immediately after running!

These scripts have access to your application and database. Leaving them on the server is a security risk.

**After each script:**
1. Verify it worked
2. Delete the file via File Manager
3. Move to next script

---

## Summary

**The fix:**
1. Run `setup-storage.php` FIRST to create directories
2. Then run `migrate.php` to create database tables
3. Then run `storage-link.php` to link storage
4. Finally run `create-admin.php` to create admin user
5. Delete all scripts

**Your deployment should now work!** ✅

---

**Need Help?**

If you still have issues:
1. Check the error message carefully
2. Look in `storage/logs/laravel.log`
3. Enable `APP_DEBUG=true` temporarily
4. Contact your hosting support

---

**Last Updated:** April 20, 2026
