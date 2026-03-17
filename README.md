# HFE Properties Plugin

A custom WordPress plugin for **Homes for Expats** to manage and display properties for sale and rent with a beautiful carousel interface.

## Features

- ✅ Custom Post Type: Properties
- ✅ Custom Taxonomies: Property Types & Locations
- ✅ Comprehensive Property Fields (price, bedrooms, bathrooms, size, etc.)
- ✅ Beautiful Swiper-based Carousel
- ✅ Responsive Property Cards
- ✅ Detailed Property Pages with Gallery
- ✅ Property Archive Pages
- ✅ Grid and Carousel Display Options
- ✅ Admin Gallery Manager with Drag & Drop
- ✅ Contact Form Integration Ready
- ✅ Social Sharing Buttons
- ✅ Related Properties Display

## Installation

1. Upload the `hfe-properties` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. **Automatic Setup:** The plugin will automatically create:
   - 3 property pages (For Sale, For Rent, All Properties) with shortcodes
   - Default property types (Apartment, House, Studio, Villa, Penthouse)
   - Default locations (Amsterdam, Rotterdam, The Hague, Utrecht, etc.)
4. Go to Properties > Add New to create your first property

**Note:** After activation, you'll see a success notice with links to all created pages!

## What Gets Created Automatically

When you activate the plugin, it automatically sets up:

### 📄 Pages (with shortcodes already added)
- **Properties for Sale** - `/properties-for-sale/`
- **Properties for Rent** - `/properties-for-rent/`
- **All Properties** - `/all-properties/`

### 🏠 Property Types
- Apartment
- House
- Studio
- Villa
- Penthouse

### 📍 Locations (Dutch Cities)
- Amsterdam
- Rotterdam
- The Hague
- Utrecht
- Eindhoven
- Haarlem
- Leiden

You can add more or edit these in WordPress admin!

## Usage

### Creating Properties

1. Navigate to **Properties > Add New** in WordPress admin
2. Fill in the property title and description
3. Set a featured image for the property card
4. Fill in property details:
   - Status (For Sale / For Rent)
   - Availability (Available / Pending / Sold)
   - Price
   - Size (m²)
   - Bedrooms, Bathrooms
   - Floor, Terrace, Parking
   - Year Built
5. Add multiple gallery images
6. Add features and amenities (one per line)
7. Assign Property Type and Location
8. Publish!

### Displaying Properties

#### Carousel (Recommended)

Add this shortcode to any page:

```
[hfe_properties_carousel status="sale" limit="6"]
```

**Parameters:**
- `status` - "sale", "rent", or "all" (default: "sale")
- `limit` - Number of properties to show (default: 6)
- `location` - Filter by location slug (optional)
- `type` - Filter by property type slug (optional)
- `orderby` - "date", "title", "price" (default: "date")
- `order` - "DESC" or "ASC" (default: "DESC")

**Examples:**

```
// Show 8 properties for sale
[hfe_properties_carousel status="sale" limit="8"]

// Show properties for rent in Amsterdam
[hfe_properties_carousel status="rent" location="amsterdam" limit="6"]

// Show all luxury apartments
[hfe_properties_carousel status="all" type="luxury-apartment" limit="10"]
```

#### Grid Layout

Add this shortcode for a grid display:

```
[hfe_properties_grid status="sale" limit="9" columns="3"]
```

**Parameters:**
- All carousel parameters, plus:
- `columns` - Number of columns (2, 3, or 4) (default: 3)

**Examples:**

```
// 2 column grid
[hfe_properties_grid columns="2" limit="6"]

// 4 column grid of rentals
[hfe_properties_grid status="rent" columns="4" limit="12"]
```

## Property Pages

The plugin automatically creates beautiful single property pages with:

- Hero header with location and price
- Property statistics grid
- Full description
- Features and amenities list
- Photo gallery with lightbox
- Contact form section
- Property details sidebar
- Social sharing buttons
- Related properties section

## Customization

### Styling

