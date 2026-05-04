# Analytics Quick Start Guide

## 🚀 What You Got

A complete analytics system with privacy compliance for your church website!

## ✅ Immediate Benefits

### For Administrators
- **See who visits your site** - Track page views, unique visitors, and session duration
- **Understand your audience** - Know what devices and browsers they use
- **Identify popular content** - See which pages get the most traffic
- **Monitor engagement** - Track how long people stay on each page

### For Visitors
- **Privacy respected** - Cookie consent required before tracking
- **Transparent** - Clear privacy policy and terms of use
- **Control** - Can decline tracking if desired

## 📍 Where to Find Things

### Admin Dashboard
1. Go to: `https://yoursite.com/admin`
2. Login with your admin account
3. Look for **"Analytics"** in the left sidebar
4. Click **"Page Views"** to see all data

### Public Pages
- Privacy Policy: `https://yoursite.com/privacy-policy`
- Terms of Use: `https://yoursite.com/terms-of-use`

## 🎯 What Gets Tracked

### Automatically Tracked (No Code Needed)
✅ Every page visit
✅ Device type (mobile/tablet/desktop)
✅ Browser and operating system
✅ Time spent on each page
✅ Referrer (where visitors came from)
✅ Session duration

### Can Be Tracked (With Code)
- Video plays
- Button clicks
- Form submissions
- Downloads
- Any custom event you want

## 🔍 Quick Demo

### Test It Out:
1. **Visit your website** (in incognito/private mode)
2. **Accept cookies** when the banner appears
3. **Browse a few pages**
4. **Go to admin panel** → Analytics → Page Views
5. **See your visits** in the table!

## 📊 Dashboard Widgets

When you open the Page Views page, you'll see:

### Widget 1: Analytics Overview
- 📈 Total page views today
- 👥 Unique visitors (last 7 days)
- 📊 Total page views (last 30 days)
- ⏱️ Average session duration

### Widget 2: Analytics Chart
- 📉 Line chart showing page views over time
- 🔽 Filter dropdown: Today, 7 days, 30 days, 90 days

### Widget 3: Page Views Table
- 📋 Detailed list of all page views
- 🔍 Search and filter options
- 📱 Device type badges
- 👤 User identification

## 🍪 Cookie Consent

### How It Works:
1. **First-time visitor** sees banner at bottom of page
2. **Two options:**
   - ✅ Accept - Tracking starts
   - ❌ Decline - No tracking
3. **Choice saved** in browser (30 days)
4. **Banner disappears** after choice made

### What Happens:
- **Accept** → Analytics tracking enabled
- **Decline** → No data collected

## 🔒 Privacy Compliance

### What We Did:
✅ Cookie consent banner (GDPR compliant)
✅ Privacy policy page (explains everything)
✅ Terms of use page (legal protection)
✅ No tracking without consent
✅ Anonymous session tracking
✅ Footer links for easy access

### What You Should Do:
1. **Review privacy policy** - Update contact info
2. **Review terms of use** - Update contact info
3. **Test cookie consent** - Make sure it works
4. **Inform your team** - Show them the analytics

## 📱 Example Use Cases

### Track Sermon Downloads
```javascript
// When someone downloads a sermon
trackEvent('sermon_download', 'content', {
    sermon_title: 'Sunday Service',
    format: 'mp3'
});
```

### Track Video Plays
```javascript
// When someone plays a video
trackEvent('video_play', 'engagement', {
    video_title: 'Welcome Message',
    duration: 300
});
```

### Track Donation Button Clicks
```javascript
// When someone clicks donate
trackEvent('donate_click', 'conversion', {
    button_location: 'header',
    page: 'home'
});
```

## 🛠️ Common Tasks

### View Today's Traffic
1. Go to Analytics → Page Views
2. Look at "Total Page Views (Today)" widget
3. Or filter table by today's date

### See Most Popular Pages
1. Go to Analytics → Page Views
2. Look at the table
3. Sort by "Path" column
4. Count occurrences manually (or use filters)

### Check Mobile vs Desktop
1. Go to Analytics → Page Views
2. Use "Device Type" filter
3. Select "Mobile" or "Desktop"
4. See filtered results

### Export Data
1. Go to Analytics → Page Views
2. Use table filters to narrow down
3. (Future: Add export button)

## 🎨 Customization

### Change Cookie Consent Text
Edit: `resources/js/Components/CookieConsent.jsx`

### Update Privacy Policy
Edit: `resources/js/Pages/PrivacyPolicy.jsx`

### Update Terms of Use
Edit: `resources/js/Pages/TermsOfUse.jsx`

### Add Custom Events
Use the `useAnalytics` hook in any React component

## 📈 Best Practices

### Do:
✅ Check analytics weekly
✅ Look for trends over time
✅ Identify popular content
✅ Monitor device types
✅ Track important events

### Don't:
❌ Track personal information without consent
❌ Share analytics data publicly
❌ Make decisions on small sample sizes
❌ Ignore privacy concerns
❌ Track sensitive user actions

## 🐛 Troubleshooting

### Analytics Not Showing?
1. **Check cookie consent** - Did you accept cookies?
2. **Check admin login** - Are you logged in?
3. **Check database** - Run `php artisan migrate`
4. **Clear cache** - Run `php artisan cache:clear`

### Cookie Banner Not Appearing?
1. **Check localStorage** - Clear browser data
2. **Check component** - Is CookieConsent imported in Layout?
3. **Check browser** - Try incognito mode

### No Data in Dashboard?
1. **Visit some pages** - Generate some traffic first
2. **Accept cookies** - Tracking won't work without consent
3. **Wait a moment** - Refresh the admin page
4. **Check filters** - Remove any active filters

## 📚 Documentation

- **Full Documentation**: `ANALYTICS_README.md`
- **Summary**: `ANALYTICS_SUMMARY.md`
- **This Guide**: `ANALYTICS_QUICK_START.md`

## 🎓 Learning Resources

### For Administrators:
- Learn to read analytics data
- Understand visitor behavior
- Make data-driven decisions

### For Developers:
- Read `ANALYTICS_README.md`
- Check `app/Services/AnalyticsService.php`
- Review `resources/js/hooks/useAnalytics.js`

## 🚦 Next Steps

1. ✅ **Test the system** - Visit your site and check analytics
2. ✅ **Update contact info** - In privacy policy and terms
3. ✅ **Train your team** - Show them how to use analytics
4. ✅ **Monitor regularly** - Check weekly for insights
5. ✅ **Add custom tracking** - Track important events

## 💡 Pro Tips

- **Check analytics on Monday** - See weekend traffic
- **Compare week-over-week** - Spot trends
- **Track sermon views** - See which topics resonate
- **Monitor event pages** - Before and after events
- **Use filters** - Narrow down to specific data

## 🎉 You're All Set!

Your church website now has professional-grade analytics with full privacy compliance. Start exploring your data and understanding your visitors better!

---

**Questions?** Check `ANALYTICS_README.md` for detailed documentation.

**Need Help?** Contact your development team.

**Want More?** Consider adding:
- Geographic tracking (country/city)
- Conversion funnels
- A/B testing
- Email reports
- Real-time dashboard
