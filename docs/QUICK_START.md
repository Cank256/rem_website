# ⚡ Quick Start Guide - Church Website

Get up and running in 5 minutes!

## 🚀 Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/MariaDB
- Terminal/Command Line

---

## 📦 Installation (Copy & Paste)

### Step 1: Navigate to Project
```bash
cd church-website
```

### Step 2: Install Dependencies
```bash
composer install && npm install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure Database
Edit `.env` file:
```env
DB_DATABASE=church_website
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Setup Database
```bash
php artisan migrate --seed
```

### Step 6: Create Admin User
```bash
php artisan make:filament-user
```
Enter your details when prompted.

### Step 7: Build Assets
```bash
npm run build
```

### Step 8: Start Server
```bash
php artisan serve
```

---

## 🎯 Access Your Site

- **Public Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

Login with the credentials you created in Step 6.

---

## 🎨 Quick Customization

### Change Church Name

**File**: `resources/js/Components/Layout.jsx`
```javascript
// Line 13
<Link href="/" className="text-2xl font-bold text-indigo-600">
    Your Church Name  {/* Change this */}
</Link>
```

**File**: `.env`
```env
APP_NAME="Your Church Name"
```

### Change Colors

**File**: `tailwind.config.js`
```javascript
theme: {
    extend: {
        colors: {
            // Add your custom colors
            'church-primary': '#your-color',
        }
    }
}
```

Then rebuild:
```bash
npm run build
```

---

## 📝 Add Your First Content

### Add a Sermon
1. Go to http://localhost:8000/admin
2. Click "Sermons" in sidebar
3. Click "New Sermon"
4. Fill in the form:
   - Title: "Sunday Service - Faith and Hope"
   - Speaker: "Pastor John"
   - Date: Select date
   - YouTube URL: https://www.youtube.com/watch?v=example
5. Click "Create"

### Add an Event
1. Click "Events" in sidebar
2. Click "New Event"
3. Fill in the form:
   - Title: "Sunday Worship Service"
   - Start: Select date and time
   - Location: "Main Sanctuary"
   - Description: "Join us for worship"
5. Click "Create"

### Add a Blog Post
1. Click "Blog Posts" in sidebar
2. Click "New Blog Post"
3. Fill in the form:
   - Title: "Welcome to Our Church"
   - Author: Select your user
   - Content: Write your post
   - Published At: Select date/time (or leave empty for draft)
4. Click "Create"

---

## 🔄 Development Workflow

### Make Changes to Frontend
```bash
# Start development server with hot reload
npm run dev

# In another terminal
php artisan serve
```

### Make Changes to Backend
```bash
# Just save your PHP files
# Refresh browser to see changes
```

### Clear Caches (if things break)
```bash
php artisan optimize:clear
```

---

## 🐛 Troubleshooting

### "500 Error" on homepage
```bash
chmod -R 755 storage bootstrap/cache
php artisan optimize:clear
```

### Assets not loading
```bash
npm run build
php artisan config:clear
```

### Database connection error
Check `.env` file database credentials

### Admin panel not working
```bash
php artisan config:clear
php artisan route:clear
```

---

## 📚 Next Steps

1. ✅ **Customize branding** - Update church name and colors
2. ✅ **Add content** - Create sermons, events, and blog posts
3. ✅ **Test everything** - Browse the site and admin panel
4. ✅ **Read full docs** - Check README.md and DEPLOYMENT_GUIDE.md
5. ✅ **Deploy** - Follow DEPLOYMENT_GUIDE.md for cPanel

---

## 🎓 Learn More

- **Full Documentation**: See README.md
- **Deployment Guide**: See DEPLOYMENT_GUIDE.md
- **All Commands**: See COMMANDS_REFERENCE.md
- **Project Overview**: See PROJECT_SUMMARY.md

---

## 💡 Pro Tips

### Tip 1: Use Tinker for Quick Tests
```bash
php artisan tinker

# Try these commands:
Sermon::count()
Event::upcoming()->get()
User::all()
```

### Tip 2: Watch for Changes
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### Tip 3: Fresh Start Anytime
```bash
php artisan migrate:fresh --seed
```

### Tip 4: Check Routes
```bash
php artisan route:list
```

### Tip 5: View Logs
```bash
tail -f storage/logs/laravel.log
```

---

## 🆘 Need Help?

1. Check error in `storage/logs/laravel.log`
2. Run `php artisan optimize:clear`
3. Check documentation files
4. Search Laravel docs: https://laravel.com/docs
5. Search Filament docs: https://filamentphp.com/docs

---

## ✅ Quick Checklist

Before deploying, make sure:
- [ ] Church name updated
- [ ] Colors customized (optional)
- [ ] Sample content added
- [ ] Admin user created
- [ ] Everything tested locally
- [ ] `.env` configured for production
- [ ] Assets built: `npm run build`
- [ ] Caches cleared: `php artisan optimize:clear`

---

**That's it! You're ready to go! 🎉**

For detailed information, see the other documentation files:
- README.md - Complete project overview
- DEPLOYMENT_GUIDE.md - cPanel deployment
- COMMANDS_REFERENCE.md - All terminal commands
- PROJECT_SUMMARY.md - What's been built

**Happy building! 🚀**
