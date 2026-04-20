# Quick Setup Guide - Rural Evangelical Ministries Website

## ✅ Already Completed

1. ✅ Service times updated on Live page
2. ✅ Leadership section updated with Bp. Dr. John Mark Nuwagaba
3. ✅ Gallery database structure created
4. ✅ Gallery models created
5. ✅ Frontend rebuilt

## 🚀 Next Steps (Run These Commands)

### Step 1: Run Database Migrations
```bash
cd church-website
php artisan migrate
```

### Step 2: Create Filament Resources for Gallery
```bash
php artisan make:filament-resource Gallery --generate
php artisan make:filament-resource GalleryImage --generate
```

### Step 3: Link Storage (for image uploads)
```bash
php artisan storage:link
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📝 Manual Updates Needed

### 1. YouTube Live Stream Setup

**File to update:** `resources/js/Pages/Live.jsx`

**Find this section** (around line 30):
```jsx
<div className="aspect-video bg-gray-800 flex items-center justify-center">
    <div className="text-center text-white p-8">
        ...
    </div>
</div>
```

**Replace with:**
```jsx
<div className="aspect-video bg-gray-800">
    <iframe
        width="100%"
        height="100%"
        src="https://www.youtube.com/embed/live_stream?channel=YOUR_CHANNEL_ID&autoplay=1"
        title="Live Stream"
        frameBorder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowFullScreen
    ></iframe>
</div>
```

**To get your YouTube Channel ID:**
1. Go to https://studio.youtube.com
2. Click Settings (bottom left)
3. Click Channel > Advanced settings
4. Copy your Channel ID
5. Replace `YOUR_CHANNEL_ID` in the code above

---

### 2. Update Mobile Money Numbers

**File to update:** `resources/js/Pages/Give.jsx`

**Find these lines** (around line 120-140):
```jsx
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0772 XXX XXX
</p>
```

**Replace with your actual numbers:**
```jsx
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0772 123 456
</p>
```

Do this for both MTN and Airtel sections.

---

### 3. Upload Bishop's Photo

1. Go to `/admin` (Filament admin panel)
2. Navigate to the About page management (you may need to create this)
3. Upload Bp. Dr. John Mark Nuwagaba's photo
4. Or manually place the photo in `public/images/leadership/bishop.jpg`

**Then update** `resources/js/Pages/About.jsx` (around line 150):
```jsx
<div className="w-48 h-48 bg-gray-300 rounded-full mx-auto mb-6"></div>
```

**Replace with:**
```jsx
<img 
    src="/images/leadership/bishop.jpg" 
    alt="Bp. Dr. John Mark Nuwagaba"
    className="w-48 h-48 rounded-full mx-auto mb-6 object-cover"
/>
```

---

## 🎨 Using Filament Admin

### Access Admin Panel
```
URL: http://your-domain.com/admin
```

### Create Admin User (if needed)
```bash
php artisan make:filament-user
```

Follow the prompts to create your admin account.

---

## 📸 Managing Galleries

### In Filament Admin:

1. **Create Galleries:**
   - Go to Admin > Galleries
   - Click "New Gallery"
   - Enter name (e.g., "Sunday Services", "Youth Events", "Outreach Programs")
   - Add description
   - Set as active
   - Save

2. **Add Images to Galleries:**
   - Go to Admin > Gallery Images
   - Click "New Gallery Image"
   - Select the gallery
   - Upload image
   - Add title and description
   - Set sort order
   - Save

### Gallery Categories Suggestions:
- Sunday Services
- Youth Ministry
- Women's Fellowship
- Men's Fellowship
- Community Outreach
- Special Events
- Worship Team
- Children's Ministry

---

## 📅 Managing Events

Events are already set up! Just use Filament:

1. Go to Admin > Events
2. Click "New Event"
3. Fill in:
   - Title
   - Description
   - Start date/time
   - End date/time
   - Location
   - Mark as published
4. Save

The Events page will automatically show published events.

---

## 🎤 Managing Sermons

Sermons are already set up! Just use Filament:

1. Go to Admin > Sermons
2. Click "New Sermon"
3. Fill in:
   - Title
   - Speaker
   - Date preached
   - Scripture reference
   - Description
   - Video/Audio URL (YouTube link)
   - Mark as published
4. Save

The Sermons page will automatically show published sermons.

---

## 🔧 Troubleshooting

### Issue: "Class 'Gallery' not found"
**Solution:**
```bash
composer dump-autoload
php artisan cache:clear
```

### Issue: Images won't upload
**Solution:**
```bash
php artisan storage:link
chmod -R 775 storage
chmod -R 775 public/storage
```

### Issue: Filament resources don't appear
**Solution:**
```bash
php artisan filament:upgrade
php artisan cache:clear
```

### Issue: YouTube embed shows error
**Solution:**
- Check your Channel ID is correct
- Ensure your YouTube channel is public
- Check YouTube privacy settings allow embedding

---

## 📋 Testing Checklist

After setup, test these:

- [ ] Can access /admin
- [ ] Can create galleries in admin
- [ ] Can upload images to galleries
- [ ] Gallery page shows galleries from database
- [ ] Can create events in admin
- [ ] Events page shows events
- [ ] Can create sermons in admin
- [ ] Sermons page shows sermons
- [ ] YouTube live stream works (when live)
- [ ] Service times show correctly on Live page
- [ ] Bishop's info shows on About page
- [ ] Mobile money numbers are correct

---

## 🎯 Priority Order

1. **First:** Run migrations and create Filament resources
2. **Second:** Update YouTube Channel ID
3. **Third:** Update mobile money numbers
4. **Fourth:** Upload bishop's photo
5. **Fifth:** Add content through Filament admin

---

## 📞 Need Help?

If you encounter issues:
1. Check the IMPLEMENTATION_GUIDE.md for detailed instructions
2. Check Laravel logs: `storage/logs/laravel.log`
3. Check browser console for JavaScript errors
4. Ensure all migrations ran successfully

---

## 🎉 After Setup

Once everything is working:
1. Add real content through Filament admin
2. Test all pages
3. Share the website with your team
4. Train staff on using Filament admin

---

**Last Updated:** April 20, 2026
**Status:** Ready to implement
