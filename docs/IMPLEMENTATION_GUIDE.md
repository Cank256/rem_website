# Implementation Guide for REM Website

## Overview
This guide covers implementing YouTube live streaming, gallery management, and connecting events/sermons to Filament admin.

---

## 1. YouTube Live Streaming Integration

### How YouTube Live Streaming Works

When you go live on YouTube, you can embed the live stream on your website using the YouTube embed code.

### Implementation Steps:

#### Option A: Manual Update (Simple)
1. When you go live on YouTube, get your live stream URL
2. Extract the video ID from the URL
   - Example: `https://youtube.com/watch?v=ABC123XYZ`
   - Video ID: `ABC123XYZ`
3. Update the Live.jsx file with the video ID

**File to update:** `resources/js/Pages/Live.jsx`

Replace the placeholder div with:
```jsx
<div className="aspect-video bg-gray-800">
    <iframe
        width="100%"
        height="100%"
        src="https://www.youtube.com/embed/YOUR_VIDEO_ID?autoplay=1"
        title="Live Stream"
        frameBorder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowFullScreen
    ></iframe>
</div>
```

#### Option B: Dynamic Update (Recommended)
Create a settings table in Filament to store the YouTube live stream URL.

**Steps:**
1. Create a Settings model and migration:
```bash
php artisan make:model Setting -m
```

2. In the migration file, add:
```php
$table->string('key')->unique();
$table->text('value')->nullable();
```

3. Create a Filament resource for Settings
4. Add a setting with key `youtube_live_url`
5. Update Live.jsx to fetch this setting from the backend

#### Option C: YouTube API (Advanced)
Use YouTube Data API to automatically detect when you're live.

**Requirements:**
- YouTube Data API key
- Channel ID
- Backend endpoint to check live status

---

## 2. Gallery Management with Filament

### Database Structure Created

**Tables:**
1. `galleries` - Gallery categories
   - id, name, description, slug, is_active, sort_order

2. `gallery_images` - Images in galleries
   - id, gallery_id, title, description, image_path, sort_order

### Next Steps:

#### Step 1: Update Models

**File:** `app/Models/Gallery.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order');
    }
}
```

**File:** `app/Models/GalleryImage.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'gallery_id',
        'title',
        'description',
        'image_path',
        'sort_order',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}
```

#### Step 2: Create Filament Resources

```bash
php artisan make:filament-resource Gallery --generate
php artisan make:filament-resource GalleryImage --generate
```

#### Step 3: Run Migrations

```bash
php artisan migrate
```

#### Step 4: Update Gallery Page

The Gallery page needs to fetch galleries from the database instead of using hardcoded data.

**Create Controller:**
```bash
php artisan make:controller GalleryController
```

**Update routes/web.php:**
```php
use App\Http\Controllers\GalleryController;

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
```

---

## 3. Events from Filament

### Current Status
Events model and Filament resource already exist!

### Update Events Page

**File:** `resources/js/Pages/Events.jsx`

The page needs to receive events data from the controller.

**Update HomeController or create EventsController:**
```php
use App\Models\Event;

public function events()
{
    $upcomingEvents = Event::upcoming()
        ->where('is_published', true)
        ->orderBy('start_date')
        ->get();
        
    return Inertia::render('Events', [
        'upcomingEvents' => $upcomingEvents,
    ]);
}
```

**Update routes/web.php:**
```php
Route::get('/events', [HomeController::class, 'events'])->name('events');
```

---

## 4. Sermons from Filament

### Current Status
Sermons model and Filament resource already exist!

### Update Sermons Page

**File:** `resources/js/Pages/Sermons.jsx`

**Create SermonsController:**
```bash
php artisan make:controller SermonsController
```

**Controller code:**
```php
use App\Models\Sermon;

public function index()
{
    $sermons = Sermon::where('is_published', true)
        ->orderBy('date_preached', 'desc')
        ->paginate(10);
        
    return Inertia::render('Sermons', [
        'sermons' => $sermons,
    ]);
}
```

