# Church Website - Modern Laravel Application

A modern, responsive church website built with Laravel 11, React 18, Inertia.js, and Filament v3 admin panel. Designed for deployment on traditional cPanel shared hosting.

## 🚀 Features

### Public Website
- **Homepage**: Hero section with recent sermons and upcoming events
- **Sermons**: Browse and watch/listen to sermons with YouTube and audio player integration
- **Events**: View upcoming church events with date, time, and location
- **Blog**: Read articles and updates from church leadership
- **Responsive Design**: Mobile-first design with Tailwind CSS
- **Fast Performance**: Optimized with Vite bundler

### Admin Panel (Filament v3)
- **Sermon Management**: Add sermons with YouTube URLs, audio files, and descriptions
- **Event Management**: Schedule events with date/time pickers and location
- **Blog Management**: Rich text editor for creating blog posts
- **Gallery Management**: Upload and organize photo galleries
- **Live Stream Management**: Configure and manage live streaming
- **User Management**: Comprehensive role-based access control with permissions
- **Role & Permission Management**: Create custom roles and assign granular permissions
- **Form Validation**: Built-in validation with helpful error messages
- **Modern UI**: Clean, intuitive interface

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: React 18
- **Bridge**: Inertia.js
- **Styling**: Tailwind CSS
- **Admin Panel**: Filament v3
- **Authentication**: Laravel Breeze
- **Media Player**: React Player
- **Build Tool**: Vite

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and NPM
- MySQL 5.7+ or MariaDB 10.3+
- cPanel hosting (for deployment)

## 🔧 Installation

### 1. Clone or Create Project

```bash
composer create-project laravel/laravel church-website "11.*"
cd church-website
```

### 2. Install Laravel Breeze with React

```bash
composer require laravel/breeze --dev
php artisan breeze:install react
```

### 3. Install Dependencies

```bash
composer install
composer require spatie/laravel-permission
npm install
npm install react-player
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=church_website
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Database Setup

```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder  # Seed roles and permissions
```

### 6. Install Filament Admin Panel

```bash
composer require filament/filament:"^3.2"
php artisan filament:install --panels
```

### 7. Create Admin User

```bash
bash create-admin.sh
```

This will:
- Prompt for name, email, and password
- Create user with admin role
- Assign all permissions

### 8. Build Assets

```bash
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit:
- **Public Site**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

## 👥 User Management

This application includes a comprehensive role-based access control system:

### Default Roles
- **Admin**: Full access to all features including user management
- **Editor**: Can manage content but not users or roles
- **User**: Read-only access to content

### Quick Start
1. Create admin user: `bash create-admin.sh`
2. Login to admin panel: `/admin`
3. Navigate to **User Management** to manage users, roles, and permissions

### Documentation
- **[USER_MANAGEMENT.md](USER_MANAGEMENT.md)**: Complete guide for managing users and roles
- **[ROLES_QUICK_REFERENCE.md](ROLES_QUICK_REFERENCE.md)**: Quick reference for roles and permissions
- **[SETUP_SUMMARY.md](SETUP_SUMMARY.md)**: Setup summary and quick start guide

### Common Tasks
```bash
# Create admin user
bash create-admin.sh

# Update user password
bash create-admin.sh --update

# List all users
bash create-admin.sh --list

# Clear permission cache
php artisan permission:cache-reset
```

## 📁 Project Structure

```
church-website/
├── app/
│   ├── Filament/
│   │   └── Resources/           # Admin panel resources
│   ├── Http/
│   │   └── Controllers/         # Laravel controllers
│   └── Models/                  # Eloquent models (Sermon, Event, BlogPost)
├── database/
│   ├── migrations/              # Database schema
│   ├── factories/               # Model factories for testing
│   └── seeders/                 # Database seeders
├── public/
│   ├── build/                   # Compiled frontend assets
│   └── index.php                # Application entry point
├── resources/
│   ├── js/
│   │   ├── Components/          # React components
│   │   │   ├── Layout.jsx       # Main layout with navbar/footer
│   │   │   ├── SermonCard.jsx   # Sermon display component
│   │   │   └── EventCard.jsx    # Event display component
│   │   └── Pages/
│   │       └── Welcome.jsx      # Homepage
│   └── views/
│       └── app.blade.php        # Main Blade template
├── routes/
│   └── web.php                  # Application routes
├── .env                         # Environment configuration
├── composer.json                # PHP dependencies
├── package.json                 # Node dependencies
├── vite.config.js              # Vite configuration
├── tailwind.config.js          # Tailwind CSS configuration
├── DEPLOYMENT_GUIDE.md         # Detailed deployment instructions
└── README.md                    # This file
```

