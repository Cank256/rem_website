# Quick Reference Guide

## New Features Summary

### 1. Sermons Page
- **Shows:** 5 most recent sermons initially
- **Load More:** Click button to load 5 more at a time
- **URL:** `/sermons`

### 2. Events Page
- **Shows:** 5 upcoming/ongoing events initially
- **Filters:** Automatically hides past events
- **Load More:** Click button to load 5 more at a time
- **Badge:** "Happening Now" for ongoing events
- **URL:** `/events`

### 3. Event Detail Page
- **Shows:** Full event information with image
- **Access:** Click "Learn More" on any event
- **Features:** Date, time, location, description, action buttons
- **URL:** `/events/{event-slug}`

### 4. Ministries Page
- **New Section:** "Reaching Rural Communities"
- **Stats:** 250+ churches, 50,000+ lives, 100+ districts
- **Location:** Between ministry cards and "Ready to Get Involved?"
- **URL:** `/ministries`

### 5. Multiple Image Upload
- **Where:** Admin > Gallery Images > New
- **Limit:** Up to 20 images at once
- **Features:** Drag to reorder, image editor, auto-create records

---

## Admin Panel Quick Guide

### Upload Event Image:
1. Go to `/admin` > Events
2. Create or edit an event
3. Scroll to "Event Image" field
4. Click to upload or drag image
5. Use image editor if needed
6. Save

### Upload Multiple Gallery Images:
1. Go to `/admin` > Gallery Images
2. Click "New Gallery Image"
3. Select gallery
4. Click image upload area
5. Select multiple images (Ctrl/Cmd + Click)
6. Drag to reorder if needed
7. Add title/description (optional, applies to first image only)
8. Save - all images will be created as separate records

### Filter Events:
1. Go to `/admin` > Events
2. Click filter icon
3. Toggle "Upcoming Only" (on by default)
4. See only future events

---

## Testing Quick Steps

### Test Sermons Pagination:
```
1. Add 10 sermons in admin
2. Visit /sermons
3. See 5 sermons
4. Click "Load More"
5. See 5 more
```

### Test Events Pagination:
```
1. Add 10 future events in admin
2. Visit /events
3. See 5 events
4. Click "Load More"
5. See 5 more
```

### Test Event Detail:
```
1. Add event with image in admin
2. Visit /events
3. Click "Learn More"
4. See full event page with image
```

### Test Multiple Upload:
```
1. Go to /admin > Gallery Images
2. Click "New Gallery Image"
3. Select 5 images at once
4. Upload
5. Check 5 records created
```

---

## File Locations

### Frontend Pages:
- Sermons: `resources/js/Pages/Sermons.jsx`
- Events: `resources/js/Pages/Events.jsx`
- Event Detail: `resources/js/Pages/EventDetail.jsx`
- Ministries: `resources/js/Pages/Ministries.jsx`

### Controllers:
- Sermons: `app/Http/Controllers/SermonController.php`
- Events: `app/Http/Controllers/EventController.php`

### Models:
- Event: `app/Models/Event.php`

### Filament:
- Events: `app/Filament/Resources/EventResource.php`
- Gallery Images: `app/Filament/Resources/GalleryImageResource.php`

---

## Common Commands

### Rebuild Frontend:
```bash
cd church-website
npm run build
```

### Clear Cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Start Server:
```bash
php artisan serve
```

### Check Routes:
```bash
php artisan route:list
```

---

## URLs to Test

- `/sermons` - Sermons with pagination
- `/events` - Events with pagination
- `/events/your-event-slug` - Event detail page
- `/ministries` - Ministries with rural churches section
- `/gallery` - Gallery with images
- `/admin` - Admin panel

---

## What to Add in Admin

### For Testing Sermons:
- Add at least 10 sermons
- Include YouTube URLs
- Add speaker names
- Set dates

### For Testing Events:
- Add at least 10 future events
- Upload images for some events
- Set start and end dates/times
- Add locations

### For Testing Gallery:
- Create 2-3 galleries
- Upload 5-10 images at once
- Test with different galleries

---

## Troubleshooting

### Images Not Showing:
```bash
php artisan storage:link
```

### Changes Not Appearing:
```bash
npm run build
php artisan cache:clear
```

### Past Events Still Showing:
- Check event end_datetime is in the past
- Clear cache
- Refresh page

### Load More Not Working:
- Check you have more than 5 items
- Clear browser cache
- Check console for errors

---

## Key Features

✅ Pagination on sermons (5 at a time)
✅ Pagination on events (5 at a time)
✅ Past events filtered automatically
✅ Event detail pages with images
✅ Rural churches section on ministries
✅ Multiple image upload (up to 20)
✅ Ongoing event badges
✅ Load more buttons
✅ Smooth loading (no page refresh)

---

## Next Steps

1. **Add Content:**
   - Upload 10+ sermons
   - Create 10+ events with images
   - Upload gallery images

2. **Test Everything:**
   - Test pagination on sermons
   - Test pagination on events
   - Test event detail pages
   - Test multiple image upload

3. **Customize:**
   - Update rural churches statistics if needed
   - Add more event images
   - Customize event detail page

4. **Launch:**
   - Test on mobile devices
   - Test on different browsers
   - Share with team

---

**Everything is ready to use!** 🚀

For detailed information, see:
- `NEW_FEATURES_ADDED.md` - Complete feature documentation
- `SETUP_COMPLETE.md` - Initial setup guide
- `MANUAL_UPDATES_NEEDED.md` - Manual updates required
