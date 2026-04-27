# Navbar Title and Mobile Money Updates

## Overview
Added "Rural Evangelical Ministries" title next to the logo in the navbar and replaced Text to Give with Mobile Money options (MTN and Airtel) on the Give page.

## Navbar Updates

### 1. **Church Title Added**
- **Location**: Right side of the logo in navbar
- **Text**: "Rural Evangelical Ministries" (two lines)
- **Visibility**: 
  - Hidden on mobile (< 768px) to save space
  - Visible on tablet and desktop (≥ 768px)
- **Styling**:
  - Font size: `text-lg` on tablet, `text-xl` on desktop
  - Font weight: Bold
  - Color: Dark gray (`text-gray-900`)
  - Line breaks for better readability

### 2. **Layout Structure**
```jsx
Logo + Title (flex with gap)
├── Logo Image (h-12 lg:h-16)
└── Title Text (hidden md:block)
    └── "Rural Evangelical"
    └── "Ministries"
```

### 3. **Responsive Behavior**
- **Mobile (<768px)**: Logo only
- **Tablet (768px-1023px)**: Logo + Title (text-lg)
- **Desktop (≥1024px)**: Logo + Title (text-xl)

## Give Page Updates

### **Replaced: Text to Give**
**Old Section:**
- Text to Give with phone number
- Generic SMS-based giving

### **New Section: Mobile Money**
Replaced with Uganda-specific mobile money options:

#### **1. MTN Mobile Money**
- **Design**: Yellow-themed card with MTN branding
- **Background**: Yellow-50 with yellow-400 border
- **Icon**: Yellow circle with "MTN" text
- **Information**:
  - Number: 0772 XXX XXX (placeholder)
  - Name: Rural Evangelical Ministries

#### **2. Airtel Money**
- **Design**: Red-themed card with Airtel branding
- **Background**: Red-50 with red-400 border
- **Icon**: Red circle with "Airtel" text
- **Information**:
  - Number: 0752 XXX XXX (placeholder)
  - Name: Rural Evangelical Ministries

### **Mobile Money Card Features**
- Clear visual distinction between providers
- Brand colors (MTN yellow, Airtel red)
- Easy-to-read account numbers
- Account name displayed
- Professional layout with icons

## Technical Implementation

### Files Modified

1. **resources/js/Components/Layout.jsx**
   - Added title div next to logo
   - Implemented responsive visibility
   - Added flex gap for spacing

2. **resources/js/Pages/Give.jsx**
   - Replaced Text to Give section
   - Added MTN Mobile Money card
   - Added Airtel Money card
   - Styled with brand colors

### CSS Classes Used

**Navbar Title:**
- `hidden md:block` - Responsive visibility
- `text-lg lg:text-xl` - Responsive font size
- `font-bold` - Bold weight
- `text-gray-900` - Dark text
- `leading-tight` - Tight line height
- `gap-3` - Spacing between logo and title

**Mobile Money Cards:**
- `bg-yellow-50 border-yellow-400` - MTN styling
- `bg-red-50 border-red-400` - Airtel styling
- `rounded-lg` - Rounded corners
- `border-2` - Prominent borders
- `p-4` - Internal padding

## Visual Design

### Navbar Title
```
[Logo] Rural Evangelical
       Ministries
```

### Mobile Money Section
```
┌─────────────────────────────────┐
│ 📱 Mobile Money                 │
│                                 │
│ ┌─ MTN Mobile Money ──────────┐│
│ │ 🟡 MTN                       ││
│ │ Number: 0772 XXX XXX         ││
│ │ Name: Rural Evangelical...   ││
│ └──────────────────────────────┘│
│                                 │
│ ┌─ Airtel Money ──────────────┐│
│ │ 🔴 Airtel                    ││
│ │ Number: 0752 XXX XXX         ││
│ │ Name: Rural Evangelical...   ││
│ └──────────────────────────────┘│
└─────────────────────────────────┘
```

## Benefits

### Navbar Title
✅ Immediate brand recognition
✅ Professional appearance
✅ Better use of navbar space
✅ Doesn't clutter mobile view
✅ Reinforces church identity

### Mobile Money
✅ Localized payment options for Uganda
✅ Familiar payment methods
✅ Clear visual distinction between providers
✅ Easy to copy account numbers
✅ Professional presentation
✅ Matches local giving habits

## Important Notes

### **Update Required:**
The mobile money numbers are currently placeholders (0772 XXX XXX and 0752 XXX XXX). 

**To activate:**
1. Open `resources/js/Pages/Give.jsx`
2. Replace placeholder numbers with actual mobile money numbers
3. Verify account names are correct
4. Test transactions before going live

### Example Update:
```jsx
// Replace this:
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0772 XXX XXX
</p>

// With actual number:
<p className="text-sm text-gray-700">
    <span className="font-semibold">Number:</span> 0772 123 456
</p>
```

## Responsive Behavior

### Desktop (≥1024px)
- Logo: 64px height
- Title: text-xl (20px), visible
- Mobile Money: Full cards side by side

### Tablet (768px-1023px)
- Logo: 48px height
- Title: text-lg (18px), visible
- Mobile Money: Full cards side by side

### Mobile (<768px)
- Logo: 48px height
- Title: Hidden
- Mobile Money: Stacked cards

## Accessibility

- ✅ Proper heading hierarchy
- ✅ Clear text contrast
- ✅ Readable font sizes
- ✅ Touch-friendly on mobile
- ✅ Screen reader friendly
- ✅ Semantic HTML structure

## Testing Checklist

- [x] Title appears next to logo on desktop
- [x] Title hidden on mobile
- [x] Title properly sized on tablet
- [x] Mobile Money section displays correctly
- [x] MTN card has yellow theme
- [x] Airtel card has red theme
- [x] Account numbers are readable
- [x] Layout responsive on all screens
- [x] No text overflow issues
- [x] Brand colors accurate

## Future Enhancements

### Optional Improvements:

1. **Navbar Title**
   - Add animation on page load
   - Make title clickable (link to home)
   - Add tagline below title
   - Implement color change on scroll

2. **Mobile Money**
   - Add QR codes for each provider
   - Include step-by-step instructions
   - Add "Copy Number" button
   - Show transaction confirmation
   - Add mobile money logos/images
   - Include USSD codes for quick access

3. **Additional Payment Methods**
   - Add bank transfer details
   - Include international options
   - Add cryptocurrency options
   - Integrate payment APIs

## Security Considerations

⚠️ **Important:**
- Verify mobile money numbers before publishing
- Test small transactions first
- Monitor for fraudulent activity
- Keep account details updated
- Consider adding verification steps
- Document all transactions

## Support

If mobile money payments aren't working:
1. Verify account numbers are correct
2. Check account is active and verified
3. Ensure account name matches exactly
4. Test with small amount first
5. Contact mobile money provider if issues persist

---

**Implementation Date**: April 20, 2026
**Status**: ✅ Complete (Numbers need updating)
**Version**: 1.0
