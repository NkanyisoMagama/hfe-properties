# Property Detail Page Banner - Complete Guide

## 🎉 New Feature Added!

Each property detail page now has a **customizable banner image** with breadcrumb navigation!

---

## ✨ What Was Added

### 1. Banner Meta Box in Admin
Located in the **sidebar** when editing a property:
- Upload custom banner image
- Preview current banner
- Remove button to reset to default
- Shows whether using default or custom banner

### 2. Banner on Single Property Page
- Full-width banner at the top
- Breadcrumb navigation (Home / Properties / Property Name)
- Property title overlaid on banner
- Dark overlay for text readability
- Fully responsive design

### 3. Quick Info Section
Below the banner, displays:
- Location with icon
- Property type with icon
- Status badge (For Sale/Rent)
- Price highlighted with icon

---

## 📸 Visual Layout

```
┌─────────────────────────────────────────────┐
│                                             │
│         [Banner Background Image]           │
│            [Dark Overlay]                   │
│                                             │
│   Home / Properties / Property Name         │ ← Breadcrumb
│                                             │
│     LUXURY APARTMENT IN AMSTERDAM           │ ← Title
│                                             │
└─────────────────────────────────────────────┘
┌─────────────────────────────────────────────┐
│  📍 Location    🏠 Type   ✓ Status  💰 Price│ ← Quick Info
└─────────────────────────────────────────────┘
         ↓
  [Property Stats Grid]
  [Description]
  [Features]
  [Gallery]
```

---

## 🎯 How to Use

### For Administrators

**Setting a Custom Banner:**

1. Edit any property
2. Find "Property Page Banner Image" meta box (sidebar)
3. Click "Upload Banner Image"
4. Select image from media library
5. Click "Use this image"
6. Save/Update property

**Removing Custom Banner:**

1. Edit property
2. Click "Remove" button in banner meta box
3. Reverts to default banner
4. Save/Update property

**Default Banner:**
- URL: `https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg`
- Used when no custom banner is set
- Can be changed in code

---

## 🎨 Banner Specifications

### Recommended Size
- **Width:** 1920px
- **Height:** 400-600px
- **Aspect Ratio:** 16:9 or wider
- **Format:** JPG, PNG
- **Max Size:** 2MB recommended

### Design Tips
- Use high-contrast images
- Darker images work better with white text
- Avoid busy backgrounds behind text area
- Center important elements

---

## 💾 Technical Details

### Database Storage

**Meta Keys:**
- `_hfe_banner_image_id` - WordPress attachment ID
- `_hfe_banner_image` - Full URL to banner image

### Default Fallback

If no custom banner is set:
```php
$banner_image = get_post_meta($property_id, '_hfe_banner_image', true);
if (!$banner_image) {
    $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';
}
```

---

## 🎨 Customization Options

### Change Default Banner URL

Edit `templates/single-property.php`:
```php
if (!$banner_image) {
    $banner_image = 'YOUR_NEW_DEFAULT_URL';
}
```

Also update in `includes/meta-boxes.php`:
```php
if (!$banner_url) {
    $banner_url = 'YOUR_NEW_DEFAULT_URL';
}
```

And in `assets/js/admin.js`:
```javascript
const defaultBanner = 'YOUR_NEW_DEFAULT_URL';
```

### Change Banner Height

Add to your theme CSS:
```css
.hfe-page-banner {
    min-height: 500px; /* Custom height */
}
```

### Change Overlay Darkness

```css
.hfe-banner-overlay {
    background: rgba(0, 0, 0, 0.7); /* Darker = higher number */
}
```

### Hide Breadcrumb

```css
.hfe-breadcrumb {
    display: none;
}
```

### Customize Quick Info Section

```css
.hfe-quick-info-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columns instead of auto */
}

.hfe-quick-info-item {
    background: #your-color;
    padding: 20px;
}
```

---

## 📱 Responsive Design

### Desktop (>1024px)
- Banner: 300px height
- Title: 48px
- Quick Info: 4 columns (auto-fit)

### Tablet (768px-1024px)
- Banner: 250px height
- Title: 40px
- Quick Info: 2 columns

