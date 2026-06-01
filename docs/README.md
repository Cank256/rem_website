# Church Website Documentation

## Quick Navigation

This documentation has been streamlined into 6 essential files:

### 📖 [1. Getting Started](1-GETTING-STARTED.md)
**Start here!** Complete setup guide for local development.
- 5-minute installation
- Configuration steps
- First content setup
- YouTube integration
- Email configuration
- Analytics setup

### 🚀 [2. Deployment](2-DEPLOYMENT.md)
Production deployment guide for cPanel/shared hosting.
- Pre-deployment preparation
- cPanel configuration
- Database setup without terminal
- Security hardening
- Post-deployment testing
- Updates and maintenance

### ✅ [Deployment Checklist](DEPLOYMENT_CHECKLIST.md)
Quick reference checklist for deployment verification.
- Step-by-step deployment verification
- Testing checklist
- Security verification
- Quick troubleshooting

### 🐛 [3. Troubleshooting](3-TROUBLESHOOTING.md)
Solutions for common issues.
- Application errors (500, 404, CSRF)
- Database problems
- File upload issues
- Asset loading problems
- Admin panel issues
- YouTube API errors
- Email problems
- Analytics troubleshooting
- Debug tools and commands

### ⭐ [4. Features](4-FEATURES.md)
Complete feature list and capabilities.
- Content management (Sermons, Events, Blog, Galleries)
- YouTube live streaming and auto-sync
- Analytics with GDPR compliance
- Email integration (Resend)
- Image compression and optimization
- User roles and permissions
- Technology stack

### 💻 [5. Commands](5-COMMANDS.md)
Quick reference for all terminal commands.
- Setup commands
- Filament admin commands
- YouTube sync commands
- Database and migrations
- Cache management
- NPM and Composer
- Production deployment
- Debugging tools

### 👥 [User Management](USER_MANAGEMENT.md)
User roles, permissions, and access control.
- Role-based access control
- Admin/Editor/User roles
- Permission management
- Spatie Permission integration

---

## Quick Links

### Getting Started
```bash
cd church-website
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan make:filament-user
npm run build
php artisan serve
```

### Access Points
- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

### Key Features
✅ Sermons with YouTube/Audio
✅ Events with images
✅ Blog posts
✅ Photo galleries
✅ Live streaming
✅ Analytics (GDPR compliant)
✅ Email integration
✅ Role-based access control

---

## Project Overview

### Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: React 18, Inertia.js
- **Styling**: Tailwind CSS
- **Admin**: Filament v3
- **Database**: MySQL/MariaDB
- **Build**: Vite 6

### What's Included
- Modern church website
- Full admin panel (Filament)
- Content management system
- YouTube integration (live + auto-sync)
- Analytics system
- Email integration (Resend)
- User management
- Image optimization
- Mobile responsive
- SEO friendly

---

## Documentation Structure

### For New Users
1. Read [Getting Started](1-GETTING-STARTED.md)
2. Configure your site
3. Add content through admin panel
4. Read [Features](4-FEATURES.md) to learn what's possible

### For Deployment
1. Read [Deployment](2-DEPLOYMENT.md)
2. Follow [Deployment Checklist](DEPLOYMENT_CHECKLIST.md)
3. Refer to [Troubleshooting](3-TROUBLESHOOTING.md) if issues arise

### For Daily Use
- [Commands](5-COMMANDS.md) - Quick command reference
- [User Management](USER_MANAGEMENT.md) - Managing users and roles
- [Features](4-FEATURES.md) - Understanding all capabilities

---

## What Changed (Consolidation)

**Before**: 50+ documentation files with lots of redundancy
**After**: 6 focused, essential files

### Files Removed
All specific fix/update/implementation docs have been removed:
- ❌ Setup guides (5 files) → Merged into Getting Started
- ❌ Deployment guides (7 files) → Merged into Deployment
- ❌ Analytics docs (4 files) → Merged into Features
- ❌ YouTube sync docs (5 files) → Merged into Features + Getting Started
- ❌ Email docs (5 files) → Merged into Getting Started
- ❌ Fix/update docs (15+ files) → Removed (outdated)
- ❌ Project overview docs (4 files) → Merged into Features

### Why This Is Better
✅ Single source of truth for each topic
✅ No confusion about which file to read
✅ Easier to maintain
✅ Faster to find information
✅ Better organized

---

## Support

### Stuck on Something?
1. Check [Troubleshooting](3-TROUBLESHOOTING.md)
2. Review [Commands](5-COMMANDS.md)
3. Check Laravel logs: `storage/logs/laravel.log`
4. Enable debug mode temporarily (see Troubleshooting)

### External Resources
- Laravel Docs: https://laravel.com/docs/11.x
- Filament Docs: https://filamentphp.com/docs/3.x
- Inertia Docs: https://inertiajs.com
- Tailwind Docs: https://tailwindcss.com/docs

---

## Contributing

Found an issue or have a suggestion? Please update the relevant documentation file:
- Setup issues → `1-GETTING-STARTED.md`
- Deployment issues → `2-DEPLOYMENT.md`
- Bug fixes → `3-TROUBLESHOOTING.md`
- New features → `4-FEATURES.md`
- New commands → `5-COMMANDS.md`

---

**Project Status**: ✅ Production Ready
**Laravel**: 11.x | **PHP**: 8.2+ | **Filament**: 3.x | **React**: 18.x