The plugin uses CSS custom properties for easy theming. Override these in your theme:

```css
:root {
    --hfe-primary-color: #CD8C66;  /* Your brand color */
    --hfe-text-color: #333333;
    --hfe-light-gray: #f5f5f5;
    --hfe-border-color: #e0e0e0;
}
```

### Templates

You can override plugin templates by copying them to your theme:

```
your-theme/hfe-properties/templates/single-property.php
your-theme/hfe-properties/templates/archive-property.php
```

### Contact Form Integration

The plugin is ready for Contact Form 7, WPForms, or Gravity Forms.

Edit `includes/template-functions.php` in the `hfe_property_contact_form()` function:

```php
// Replace YOUR_FORM_ID with your actual form ID
echo do_shortcode('[contact-form-7 id="123"]');
```

## Property Taxonomies

### Property Types

Default types are created automatically:
- Apartment
- House
- Studio
- Villa
- Penthouse

Go to **Properties > Property Types** to add more or edit existing ones.

### Locations

Default Dutch cities are created automatically:
- Amsterdam
- Rotterdam
- The Hague
- Utrecht
- Eindhoven
- Haarlem
- Leiden

Go to **Properties > Locations** to add more or edit existing ones.

## Settings Page

Access plugin settings at **Properties > Settings** to:
- View all auto-generated pages
- Regenerate missing pages
- See property and taxonomy statistics
- Access shortcode reference
- Reset installation settings

## File Structure

```
hfe-properties/
├── assets/
│   ├── css/
│   │   ├── hfe-properties.css    # Frontend styles
│   │   └── admin.css             # Admin styles
│   ├── js/
│   │   ├── hfe-properties.js     # Frontend JS (carousel, lightbox)
│   │   └── admin.js              # Admin JS (gallery uploader)
│   └── images/
│       └── placeholder.jpg       # Placeholder image
├── includes/
│   ├── post-types.php            # Register custom post type
│   ├── meta-boxes.php            # Property meta boxes
│   ├── shortcodes.php            # Carousel & grid shortcodes
│   └── template-functions.php    # Template helper functions
├── templates/
│   ├── single-property.php       # Single property template
│   └── archive-property.php      # Archive template
├── hfe-properties.php            # Main plugin file
└── README.md                     # This file
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- jQuery (included with WordPress)

## External Dependencies

- **Swiper.js** - Modern carousel library (loaded from CDN)
- Version: 11.0.0

## Shortcode Reference

### [hfe_properties_carousel]

Display properties in a responsive carousel slider.

**All Parameters:**
```
[hfe_properties_carousel
    status="sale|rent|all"
    limit="6"
    orderby="date|title|price"
    order="DESC|ASC"
    location="location-slug"
    type="property-type-slug"
]
```

### [hfe_properties_grid]

Display properties in a responsive grid layout.

**All Parameters:**
```
[hfe_properties_grid
    status="sale|rent|all"
    limit="9"
    columns="2|3|4"
    orderby="date|title|price"
    order="DESC|ASC"
    location="location-slug"
    type="property-type-slug"
]
```

## Responsive Breakpoints

- **Mobile** (< 768px): 1 column
- **Tablet** (768px - 1024px): 2 columns
- **Desktop** (> 1024px): 3 columns (carousel), customizable (grid)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Development

### Adding Custom Fields

Edit `includes/meta-boxes.php` to add new property fields.

### Modifying Carousel Settings

Edit `assets/js/hfe-properties.js` in the `initPropertyCarousel()` function.

### Styling Property Cards

Edit `assets/css/hfe-properties.css` in the `.hfe-property-card` section.

## Support

For issues or questions:
- Email: nkanyisoweb@gmail.com


## Changelog

### Version 1.0.0
- Initial release
- Custom post type: Properties
- Carousel and grid layouts
- Property detail pages
- Gallery management
- Admin interface
- Responsive design

## Credits


Built with WordPress, Swiper.js, and ❤️

## License

Proprietary - © Homes for Expats. All rights reserved.
