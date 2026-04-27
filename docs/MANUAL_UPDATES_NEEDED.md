# Manual Updates Needed

These are the only things you need to update manually. Everything else is done!

---

## 1. YouTube Live Stream Channel ID

**File:** `resources/js/Pages/Live.jsx`  
**Line:** ~30

**Current Code:**
```jsx
<div className="aspect-video bg-gray-800 flex items-center justify-center">
    <div className="text-center text-white p-8">
        <svg className="w-24 h-24 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
        <h2 className="text-2xl font-bold mb-2">Stream Currently Offline</h2>
        <p className="text-gray-400 mb-6">
            We're not currently live. Check back during our service times or watch previous sermons below.
        </p>
        <div className="inline-flex items-center px-4 py-2 bg-red-600 rounded-full">
            <span className="w-3 h-3 bg-white rounded-full mr-2 animate-pulse"></span>
            <span className="text-sm font-medium">Offline</span>
        </div>
    </div>
</div>
```

**Replace With:**
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

**How to Get Your Channel ID:**
1. Go to https://studio.youtube.com
2. Click Settings (bottom left)
3. Click Channel > Advanced settings
4. Copy your Channel ID (looks like: UCxxxxxxxxxxxxxxxxxx)
5. Replace `YOUR_CHANNEL_ID` with your actual ID

**After updating:**
```bash
cd church-website
npm run build
```

---

## 2. Mobile Money Numbers

**File:** `resources/js/Pages/Give.jsx`  
**Lines:** ~120 and ~140

**MTN Mobile Money (Line ~120):**
```jsx
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0772 XXX XXX
</p>
```

**Replace with your actual MTN number:**
```jsx
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0772 123 456
</p>
```

**Airtel Money (Line ~140):**
```jsx
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0752 XXX XXX
</p>
```

**Replace with your actual Airtel number:**
```jsx
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0752 123 456
</p>
```

**After updating:**
```bash
cd church-website
npm run build
```

---

## 3. Bishop's Photo

**Option A: Use the default path**

1. Place the bishop's photo in: `public/images/leadership/bishop.jpg`
2. Make sure it's named exactly `bishop.jpg`
3. Recommended size: 400x400px (square)

**Option B: Use a different path**

If you want to use a different filename or location:

**File:** `resources/js/Pages/About.jsx`  
**Line:** ~150

**Current Code:**
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

Or use your custom path:
```jsx
<img 
    src="/images/your-custom-path.jpg" 
    alt="Bp. Dr. John Mark Nuwagaba"
    className="w-48 h-48 rounded-full mx-auto mb-6 object-cover"
/>
```

**After updating:**
```bash
cd church-website
npm run build
```

---

## Summary

That's it! Only 3 things to update:

1. ✏️ YouTube Channel ID in `Live.jsx`
2. ✏️ Mobile Money numbers in `Give.jsx`
3. ✏️ Bishop's photo in `public/images/leadership/`

After making these changes, run:
```bash
cd church-website
npm run build
```

Everything else is working and pulling from the database! 🎉