### Mobile (<768px)
- Banner: 200px height
- Title: 32px
- Breadcrumb: 12px
- Quick Info: 1 column

---

## 🎯 Features

### Banner Section
✅ Full-width background image
✅ Dark overlay (50% opacity)
✅ Breadcrumb navigation
✅ Property title
✅ Responsive sizing
✅ Custom per property
✅ Default fallback

### Quick Info Section
✅ Location display
✅ Property type
✅ Status badge
✅ Price highlight
✅ Icon-based design
✅ Responsive grid

---

## 🔧 Admin Interface

### Banner Meta Box Features

**Upload Button**
- Opens WordPress media library
- Single image selection
- Updates preview immediately
- Shows in sidebar

**Preview Area**
- Displays current banner
- Full-width preview
- Updates on selection

**Remove Button**
- Only shows when custom banner exists
- Resets to default
- Confirms before removing

**Status Indicator**
- Shows if using default or custom
- Helpful description text
- Recommended size info

---

## 🎨 Breadcrumb Features

### Navigation Path
```
Home / Properties / [Property Title]
```

### Interactive Elements
- Home link → Homepage
- Properties link → Property archive
- Current page = Property title (not clickable)

### Styling
- White text on dark background
- Hover effect on links
- Primary color on current page
- Separator: "/"

---

## 📊 Quick Info Grid

### 4 Information Cards

**1. Location**
- 📍 Icon
- "Location: [City Name]"
- Gray background

**2. Type**
- 🏠 Icon
- "Type: [Apartment/House/etc]"
- Gray background

**3. Status**
- ✓ Icon
- "Status: For Sale/Rent"
- Badge style
- Color-coded

**4. Price**
- 💰 Icon
- "Price: €XXX,XXX"
- **Highlighted background** (primary color)
- White text
- Larger font

---

## 🚀 Use Cases

### Different Banners for Different Properties

**Luxury Properties**
- High-end interior shots
- Professional photography
- Warm lighting

**City Apartments**
- Canal views
- City skyline
- Urban scenes

**Family Homes**
- Garden views
- Neighborhood shots
- Cozy exteriors

**Rental Properties**
- Welcoming interiors
- Community areas
- Lifestyle images

---

## ✨ Best Practices

### Image Selection

**DO:**
- Use high-quality images (1920px+ width)
- Choose images with empty sky/space for text
- Use images that represent the property
- Keep consistent style across listings

**DON'T:**
- Use low-resolution images
- Choose busy backgrounds
- Use images with existing text
- Mix different photography styles

### Banner Management

**Organize by Category:**
- Create default banners for each property type
- Use location-specific banners when possible
- Update seasonally if relevant

**Naming Convention:**
- `banner-luxury-apartment.jpg`
- `banner-amsterdam-canal.jpg`
- `banner-family-home-garden.jpg`

---

## 🔍 SEO Benefits

### Structured Data
- Breadcrumb navigation improves SEO
- Clear hierarchy for search engines
- Better user experience signals

### Image Optimization
- Use descriptive filenames
- Add alt text in media library
- Compress images before upload
- Use WebP format when possible

---

## 📝 Admin Workflow

### Adding New Property

1. Create property post
2. Add property details (price, size, etc.)
3. Upload property gallery images
4. **Upload custom banner** (or use default)
5. Publish

### Updating Property Banner

1. Edit property
2. Scroll to sidebar
3. Find "Property Page Banner Image"
4. Click "Upload Banner Image"
5. Select/Upload new image
6. Update property

---

## 🎉 Summary

**What Users See:**
- Professional banner on every property page
- Clear breadcrumb navigation
- Key property info at a glance
- Consistent branding

**What Admins Get:**
- Easy banner upload interface
- Visual preview
- Default fallback option
- Per-property customization

**What You Get:**
- Professional-looking property pages
- Improved user experience
- Better navigation
- Flexible customization

---

## 🚀 Result

Every property detail page now has:
✅ Eye-catching banner image
✅ Clear breadcrumb trail
✅ Property title overlay
✅ Quick info section
✅ Professional appearance
✅ Mobile responsive
✅ Easy to customize

**Perfect for showcasing properties!** 🏠
