# Quick Start: YouTube to Sermons Sync

## 🚀 Get Started in 3 Steps

### Step 1: Get Your YouTube Credentials (5 minutes)

#### A. Get YouTube API Key
1. Visit: https://console.cloud.google.com/
2. Create/select a project
3. Enable "YouTube Data API v3"
4. Create credentials → API Key
5. Copy the API key

#### B. Get YouTube Channel ID
**Method 1 - From YouTube Studio:**
1. Go to YouTube Studio
2. Settings → Channel → Advanced settings
3. Copy your Channel ID

**Method 2 - From Channel URL:**
- If your URL is `youtube.com/channel/UCxxxxx`, the ID is `UCxxxxx`
- If your URL is `youtube.com/@YourName`, use [this tool](https://commentpicker.com/youtube-channel-id.php) to get the ID

### Step 2: Configure in Admin Panel (2 minutes)

1. Log in to your church website admin panel
2. Go to **"Live Stream"** in the sidebar
3. Click on the existing live stream record (or create one)
4. Fill in:
   - **YouTube Channel ID**: Paste your channel ID
   - **YouTube API Key**: Paste your API key
5. Click **Save**

### Step 3: Test Your Connection (Optional but Recommended)

Before syncing, test that everything is configured correctly:

```bash
php artisan youtube:test-connection
```

This will show you:
- ✅ If your API key and Channel ID are valid
- 📺 A preview of your 5 most recent live streams
- ⚠️ Any configuration issues

### Step 4: Sync Your Sermons (1 minute)

1. Go to **"Sermons"** in the admin sidebar
2. Look for the **"Sync from YouTube"** button (top right, green button with refresh icon)
3. Click it
4. Confirm the sync
5. Wait for the success notification
6. Done! Your previous live streams are now sermons 🎉

---

## 📋 What Happens During Sync?

The system will:
- ✅ Find all previous **live streams** on your channel (not regular videos)
- ✅ Import them as sermons with:
  - Video title
  - Description
  - YouTube URL
  - Date (when the stream happened)
- ✅ Skip any sermons that already exist
- ✅ Show you how many were imported

---

## 🔧 Alternative: Command Line Method

If you prefer using the terminal:

```bash
# Navigate to your project
cd /path/to/church-website

# Run the sync command
php artisan youtube:sync-sermons

# Optional: Limit to 20 most recent streams
php artisan youtube:sync-sermons --max-results=20

# Optional: Set default speaker name
php artisan youtube:sync-sermons --speaker="Pastor John Smith"
```

---

## ⚠️ Troubleshooting

### "Configuration Missing" Error
**Problem**: YouTube credentials not set up  
**Solution**: Complete Step 2 above

### "No Live Streams Found"
**Problem**: No live streams on your channel, or they're private  
**Solution**: 
- Make sure you have previous live streams (not just regular videos)
- Ensure streams are Public or Unlisted (not Private)

### API Quota Exceeded
**Problem**: Too many API calls in one day  
**Solution**: 
- Wait 24 hours for quota to reset
- YouTube gives 10,000 units/day (plenty for normal use)

---

## 📝 After Import

You can edit any imported sermon to:
- Change the speaker name (defaults to "Pastor")
- Update the title or description
- Add an audio URL
- Delete sermons you don't want

---

## 🔄 Re-running the Sync

You can run the sync as many times as you want:
- It will only import **new** live streams
- Existing sermons won't be duplicated
- Perfect for keeping your sermons up-to-date

---

## 💡 Pro Tips

1. **Run monthly**: Sync once a month to catch new live streams
2. **Edit after import**: Review and update speaker names, descriptions, etc.
3. **Set up scheduling**: Ask your developer to set up automatic daily syncs
4. **Keep API key secure**: Never share your API key publicly

---

## 📚 Need More Help?

See the full guide: `YOUTUBE_SYNC_GUIDE.md`

---

**That's it! You're ready to automatically import your YouTube live streams as sermons.** 🎊
