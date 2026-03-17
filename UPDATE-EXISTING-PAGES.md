# How to Add Banners to Your Existing Pages

Your existing pages at:
- https://homesforexpats.nl/properties-for-sale/
- https://homesforexpats.nl/properties-for-rent/
- https://homesforexpats.nl/all-properties/

Don't have the banner yet because they were created before the plugin was installed.

---

## 🚀 Quick Fix (Automated - RECOMMENDED)

### Option 1: Use the Update Tool

1. **Go to WordPress Admin**
2. Navigate to: **Properties > Update Pages**
3. You'll see a list of your existing pages
4. Click **"Update All Pages Now"** button
5. Done! All pages will have banners

**What it does:**
- ✅ Adds banner image to all pages
- ✅ Sets the correct page template
- ✅ Keeps all existing content
- ✅ Takes 5 seconds

---

## 🔧 Manual Fix (If you prefer)

### Option 2: Update Each Page Manually

For each page (Properties for Sale, Properties for Rent, All Properties):

1. **Go to:** Pages > All Pages
2. **Edit** the page
3. **In Page Attributes** (right sidebar):
   - Template: Select **"Properties Page with Banner"**
4. **Scroll down** to Custom Fields section
   - If you don't see it, click "Screen Options" at the top and enable "Custom Fields"
5. **Click "Add New Custom Field"**
   - Name: `_hfe_banner_image`
   - Value: `https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg`
6. **Click "Add Custom Field"**
7. **Click "Update"** to save

Repeat for each page.

---

## 🎯 What You'll See After Update

### Before:
```
[Properties for Sale Page]
↓
[Property Cards]
```

### After:
```
┌─────────────────────────────┐
│   [Banner Image]            │
│   Home / Properties for Sale│
│   PROPERTIES FOR SALE       │
└─────────────────────────────┘
↓
[Property Cards]
```

---

## ✅ Verification

After updating, visit your pages:
1. https://homesforexpats.nl/properties-for-sale/
2. https://homesforexpats.nl/properties-for-rent/
3. https://homesforexpats.nl/all-properties/

You should see:
- ✓ Banner image at the top
- ✓ Breadcrumb navigation (Home / Page Name)
- ✓ Page title overlaid on banner
- ✓ Your property carousel/grid below

---

## 🆘 Troubleshooting

### Banner Not Showing?

**Check 1: Template**
- Edit page
- Look at "Page Attributes" > "Template"
- Should be: "Properties Page with Banner"
- If not, select it and update

**Check 2: Custom Field**
- Edit page
- Scroll to "Custom Fields"
- Look for `_hfe_banner_image`
- Should have value: `https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg`

**Check 3: Template File Exists**
- Make sure plugin is activated
- Template file should be at: `wp-content/plugins/hfe-properties/templates/page-properties.php`

### Still Not Working?

1. **Clear Cache**
   - Clear browser cache
   - If using caching plugin, clear that too

2. **Check Theme Compatibility**
   - Some themes override page templates
   - Try switching to a default WordPress theme temporarily

3. **Re-save Permalinks**
   - Go to Settings > Permalinks
   - Click "Save Changes" (don't change anything)

---

## 📝 Quick Reference

### Banner Image URL:
```
https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg
```

### Template Name:
```
Properties Page with Banner
```

### Custom Field:
```
Name:  _hfe_banner_image
Value: https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg
```

---

## 🎨 After Setup

Once banners are added, all your property pages will have:
- ✅ Professional banner header
- ✅ Breadcrumb navigation
- ✅ Consistent branding
- ✅ Responsive design
- ✅ SEO-friendly structure

---

## 💡 Pro Tip

**Use the Automated Tool!**

It's faster, safer, and guaranteed to work. Just:
1. Go to Properties > Update Pages
2. Click one button
3. Done!

Much easier than updating each page manually! 🚀
