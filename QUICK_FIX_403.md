# Quick Fix: 403 Forbidden on /admin

## 🚀 3-Minute Fix

### Step 1: Upload Diagnostic Script (30 seconds)

1. Upload `check-admin.php` to your server's `public/` folder
2. Visit: `https://yourdomain.com/check-admin.php`
3. Read the output - it will tell you what's wrong
4. Delete the file

### Step 2: Fix Permissions (1 minute)

1. Upload `fix-permissions.php` to `public/` folder
2. Visit: `https://yourdomain.com/fix-permissions.php`
3. Should see "Permissions set" messages
4. Delete the file

### Step 3: Check Document Root (1 minute)

**This is the #1 cause of 403 errors!**

**In cPanel:**
1. Go to **Domains**
2. Click your domain name
3. Look at **Document Root**
4. Should be: `/public_html/public` (or similar ending in `/public`)
5. If it's just `/public_html`, change it to `/public_html/public`
6. Save and wait 2-3 minutes

### Step 4: Test (30 seconds)

1. Clear browser cache (Ctrl+Shift+Delete)
2. Visit: `https://yourdomain.com/admin`
3. Login with your credentials
4. Should work now! ✅

---

## 🎯 If Still Not Working

### Quick Check: File Permissions in cPanel

1. Go to **File Manager**
2. Navigate to your website folder
3. Select the **storage** folder
4. Right-click → **Permissions**
5. Set to **755**
6. Check "Recurse into subdirectories"
7. Click OK
8. Repeat for **bootstrap/cache** folder

### Quick Check: Clear Cache Files

1. Go to **File Manager**
2. Navigate to `bootstrap/cache/`
3. Delete these files (keep .gitignore):
   - config.php
   - routes-v7.php
   - packages.php
   - services.php
4. Try accessing `/admin` again

---

## 📋 Quick Checklist

- [ ] Ran `check-admin.php` - saw what's wrong
- [ ] Ran `fix-permissions.php` - fixed permissions
- [ ] Document root points to `/public` folder
- [ ] Cleared browser cache
- [ ] Deleted diagnostic scripts

---

## 🆘 Emergency Debug

If nothing works, enable debug mode to see the actual error:

1. Edit `.env` file
2. Change: `APP_DEBUG=false` to `APP_DEBUG=true`
3. Visit `/admin` - you'll see the real error
4. Fix the error
5. Change back to `APP_DEBUG=false`

---

## 📞 Common Errors & Fixes

| Error | Fix |
|-------|-----|
| "403 Forbidden" | Document root not pointing to `public/` |
| "500 Internal Server Error" | Check permissions on storage/ and bootstrap/cache/ |
| "No input file specified" | Document root issue or .htaccess missing |
| "Class not found" | Run `composer install --no-dev` locally and re-upload |
| "Database connection failed" | Check .env database credentials |

---

## ✅ Success!

When it works, you should:
1. See the Filament dashboard
2. Be able to click on "Sermons", "Events", etc.
3. Be able to upload images
4. See no errors

**Don't forget to:**
- Set `APP_DEBUG=false` in `.env`
- Delete all diagnostic PHP files
- Test uploading content

---

**Need more help?** Read `TROUBLESHOOTING_403.md` for detailed solutions.