**Update routes/web.php:**
```php
use App\Http\Controllers\SermonsController;

Route::get('/sermons', [SermonsController::class, 'index'])->name('sermons');
```

---

## 5. Quick Implementation Commands

Run these commands in order:

```bash
# 1. Run migrations
cd church-website
php artisan migrate

# 2. Create Filament resources for Gallery
php artisan make:filament-resource Gallery --generate

# 3. Create Filament resource for GalleryImage  
php artisan make:filament-resource GalleryImage --generate

# 4. Create controllers
php artisan make:controller GalleryController
php artisan make:controller SermonsController
php artisan make:controller EventsController

# 5. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 6. Rebuild frontend
npm run build
```

---

## 6. Filament Admin Access

### Access the Admin Panel:
```
URL: http://your-domain.com/admin
```

### Create Admin User (if needed):
```bash
php artisan make:filament-user
```

### Managing Content:

1. **Galleries:**
   - Go to Admin > Galleries
   - Create new gallery (e.g., "Sunday Services", "Youth Events")
   - Add images to each gallery

2. **Events:**
   - Go to Admin > Events
   - Create/Edit events
   - Set dates, times, locations
   - Mark as published

3. **Sermons:**
   - Go to Admin > Sermons
   - Upload sermon details
   - Add video/audio links
   - Mark as published

---

## 7. YouTube Live Stream - Detailed Guide

### Method 1: Using YouTube Channel Live URL

**Always-working embed URL:**
```
https://www.youtube.com/embed/live_stream?channel=YOUR_CHANNEL_ID
```

This URL automatically shows your live stream when you're live, or shows "offline" when you're not.

**To get your Channel ID:**
1. Go to YouTube Studio
2. Click Settings > Channel > Advanced settings
3. Copy your Channel ID

**Update Live.jsx:**
```jsx
<iframe
    width="100%"
    height="100%"
    src="https://www.youtube.com/embed/live_stream?channel=YOUR_CHANNEL_ID&autoplay=1"
    title="Live Stream"
    frameBorder="0"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
    allowFullScreen
></iframe>
```

### Method 2: Using Specific Video ID

When you schedule a live stream, YouTube gives you a video ID. Use that:

```jsx
<iframe
    width="100%"
    height="100%"
    src="https://www.youtube.com/embed/VIDEO_ID?autoplay=1"
    title="Live Stream"
    frameBorder="0"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
    allowFullScreen
></iframe>
```

---

## 8. Testing Checklist

- [ ] Migrations run successfully
- [ ] Gallery resource appears in Filament admin
- [ ] Can create galleries in admin
- [ ] Can upload images to galleries
- [ ] Gallery page shows galleries from database
- [ ] Events page shows events from database
- [ ] Sermons page shows sermons from database
- [ ] YouTube live stream works when live
- [ ] Service times display correctly
- [ ] Leadership section shows Bp. Dr. John Mark Nuwagaba

---

## 9. Troubleshooting

### Issue: Migrations fail
**Solution:** Check database connection in `.env` file

### Issue: Filament resources don't appear
**Solution:** Clear cache with `php artisan cache:clear`

### Issue: Images don't upload
**Solution:** 
1. Check storage is linked: `php artisan storage:link`
2. Check folder permissions: `chmod -R 775 storage`

### Issue: YouTube embed doesn't work
**Solution:**
1. Verify Channel ID is correct
2. Check YouTube privacy settings (must be public)
3. Ensure embed is allowed in YouTube settings

---

## 10. Next Steps

1. Run the migrations
2. Create Filament resources
3. Add sample data through admin
4. Test each page
5. Update YouTube channel ID in Live page
6. Add actual mobile money numbers in Give page
7. Upload Bp. Dr. John Mark Nuwagaba's photo

---

## Support Files Created

- Migrations: `database/migrations/2026_04_20_*_create_galleries_table.php`
- Migrations: `database/migrations/2026_04_20_*_create_gallery_images_table.php`
- Models: `app/Models/Gallery.php`
- Models: `app/Models/GalleryImage.php`

---

**Last Updated:** April 20, 2026
**Status:** Ready for implementation
