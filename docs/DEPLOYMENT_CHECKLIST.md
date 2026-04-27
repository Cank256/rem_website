# cPanel Deployment Checklist

Quick checklist for deploying to shared hosting without terminal access.

---

## Before You Start

- [ ] PHP 8.2+ available on hosting
- [ ] MySQL database access
- [ ] cPanel login credentials
- [ ] Domain name configured

---

## Local Preparation

- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm install && npm run build`
- [ ] Generate APP_KEY: `php artisan key:generate --show`
- [ ] Create `.env.production` with production settings
- [ ] Create ZIP file (exclude node_modules, .git, tests)

---

## cPanel Setup

- [ ] Create MySQL database
- [ ] Create database user
- [ ] Grant ALL PRIVILEGES to user
- [ ] Save database credentials
- [ ] Set PHP version to 8.2+
- [ ] Enable required PHP extensions

---

## File Upload

- [ ] Upload ZIP to server
- [ ] Extract files to `/home/username/laravel/`
- [ ] Delete ZIP file
- [ ] Rename `.env.production` to `.env`
- [ ] Update `.env` with database credentials

---

## Permissions

- [ ] Set `storage/` to 755 (recursive)
- [ ] Set `bootstrap/cache/` to 755 (recursive)
- [ ] Set files in `storage/` to 644
- [ ] Set files in `bootstrap/cache/` to 644

---

## Domain Configuration

Choose one:
- [ ] **Option A:** Redirect main domain to `/laravel/public/`
- [ ] **Option B:** Create subdomain pointing to `/laravel/public/`
- [ ] **Option C:** Add addon domain pointing to `/laravel/public/`

---

## Database & Setup

- [ ] Create `migrate.php` in `/public/`
- [ ] Visit `yourdomain.com/migrate.php`
- [ ] Verify migrations completed
- [ ] **DELETE `migrate.php` immediately**
- [ ] Create `storage-link.php` in `/public/`
- [ ] Visit `yourdomain.com/storage-link.php`
- [ ] **DELETE `storage-link.php` immediately**

---

## Admin User

- [ ] Create `create-admin.php` in `/public/`
- [ ] Edit email and password in the file
- [ ] Visit `yourdomain.com/create-admin.php`
- [ ] Save admin credentials
- [ ] **DELETE `create-admin.php` immediately**

---

## Security

- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Verify `.env` is not accessible via browser
- [ ] Add `.htaccess` to protect `.env`
- [ ] Enable HTTPS/SSL
- [ ] Update `APP_URL` to use https://
- [ ] Delete all temporary PHP scripts

---

## Testing

- [ ] Visit homepage - loads correctly
- [ ] All navigation links work
- [ ] Visit `/admin` - login page appears
- [ ] Login with admin credentials
- [ ] Upload test image in Gallery
- [ ] Create test event with image
- [ ] Create test sermon
- [ ] Visit `/gallery` - images display
- [ ] Visit `/events` - events display
- [ ] Visit `/sermons` - sermons display
- [ ] Click "Learn More" on event - detail page works
- [ ] Test "Load More" on sermons
- [ ] Test "Load More" on events
- [ ] Check mobile responsiveness
- [ ] Test all forms work

---

## Post-Deployment

- [ ] Backup database (phpMyAdmin > Export)
- [ ] Backup files (compress laravel folder)
- [ ] Document admin credentials securely
- [ ] Setup email (if needed)
- [ ] Configure cron jobs (if needed)
- [ ] Add content (sermons, events, galleries)
- [ ] Test with real users

---

## Troubleshooting Quick Fixes

**500 Error:**
- Check `.env` exists and has correct DB credentials
- Check folder permissions
- Temporarily enable `APP_DEBUG=true`

**Database Error:**
- Verify DB credentials in `.env`
- Try `DB_HOST=localhost` or `127.0.0.1`
- Check user has privileges

**Images Not Showing:**
- Re-run storage link script
- Check `public/storage` exists
- Re-upload images

**CSS/JS Not Loading:**
- Verify `public/build` folder exists
- Check `.htaccess` in public folder
- Clear browser cache

---

## Important Files Location

```
/home/username/laravel/
├── .env                    # Configuration
├── storage/logs/           # Error logs
├── public/                 # Web root
│   ├── .htaccess          # URL rewriting
│   └── index.php          # Entry point
└── bootstrap/cache/        # Cache files
```

---

## Quick Commands (If Terminal Available)

If your host adds terminal later:

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Create admin user
php artisan make:filament-user
```

---

## Support Contacts

- **Hosting Support:** Contact for server issues
- **Database Issues:** Check cPanel > MySQL Databases
- **Domain Issues:** Check cPanel > Domains
- **SSL Issues:** Check cPanel > SSL/TLS Status

---

## Backup Schedule

Recommended:
- **Daily:** Database backup (automated if possible)
- **Weekly:** Full file backup
- **Before Updates:** Both database and files

---

## Update Procedure

1. Make changes locally
2. Test locally
3. Run `npm run build`
4. Run `composer install --no-dev`
5. Create backup of live site
6. Upload changed files only
7. Clear caches (delete cache files)
8. Test live site

---

## Emergency Rollback

If something breaks:
1. Restore database from backup (phpMyAdmin > Import)
2. Restore files from backup (extract ZIP)
3. Clear all caches
4. Test site

---

## Success Indicators

✅ Homepage loads without errors
✅ Admin panel accessible at `/admin`
✅ Can login to admin
✅ Can upload images
✅ Can create events and sermons
✅ Public pages display content
✅ HTTPS working
✅ Mobile responsive
✅ No console errors

---

## Final Security Check

- [ ] All temporary scripts deleted
- [ ] `APP_DEBUG=false`
- [ ] `.env` not accessible
- [ ] Strong passwords used
- [ ] HTTPS enabled
- [ ] Backups created
- [ ] Admin credentials documented securely

---

**Deployment Complete!** 🎉

Your Rural Evangelical Ministries website is now live!

---

**Document Version:** 1.0
**Last Updated:** April 20, 2026
