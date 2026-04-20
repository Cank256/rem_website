# 📋 Deployment Checklist - Church Website

Use this checklist to ensure a smooth deployment to cPanel hosting.

---

## 🔍 PRE-DEPLOYMENT CHECKLIST

### Local Development Complete
- [ ] All features tested locally
- [ ] Admin panel working correctly
- [ ] Frontend displays properly
- [ ] Mobile responsiveness verified
- [ ] All forms validated
- [ ] Media players (YouTube/Audio) working
- [ ] Navigation links functional
- [ ] No console errors in browser

### Content Ready
- [ ] Church name updated in code
- [ ] Logo/branding customized (if applicable)
- [ ] Sample content removed or replaced
- [ ] Real sermons added
- [ ] Real events added
- [ ] Blog posts created
- [ ] Contact information updated in footer

### Code Optimization
- [ ] Run: `composer install --no-dev --optimize-autoloader`
- [ ] Run: `npm run build`
- [ ] Run: `php artisan config:cache`
- [ ] Run: `php artisan route:cache`
- [ ] Run: `php artisan view:cache`
- [ ] Remove development dependencies
- [ ] Check for debug statements in code

### Environment Configuration
- [ ] `.env.example` updated with all required variables
- [ ] `.env` file ready for production (don't upload yet)
- [ ] `APP_ENV=production` set
- [ ] `APP_DEBUG=false` set
- [ ] `APP_URL` set to production domain
- [ ] Database credentials prepared
- [ ] Strong `APP_KEY` generated

### Security Review
- [ ] All passwords are strong
- [ ] `.env` not in version control
- [ ] Sensitive data removed from code
- [ ] CSRF protection enabled
- [ ] SQL injection protection verified
- [ ] XSS protection verified
- [ ] File upload validation (if applicable)

### Files Prepared
- [ ] Create ZIP of project (exclude node_modules, .git)
- [ ] Backup current site (if updating)
- [ ] Database export ready (if migrating)
- [ ] Documentation files included

---

## 🚀 CPANEL SETUP CHECKLIST

### cPanel Access
- [ ] cPanel login credentials obtained
- [ ] SSH access available (optional but recommended)
- [ ] FTP credentials available (alternative)

### PHP Configuration
- [ ] PHP version set to 8.2 or higher
- [ ] Required PHP extensions enabled:
  - [ ] mbstring
  - [ ] xml
  - [ ] pdo
  - [ ] pdo_mysql
  - [ ] openssl
  - [ ] tokenizer
  - [ ] json
  - [ ] bcmath
  - [ ] ctype
  - [ ] fileinfo
  - [ ] curl

### Database Setup
- [ ] MySQL database created
- [ ] Database user created
- [ ] User added to database with ALL PRIVILEGES
- [ ] Database credentials noted:
  - Database name: _______________
  - Username: _______________
  - Password: _______________
  - Host: _______________ (usually localhost)

### SSL Certificate
- [ ] SSL certificate installed (Let's Encrypt recommended)
- [ ] HTTPS working
- [ ] HTTP to HTTPS redirect configured

---

## 📤 FILE UPLOAD CHECKLIST

### Upload Structure
- [ ] Upload entire project to `/home/username/church-website/`
- [ ] Verify all files uploaded successfully
- [ ] Check file count matches local

### Public Directory Setup
Choose one method:

**Method A: Symlink (Recommended)**
- [ ] Delete all files in `public_html/`
- [ ] Create symlink: `ln -s /home/username/church-website/public/* /home/username/public_html/`
- [ ] Verify symlink working

**Method B: Copy & Update**
- [ ] Copy contents of `church-website/public/` to `public_html/`
- [ ] Update `public_html/index.php` paths
- [ ] Verify paths point to correct directories

### File Permissions
- [ ] Set storage permissions: `chmod -R 755 storage`
- [ ] Set cache permissions: `chmod -R 755 bootstrap/cache`
- [ ] Set ownership: `chown -R username:username storage bootstrap/cache`
- [ ] Verify web server can write to storage

---

## ⚙️ CONFIGURATION CHECKLIST

### Environment File
- [ ] Upload `.env` file to `/home/username/church-website/`
- [ ] Update `.env` with production values:
  ```env
  APP_NAME="Your Church Name"
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://yourdomain.com
  
  DB_CONNECTION=mysql
  DB_HOST=localhost
  DB_PORT=3306
  DB_DATABASE=your_cpanel_database
  DB_USERNAME=your_cpanel_user
  DB_PASSWORD=your_cpanel_password
  ```
- [ ] Verify `.env` file permissions: `chmod 644 .env`

### Application Key
- [ ] Generate key: `php artisan key:generate`
- [ ] Verify `APP_KEY` in `.env` is set

### Database Migration
Choose one method:

**Method A: Import SQL**
- [ ] Go to phpMyAdmin
- [ ] Select database
- [ ] Import SQL dump
- [ ] Verify all tables imported

**Method B: Run Migrations**
- [ ] SSH into server
- [ ] Navigate to project: `cd /home/username/church-website`
- [ ] Run: `php artisan migrate --force`
- [ ] Verify tables created

### Create Admin User
- [ ] Run: `php artisan make:filament-user`
- [ ] Enter admin credentials
- [ ] Note credentials securely

---

## 🧪 TESTING CHECKLIST

### Basic Functionality
- [ ] Visit homepage: `https://yourdomain.com`
- [ ] Homepage loads without errors
- [ ] Navigation menu works
- [ ] Footer displays correctly
- [ ] Mobile menu works

### Admin Panel
- [ ] Visit: `https://yourdomain.com/admin`
- [ ] Admin login page loads
- [ ] Can login with admin credentials
- [ ] Dashboard displays
- [ ] Can view sermons list
- [ ] Can create new sermon
- [ ] Can edit sermon
- [ ] Can delete sermon
- [ ] Same tests for Events
- [ ] Same tests for Blog Posts

### Frontend Features
- [ ] Recent sermons display on homepage
- [ ] Upcoming events display on homepage
- [ ] YouTube player works
- [ ] Audio player works (if applicable)
- [ ] All links work
- [ ] Images load correctly
- [ ] Forms work (if applicable)

### Mobile Testing
- [ ] Test on mobile device or emulator
- [ ] Navigation menu works
- [ ] Content displays properly
- [ ] Touch interactions work
- [ ] Media players work on mobile

### Browser Testing
- [ ] Test in Chrome
- [ ] Test in Firefox
- [ ] Test in Safari
- [ ] Test in Edge
- [ ] No console errors

### Performance
- [ ] Page load time acceptable (< 3 seconds)
- [ ] Images optimized
- [ ] No 404 errors
- [ ] No broken links

---

## 🔧 POST-DEPLOYMENT CHECKLIST

### Optimization
- [ ] Clear all caches: `php artisan optimize:clear`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer dump-autoload --optimize`

### Security Hardening
- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `.env` not publicly accessible
- [ ] Test that `/storage` is not publicly accessible
- [ ] Verify HTTPS working
- [ ] Test admin panel security
- [ ] Change default admin password (if used)

### Monitoring Setup
- [ ] Set up error monitoring (optional)
- [ ] Configure log rotation
- [ ] Set up backup schedule
- [ ] Configure uptime monitoring (optional)
- [ ] Set up analytics (optional)

### Documentation
- [ ] Document server credentials (securely)
- [ ] Document database credentials (securely)
- [ ] Document admin credentials (securely)
- [ ] Note deployment date
- [ ] Create maintenance log

---

## 📊 VERIFICATION CHECKLIST

### URLs to Test
- [ ] `https://yourdomain.com` - Homepage
- [ ] `https://yourdomain.com/admin` - Admin panel
- [ ] `https://yourdomain.com/login` - User login
- [ ] `https://yourdomain.com/register` - Registration
- [ ] Check all navigation links

### Error Checking
- [ ] Check `storage/logs/laravel.log` for errors
- [ ] Check cPanel error logs
- [ ] Check browser console for JavaScript errors
- [ ] Verify no 500 errors
- [ ] Verify no 404 errors

### Database Verification
- [ ] Login to phpMyAdmin
- [ ] Verify all tables exist:
  - [ ] users
  - [ ] sermons
  - [ ] events
  - [ ] blog_posts
  - [ ] migrations
  - [ ] cache
  - [ ] jobs
  - [ ] sessions
- [ ] Verify data exists in tables

---

## 🔄 ROLLBACK PLAN (If Something Goes Wrong)

### Immediate Actions
- [ ] Enable maintenance mode: `php artisan down`
- [ ] Check error logs: `storage/logs/laravel.log`
- [ ] Check cPanel error logs

### Common Fixes
- [ ] Clear all caches: `php artisan optimize:clear`
- [ ] Fix file permissions: `chmod -R 755 storage bootstrap/cache`
- [ ] Verify `.env` configuration
- [ ] Check database connection
- [ ] Verify `public_html/index.php` paths

### Full Rollback
- [ ] Restore previous site backup
- [ ] Restore database backup
- [ ] Verify old site working
- [ ] Investigate issues before retry

---

## 📝 POST-LAUNCH TASKS

### Week 1
- [ ] Monitor error logs daily
- [ ] Check site performance
- [ ] Verify all features working
- [ ] Collect user feedback
- [ ] Fix any reported issues

### Week 2-4
- [ ] Monitor error logs weekly
- [ ] Check analytics (if configured)
- [ ] Update content regularly
- [ ] Backup database weekly
- [ ] Plan future enhancements

### Monthly
- [ ] Update dependencies: `composer update`
- [ ] Update npm packages: `npm update`
- [ ] Security audit
- [ ] Performance review
- [ ] Backup verification

---

## 🆘 TROUBLESHOOTING GUIDE

### Issue: 500 Internal Server Error
**Check:**
- [ ] `.env` file exists and is configured
- [ ] File permissions: `chmod -R 755 storage bootstrap/cache`
- [ ] `storage/logs/laravel.log` for specific error
- [ ] PHP version is 8.2+
- [ ] All required PHP extensions enabled

**Fix:**
```bash
php artisan optimize:clear
chmod -R 755 storage bootstrap/cache
```

### Issue: Assets Not Loading (CSS/JS)
**Check:**
- [ ] `public/build` directory exists
- [ ] `APP_URL` in `.env` matches domain
- [ ] HTTPS is working
- [ ] No mixed content warnings

**Fix:**
```bash
npm run build
php artisan config:clear
```

### Issue: Database Connection Error
**Check:**
- [ ] Database credentials in `.env`
- [ ] Database exists in cPanel
- [ ] User has privileges
- [ ] Host is correct (usually `localhost`)

**Fix:**
- Verify credentials in cPanel
- Test connection in phpMyAdmin
- Update `.env` with correct values

### Issue: Admin Panel Not Accessible
**Check:**
- [ ] Filament installed: `composer show filament/filament`
- [ ] Routes cached: `php artisan route:clear`
- [ ] Config cached: `php artisan config:clear`

**Fix:**
```bash
php artisan config:clear
php artisan route:clear
composer dump-autoload
```

### Issue: Blank Page
**Check:**
- [ ] PHP error logs
- [ ] Laravel logs: `storage/logs/laravel.log`
- [ ] Browser console for errors

**Fix:**
- Enable error display temporarily to see error
- Check file permissions
- Clear all caches

---

## ✅ FINAL SIGN-OFF

### Deployment Complete
- [ ] All checklist items completed
- [ ] Site fully functional
- [ ] Admin panel working
- [ ] No critical errors
- [ ] Performance acceptable
- [ ] Security verified
- [ ] Documentation updated
- [ ] Stakeholders notified

### Deployment Details
- **Deployment Date**: _______________
- **Deployed By**: _______________
- **Domain**: _______________
- **Server**: _______________
- **PHP Version**: _______________
- **Laravel Version**: 11.x
- **Database**: _______________

### Sign-Off
- **Developer**: _______________ Date: _______________
- **Client/Pastor**: _______________ Date: _______________

---

## 📞 SUPPORT CONTACTS

### Technical Support
- **Hosting Provider**: _______________
- **Support Email**: _______________
- **Support Phone**: _______________

### Developer Contact
- **Name**: _______________
- **Email**: _______________
- **Phone**: _______________

---

**Congratulations on your deployment! 🎉**

Keep this checklist for future reference and updates.

For detailed instructions, refer to:
- DEPLOYMENT_GUIDE.md
- README.md
- COMMANDS_REFERENCE.md
