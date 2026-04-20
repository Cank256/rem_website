# Correct Deployment Order

## ⚠️ IMPORTANT: Follow This Exact Order!

The error you're getting is because cached configuration from your local machine is being used on the server. Follow these steps in order to fix it.

---

## Step-by-Step Deployment

### Step 1: Clear Cache FIRST ⭐

**File:** `clear-cache.php`

**What it does:**
- Deletes all cached config files (this fixes your error!)
- Clears compiled views
- Clears framework cache
- Clears sessions
- Checks for local paths in .env

**How to run:**
1. Upload `clear-cache.php` to server's `public/` folder
2. Visit: `https://yourdomain.com/clear-cache.php`
3. You should see "Cache clearing complete"
4. **DELETE the file immediately**

**Why this fixes your error:**
Your error shows `/Users/caleb/Dev/...` which is your LOCAL path. This means Laravel cached your local configuration. Clearing the cache removes these local paths.

---

### Step 2: Setup Storage Structure

**File:** `setup-storage.php`

**What it does:**
- Creates all required storage directories
- Sets proper permissions
- Creates .gitignore files

**How to run:**
1. Upload `setup-storage.php` to `public/` folder
2. Visit: `https://yourdomain.com/setup-storage.php`
3. You should see "Storage structure is ready"
4. **DELETE the file immediately**

---

### Step 3: Run Migrations

**File:** `migrate.php` (improved version)

**What it does:**
- Clears any remaining cached config
- Checks storage directories exist
- Runs database migrations
- Lists created tables

**How to run:**
1. Upload the NEW `migrate.php` to `public/` folder
2. Visit: `https://yourdomain.com/migrate.php`
3. You should see "Migrations completed successfully"
4. **DELETE the file immediately**

---

### Step 4: Create Storage Link

**File:** `storage-link.php`

**What it does:**
- Creates symlink from public/storage to storage/app/public
- Verifies the link was created

**How to run:**
1. Upload `storage-link.php` to `public/` folder
2. Visit: `https://yourdomain.com/storage-link.php`
3. You should see "Storage link created successfully"
4. **DELETE the file immediately**

---

### Step 5: Create Admin User

**File:** `create-admin.php`

**What it does:**
- Creates your first admin user
- Shows credentials clearly

**How to run:**
1. Upload `create-admin.php` to `public/` folder
2. **EDIT the file first** - change email and password
3. Visit: `https://yourdomain.com/create-admin.php`
4. Save the credentials shown
5. **DELETE the file immediately**

---

## Quick Checklist

```
□ 1. Upload clear-cache.php → Run → Delete
□ 2. Upload setup-storage.php → Run → Delete
□ 3. Upload migrate.php → Run → Delete
□ 4. Upload storage-link.php → Run → Delete
□ 5. Edit & upload create-admin.php → Run → Delete
□ 6. Test website
□ 7. Login to /admin
```

---

## Why This Order Matters

**1. Clear Cache First**
- Removes local machine paths from cached config
- Prevents "No such file or directory" errors
- Ensures fresh configuration is loaded

**2. Setup Storage**
- Creates directories Laravel needs
- Must exist before migrations run

**3. Run Migrations**
- Now has clean config and proper directories
- Creates database tables

**4. Storage Link**
- Allows uploaded images to be displayed
- Needs storage/app/public to exist first

**5. Admin User**
- Needs database tables to exist
- Creates your login account

---

## Common Mistakes

❌ **Running migrate.php before clear-cache.php**
- Result: Gets local paths from cached config
- Fix: Run clear-cache.php first

❌ **Running migrate.php before setup-storage.php**
- Result: "No such file or directory" error
- Fix: Run setup-storage.php first

❌ **Not deleting scripts after running**
- Result: Security vulnerability
- Fix: Delete each script immediately after use

❌ **Using old migrate.php**
- Result: Still uses cached config
- Fix: Use the NEW migrate.php that clears cache

---

## Troubleshooting

### Still getting local path error?

**Check these:**

1. **Did you run clear-cache.php first?**
   - This is the most important step
   - It removes the cached local paths

2. **Check bootstrap/cache/ folder**
   - Should be empty except .gitignore
   - Delete any .php files manually if needed

3. **Check .env file**
   - Should NOT contain `/Users/` or `C:\`
   - Should have server paths only

4. **Re-upload vendor folder**
   - Your vendor folder might have cached paths
   - Run `composer install --no-dev` locally
   - Re-upload the vendor folder

### Database connection error?

Check `.env` file:
```env
DB_HOST=localhost  (or 127.0.0.1)
DB_DATABASE=your_actual_database_name
DB_USERNAME=your_actual_database_user
DB_PASSWORD=your_actual_database_password
```

### Storage directory error?

Run `setup-storage.php` again, or create manually:
```
storage/app/public/
storage/framework/cache/data/
storage/framework/sessions/
storage/framework/testing/
storage/framework/views/
storage/logs/
```

---

## Files You Need

All scripts are in your local `church-website/public/` folder:

```
public/
├── clear-cache.php       ← NEW! Run this FIRST
├── setup-storage.php     ← Run second
├── migrate.php           ← Updated! Run third
├── storage-link.php      ← Run fourth
└── create-admin.php      ← Run fifth
```

---

## Before Uploading

**On your local machine:**

1. **Make sure you have the latest files:**
   ```bash
   cd church-website
   git pull origin main
   ```

2. **Check the scripts exist:**
   ```bash
   ls -la public/*.php
   ```

3. **Test locally (optional):**
   ```bash
   php public/clear-cache.php
   php public/setup-storage.php
   php public/migrate.php
   ```

4. **Prepare for upload:**
   - All 5 scripts should be in `public/` folder
   - Ready to upload to server

---

## After Deployment

**Test everything:**

1. Visit your website homepage
2. Visit `/admin` - should see login page
3. Login with admin credentials
4. Upload a test image in Gallery
5. Create a test event
6. Visit `/gallery` - image should display
7. Visit `/events` - event should display

**If everything works:**
- ✅ Deployment successful!
- ✅ All scripts deleted
- ✅ Website is live

---

## Security Reminder

⚠️ **CRITICAL:** Delete each script immediately after running!

These scripts have full access to your application. Leaving them on the server is a major security risk.

**After each script:**
1. Verify it worked
2. Go to File Manager
3. Delete the .php file
4. Move to next script

---

## Summary

**Your error is caused by cached local paths.**

**The fix:**
1. Run `clear-cache.php` FIRST to remove cached local paths
2. Then follow the rest of the steps in order
3. The NEW `migrate.php` also clears cache before running

**This will fix your deployment!** ✅

---

**Last Updated:** April 20, 2026
**Status:** Ready to deploy
