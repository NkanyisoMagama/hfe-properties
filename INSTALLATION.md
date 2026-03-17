# HFE Properties - Installation Guide

## Quick Start Guide

### Step 1: Install the Plugin

1. **Upload to WordPress:**
   - Copy the entire `hfe-properties` folder to `/wp-content/plugins/`
   - Or zip the folder and upload via WordPress admin (Plugins > Add New > Upload)

2. **Activate:**
   - Go to **Plugins** in WordPress admin
   - Find "HFE Properties"
   - Click **Activate**

3. **🎉 Automatic Setup Happens!**
   - The plugin automatically creates everything you need
   - You'll see a success notice with links to all created pages
   - No manual setup required!

### Step 2: Review What Was Created

After activation, the plugin automatically creates:

**📄 Pages (Ready to Use):**
- Properties for Sale (`/properties-for-sale/`)
- Properties for Rent (`/properties-for-rent/`)
- All Properties (`/all-properties/`)

**🏠 Property Types:**
- Apartment
- House
- Studio
- Villa
- Penthouse

**📍 Locations:**
- Amsterdam
- Rotterdam
- The Hague
- Utrecht
- Eindhoven
- Haarlem
- Leiden

**You can edit or add more anytime!**

### ~~Step 2: Create Property Types & Locations~~ (AUTOMATIC!)

~~Before adding properties, set up your taxonomies:~~ **This is now automatic!**

**These are created automatically, but you can add more:**

1. **Add More Property Types:**
   - Go to **Properties > Property Types**
   - Click "Add New Property Type"
   - Examples: Townhouse, Condo, Duplex

2. **Add More Locations:**
   - Go to **Properties > Locations**
   - Click "Add New Location"
   - Examples: Groningen, Maastricht, Delft

### Step 3: Add Your First Property

1. Go to **Properties > Add New**

2. **Basic Info:**
   - Title: e.g., "Luxury 2-Bedroom Apartment in Amsterdam"
   - Description: Add detailed property description

3. **Featured Image:**
   - Set the main property image (required for carousel)

4. **Property Details:**
   - Status: For Sale or For Rent
   - Availability: Available
   - Price: e.g., 450000 (no symbols, just numbers)
   - Currency: EUR
   - Size: e.g., 120 (in square meters)
   - Bedrooms: e.g., 2
   - Bathrooms: e.g., 1.5
   - Floor: e.g., "3rd floor"
   - Parking: e.g., 1
   - Year Built: e.g., 2020

5. **Gallery:**
   - Click "Add Images"
   - Select multiple images
   - Drag to reorder

6. **Features:**
   - Add features, one per line:
     ```
     Luxury Living Room
     Modern Kitchen
     Balcony with City View
     Smart Home System
     Premium Appliances
     ```

7. **Taxonomies:**
   - Select Property Type
   - Select Location

8. **Publish!**

### ~~Step 4: Create a Properties Page~~ (AUTOMATIC!)

**Pages are already created!** Just add them to your menu:

1. Go to **Appearance > Menus**
2. Find the auto-created pages:
   - Properties for Sale
   - Properties for Rent
   - All Properties
3. Add them to your menu
4. Save

**Want to customize the pages?**
- Click the "Edit" link in the activation notice
- Or go to Pages > All Pages and edit them

### Step 5: View Your Pages

Check out the automatically created pages:
- Visit `/properties-for-sale/` on your site
- Visit `/properties-for-rent/` on your site
- Visit `/all-properties/` on your site

They're ready to display properties as soon as you add them!

### Step 6: Optional - Contact Form Setup

To enable the contact form on property detail pages:

1. Install **Contact Form 7** (or WPForms, Gravity Forms)
2. Create a contact form
3. Copy the form ID
4. Edit `hfe-properties/includes/template-functions.php`
5. Find the `hfe_property_contact_form()` function
6. Replace `YOUR_FORM_ID` with your actual form ID:

```php
echo do_shortcode('[contact-form-7 id="123"]');
```

## Shortcode Examples

### Homepage Carousel
```
[hfe_properties_carousel status="all" limit="6"]
```

### Sales Page
```
[hfe_properties_grid status="sale" columns="3" limit="9"]
```

### Rentals Page
```
[hfe_properties_carousel status="rent" limit="8"]
```

### Location-Specific Page (Amsterdam)
```
[hfe_properties_grid location="amsterdam" limit="12" columns="3"]
```

## Customization Tips

### Change Primary Color

Add to your theme's `style.css`:

```css
:root {
    --hfe-primary-color: #YOUR_COLOR;
}
```

### Adjust Carousel Settings

Edit `assets/js/hfe-properties.js`:
- Change `autoplay.delay` for slide speed
- Modify `breakpoints` for responsive behavior

### Override Templates

Copy to your theme:
```
wp-content/themes/your-theme/hfe-properties/templates/single-property.php
```

## Troubleshooting

### Properties Don't Show Up

1. Make sure properties are **Published** (not drafts)
2. Check the shortcode status matches property status (sale/rent)
3. Try adding `status="all"` to see all properties

### Carousel Not Working

1. Check browser console for JavaScript errors
2. Ensure jQuery is loaded (it comes with WordPress)
3. Clear browser cache

### Images Not Showing

1. Upload a featured image for each property
2. Check file permissions in uploads folder
3. Regenerate thumbnails (use plugin)

### Styling Issues

1. Clear cache (browser and WordPress cache plugins)
2. Check for theme CSS conflicts
3. Increase CSS specificity if needed

## Sample Property Data

For testing, here's sample data:

**Property 1:**
- Title: Modern Canal Apartment
- Price: 475000
- Size: 85
- Bedrooms: 2
- Bathrooms: 1
- Location: Amsterdam
- Type: Apartment
- Status: For Sale

**Property 2:**
- Title: Spacious Family House
- Price: 2500
- Size: 150
- Bedrooms: 4
- Bathrooms: 2.5
- Location: Rotterdam
- Type: House
- Status: For Rent

**Property 3:**
- Title: Luxury Penthouse with Terrace
- Price: 1250000
- Size: 200
- Bedrooms: 3
- Bathrooms: 2
- Location: Amsterdam
- Type: Penthouse
- Status: For Sale

## Next Steps

1. Add 5-10 properties to test
2. Create "Properties for Sale" page
3. Create "Properties for Rent" page
4. Add to main navigation
5. Test on mobile devices
6. Customize colors to match your brand
7. Add contact form integration
8. Launch! 🚀

## Need Help?

Contact: info@homesforexpats.nl

---

**Plugin Version:** 1.0.0
**WordPress Version Required:** 5.0+
**PHP Version Required:** 7.0+
