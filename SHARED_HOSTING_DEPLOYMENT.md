# Shared Hosting Deployment (Simple Method)

## ⚡ Quick Deployment for Shared Hosting

Your shared hosting has strict timeout limits. Use these **SQL-based scripts** that work instantly without timeouts.

---

## 📋 3 Simple Steps

### Step 1: Create Database Tables

**File:** `migrate-sql.php`

**What to do:**
1. Upload `migrate-sql.php` to your server's `public/` folder
2. Visit: `https://yourdomain.com/migrate-sql.php`
3. You'll see all 14 tables created instantly (2-3 seconds)
4. **Delete the file immediately**

**Expected output:**
```
✅ migrations (created)
✅ users (created)
✅ sermons (created)
✅ events (created)
✅ galleries (created)
✅ gallery_images (created)
... and 8 more tables

✅ MIGRATION COMPLETE!
```

---

### Step 2: Create Storage Link

**File:** `storage-link.php`

**What to do:**
1. Upload `storage-link.php` to `public/` folder
2. Visit: `https://yourdomain.com/storage-link.php`
3. Should see "Storage link created successfully"
4. **Delete the file immediately**

**If symlinks don't work:**
- The script will tell you
- You'll need to manually copy images from `storage/app/public/` to `public/storage/`
- Or ask your host to enable symlinks

---

### Step 3: Create Admin User

**File:** `create-admin-sql.php`

**What to do:**
1. **FIRST:** Edit the file and change these lines:
   ```php
   $email = 'your-email@domain.com';     // Your email
   $password = 'YourStrongPassword123!';  // Your password
   ```

2. Upload `create-admin-sql.php` to `public/` folder

3. Visit: `https://yourdomain.com/create-admin-sql.php`

4. You'll see:
   ```
   ✅ ADMIN USER CREATED SUCCESSFULLY!
   
   Email:    your-email@domain.com
   Password: YourStrongPassword123!
   
   Login URL: https://yourdomain.com/admin
   ```

5. **Save the credentials!**

6. **Delete the file immediately**

---

## ✅ That's It!

Your website is now deployed!

**Test it:**
1. Visit: `https://yourdomain.com` (homepage)
2. Visit: `https://yourdomain.com/admin` (admin panel)
3. Login with your credentials
4. Upload a test image
5. Create a test event

---

## 🎯 Why These Scripts Work

**Regular scripts (migrate.php, create-admin.php):**
- ❌ Load entire Laravel framework
- ❌ Take 30-60+ seconds
- ❌ Timeout on shared hosting
- ❌ Use lots of memory

**SQL scripts (migrate-sql.php, create-admin-sql.php):**
- ✅ Direct SQL queries
- ✅ Complete in 2-3 seconds
- ✅ No timeouts
- ✅ Minimal memory usage
- ✅ Work on ANY shared hosting

---

## 📁 Files You Need

All in your local `church-website/public/` folder:

```
public/
├── migrate-sql.php         ← Use this for migrations
├── storage-link.php        ← Use this for storage
└── create-admin-sql.php    ← Use this for admin user
```

---

## 🔧 Before Uploading

### 1. Get Latest Files

```bash
cd church-website
git pull origin main
```

### 2. Verify Files Exist

```bash
ls -la public/*sql.php
```

Should show:
- migrate-sql.php
- create-admin-sql.php

### 3. Prepare .env File

Make sure your `.env` file has correct database credentials:

```env
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4. Upload Everything

Upload your entire project to the server:
- All Laravel files
- The 3 scripts above
- Your `.env` file

---

## 📊 What Gets Created

### Tables (14 total):
- ✅ migrations
- ✅ users
- ✅ password_reset_tokens
- ✅ sessions
- ✅ cache
- ✅ cache_locks
- ✅ jobs
- ✅ job_batches
- ✅ failed_jobs
- ✅ sermons
- ✅ events (with image_path column)
- ✅ blog_posts
- ✅ galleries
- ✅ gallery_images

### Admin User:
- ✅ Full access to `/admin`
- ✅ Can manage all content
- ✅ Email verified automatically

---

## ⚠️ Important Notes

### Security:
- **DELETE each script immediately after running**
- These scripts have full database access
- Leaving them is a major security risk

### Order Matters:
1. **First:** migrate-sql.php (creates tables)
2. **Second:** storage-link.php (links storage)
3. **Third:** create-admin-sql.php (creates user)

### Edit Before Running:
- **create-admin-sql.php** must be edited first
- Change email and password
- Save the file
- Then upload and run

---

## 🐛 Troubleshooting

### "Database connection failed"

**Check your .env file:**
```env
DB_HOST=localhost  (try 127.0.0.1 if localhost doesn't work)
DB_DATABASE=correct_database_name
DB_USERNAME=correct_username
DB_PASSWORD=correct_password
```

**Verify in cPanel:**
- Database exists
- User exists
- User has ALL PRIVILEGES on database

### "Table already exists"

**This is OK!** The scripts check for existing tables and skip them.

If you need to start fresh:
1. Go to phpMyAdmin
2. Drop all tables
3. Run migrate-sql.php again

### "User already exists"

**Options:**
1. Use the existing user (if you remember the password)
2. Delete the user from phpMyAdmin and run script again
3. Change the email in the script to create a different admin

### Symlink doesn't work

**Manual workaround:**
1. Create folder: `public/storage/`
2. When you upload images, they go to `storage/app/public/`
3. Manually copy them to `public/storage/` to display
4. Or ask your host to enable symlink support

---

## ✅ Success Checklist

After deployment:

- [ ] All 14 tables exist in database
- [ ] Admin user created
- [ ] Can login to `/admin`
- [ ] Homepage loads
- [ ] All navigation links work
- [ ] Can upload images in admin
- [ ] Can create events
- [ ] Can create sermons
- [ ] Images display on website
- [ ] All 3 scripts deleted from server

---

## 🎉 You're Done!

Your website is now live on shared hosting!

**Next steps:**
1. Add real content (sermons, events, galleries)
2. Update mobile money numbers in Give page
3. Update YouTube channel ID in Live page
4. Upload bishop's photo
5. Test everything thoroughly

---

## 📞 Need Help?

If you still have issues:

1. **Check error logs:**
   - cPanel > Error Logs
   - Or `storage/logs/laravel.log`

2. **Enable debug mode temporarily:**
   - Edit `.env`
   - Set `APP_DEBUG=true`
   - Visit the page to see actual error
   - Set back to `false` after fixing

3. **Contact hosting support:**
   - Ask about PHP timeout limits
   - Ask about symlink support
   - Ask about PHP extensions

---

## 📝 Summary

**3 files, 3 steps, 5 minutes:**

1. `migrate-sql.php` → Creates tables
2. `storage-link.php` → Links storage
3. `create-admin-sql.php` → Creates admin

**All scripts work instantly with no timeouts!**

---

**Last Updated:** April 20, 2026
**Status:** ✅ Tested on shared hosting
**Time to Deploy:** ~5 minutes
