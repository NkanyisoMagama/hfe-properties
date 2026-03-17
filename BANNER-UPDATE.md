# Banner & Page Updates - Summary

## 🎯 What Was Added

### New Page Template with Banner
Created a beautiful banner/breadcrumb section for property pages that displays:
- Banner background image (from https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg)
- Page title overlaid on banner
- Breadcrumb navigation (Home / Current Page)
- Fully responsive design

---

## 📄 Auto-Generated Pages (Updated)

The plugin now creates **3 pages** on activation:

### 1. Properties for Sale
- **URL:** `/properties-for-sale/`
- **Shortcode:** `[hfe_properties_carousel status="sale" limit="6"]`
- **Shows:** Available properties for sale
- **Banner:** ✓ Automatic

### 2. Sold Properties ⭐ NEW
- **URL:** `/sold-properties/`
- **Shortcode:** `[hfe_properties_grid status="sale" availability="sold" columns="3" limit="12"]`
- **Shows:** Recently sold properties
- **Banner:** ✓ Automatic

### 3. Properties for Rent
- **URL:** `/properties-for-rent/`
- **Shortcode:** `[hfe_properties_carousel status="rent" limit="6"]`
- **Shows:** Available properties for rent
- **Banner:** ✓ Automatic

---

## 🎨 Banner Features

### Visual Design
- Full-width background image
- Dark overlay (50% opacity) for text readability
- White text with text shadows
- Centered content
- Minimum height: 300px (desktop), 200px (mobile)

### Breadcrumb Navigation
- Home link → Current page
- Hover effects on links
- Primary color accent on current page
- SEO friendly structure

### Responsive Design
- Desktop: 300px height, 48px title
- Mobile: 200px height, 32px title
- Adjusts padding and font sizes

---

## 🆕 New Shortcode Parameter

Both shortcodes now support **availability** filtering:

### Filter by Availability
```
[hfe_properties_carousel status="sale" availability="sold"]
[hfe_properties_grid status="sale" availability="pending"]
```

**Options:**
- `available` - Show only available properties
- `pending` - Show only pending properties
- `sold` - Show only sold/rented properties
- *(empty)* - Show all (default)

---

## 📁 New Files Created

1. **`templates/page-properties.php`**
   - Page template with banner
   - Displays breadcrumb + title over banner image
   - Renders page content below banner

2. **Updated CSS** in `assets/css/hfe-properties.css`
   - `.hfe-page-banner` - Banner container
   - `.hfe-banner-overlay` - Dark overlay
   - `.hfe-breadcrumb` - Breadcrumb navigation
   - `.hfe-page-title` - Page title styling
   - Responsive media queries

3. **Updated Installer** in `includes/installer.php`
   - Creates 3 pages instead of 3
   - Sets banner image meta
   - Assigns page template automatically

---

## 🎯 How It Works

### On Plugin Activation:

1. **Creates Pages**
   - Generates 3 pages with shortcodes

2. **Sets Page Template**
   - Assigns `templates/page-properties.php` to each page

3. **Sets Banner Image**
   - Saves banner URL in page meta: `_hfe_banner_image`

4. **Page Template Loads**
   - Reads banner image from meta
   - Falls back to default if not set
   - Displays banner with breadcrumb
   - Shows page content below

---

## 🎨 Customization Options

### Change Banner Image Per Page

Edit any page and add custom field:
```
Field: _hfe_banner_image
Value: https://your-site.com/path/to/image.jpg
```

### Change Banner Height

Add to your theme CSS:
```css
.hfe-page-banner {
    min-height: 400px; /* Custom height */
}
```

### Change Overlay Opacity

```css
.hfe-banner-overlay {
    background: rgba(0, 0, 0, 0.6); /* Darker */
}
```

### Remove Breadcrumb

```css
.hfe-breadcrumb {
    display: none;
}
```

---

## 📊 Status vs Availability

### Clear Separation:

**Status** = Property Purpose
- For Sale
- For Rent

**Availability** = Current State
- Available (ready to buy/rent)
- Pending (offer submitted)
- Sold/Rented (deal completed)

### Examples:

1. **New Sale Listing**
   - Status: For Sale
   - Availability: Available

2. **Under Contract**
   - Status: For Sale
   - Availability: Pending

3. **Deal Closed**
   - Status: For Sale
   - Availability: Sold

---

## 🚀 Usage Examples

### Show Only Available Sales
```
[hfe_properties_carousel status="sale"]
```
(Default availability is empty = all available)

### Show Sold Properties
```
[hfe_properties_grid status="sale" availability="sold" columns="3"]
```

### Show Pending Rentals
```
[hfe_properties_carousel status="rent" availability="pending"]
```

### Show All Properties (Any Status/Availability)
```
[hfe_properties_grid status="all" columns="3"]
```

---

## ✅ What's Automatic

- ✓ Banner image set on all pages
- ✓ Page template assigned
- ✓ Breadcrumb navigation
- ✓ Responsive design
- ✓ Overlay styling
- ✓ Text shadows for readability

## 🎯 What User Needs to Do

- ✓ Just activate plugin!
- ✓ (Optional) Add pages to menu
- ✓ (Optional) Customize banner images per page

---

## 📱 Mobile Optimized

The banner automatically adjusts:
- Height: 300px → 200px
- Title: 48px → 32px
- Breadcrumb: 14px → 12px
- Padding: 80px → 50px

---

## 🎉 Result

Beautiful, professional property pages with:
- Eye-catching banner images
- Clear breadcrumb navigation
- Branded design matching Homes for Expats
- Zero manual configuration needed
- Full responsive support

**Upload → Activate → Done!** 🚀