## 🗄️ Database Schema

### Sermons Table
- `id`: Primary key
- `title`: Sermon title
- `slug`: URL-friendly slug (auto-generated)
- `speaker_name`: Name of the speaker
- `date_preached`: Date the sermon was delivered
- `youtube_url`: YouTube video URL (nullable)
- `audio_url`: Audio file URL (nullable)
- `description`: Sermon description (nullable)
- `timestamps`: Created/updated timestamps

### Events Table
- `id`: Primary key
- `title`: Event title
- `slug`: URL-friendly slug (auto-generated)
- `start_datetime`: Event start date and time
- `end_datetime`: Event end date and time (nullable)
- `location`: Event location (nullable)
- `description`: Event description
- `timestamps`: Created/updated timestamps

### Blog Posts Table
- `id`: Primary key
- `title`: Post title
- `slug`: URL-friendly slug (auto-generated)
- `author_id`: Foreign key to users table
- `content`: Post content (long text)
- `published_at`: Publication date (nullable for drafts)
- `timestamps`: Created/updated timestamps

## 🎨 Customization

### Update Church Name and Branding

1. **Layout Component** (`resources/js/Components/Layout.jsx`):
   - Update "Church Name" text
   - Modify navigation links
   - Update footer content and contact information

2. **Homepage** (`resources/js/Pages/Welcome.jsx`):
   - Customize hero section text
   - Update call-to-action messages

3. **Environment** (`.env`):
   ```env
   APP_NAME="Your Church Name"
   ```

### Styling

All styling uses Tailwind CSS. Modify:
- `tailwind.config.js`: Theme colors, fonts, etc.
- Component files: Individual component styling

### Admin Panel

Customize Filament resources in `app/Filament/Resources/`:
- Form fields and validation
- Table columns and filters
- Navigation icons and grouping

## 🚀 Deployment to cPanel

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for detailed deployment instructions.

### Quick Deployment Steps:

1. **Build for production**:
   ```bash
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Upload files** to cPanel:
   - Upload entire project to `/home/username/church-website/`
   - Copy `public/*` contents to `public_html/`

3. **Update `public_html/index.php`**:
   ```php
   require __DIR__.'/../church-website/vendor/autoload.php';
   $app = require_once __DIR__.'/../church-website/bootstrap/app.php';
   ```

4. **Configure `.env`** with production settings

5. **Run migrations**:
   ```bash
   php artisan migrate --force
   ```

## 🔐 Security

- Set `APP_DEBUG=false` in production
- Use strong passwords for admin users
- Keep dependencies updated: `composer update`
- Enable HTTPS/SSL certificate
- Never commit `.env` file to version control
- **Role-Based Access Control**: Only admins can manage users and roles
- **Permission System**: Granular control over what users can do
- **Password Hashing**: All passwords are securely hashed
- **Email Verification**: Optional email verification for new users

## 🧪 Testing

### Create Test Data

```bash
php artisan db:seed
```

This creates:
- 3 users
- 10 sermons
- 8 events
- 15 blog posts

### Access Admin Panel

1. Create admin user: `php artisan make:filament-user`
2. Visit: `http://localhost:8000/admin`
3. Login with created credentials

## 📝 Development Commands

### Laravel Commands
```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# List all routes
php artisan route:list

# Create new controller
php artisan make:controller ControllerName

# Create new model with migration
php artisan make:model ModelName -m
```

### NPM Commands
```bash
# Development with hot reload
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

## 🐛 Troubleshooting

### Assets Not Loading
```bash
npm run build
php artisan config:clear
```

### Database Connection Error
- Check `.env` database credentials
- Ensure database exists
- Verify user has proper privileges

### 500 Error
- Check `storage/logs/laravel.log`
- Ensure proper file permissions: `chmod -R 755 storage bootstrap/cache`
- Clear caches: `php artisan optimize:clear`

### Admin Panel Not Accessible
```bash
php artisan config:clear
php artisan route:clear
composer dump-autoload
```

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Filament Documentation](https://filamentphp.com/docs/3.x)
- [Inertia.js Documentation](https://inertiajs.com)
- [React Documentation](https://react.dev)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [React Player Documentation](https://github.com/cookpete/react-player)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -am 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Support

For issues and questions:
- Check the [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- Review Laravel documentation
- Check Filament documentation
- Open an issue on GitHub

---

**Built with ❤️ for churches worldwide**

**Version**: 1.0.0  
**Last Updated**: April 19, 2026  
**Laravel**: 11.x  
**PHP**: 8.2+  
**Node**: 18+
