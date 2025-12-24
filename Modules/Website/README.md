# Website Module

Website management module for Laravel ERP with multi-mode support.

## Features

- **Dashboard** - Overview of website status, mode, and quick actions
- **Settings** - Configure site name, URL, mode, logo, favicon, contact info, SEO
- **Site Modes**:
  - `Website Only` - Pages and content only, no ecommerce
  - `Ecommerce Only` - Shop focused, homepage redirects to /shop
  - `Both` - Full website with pages + ecommerce

## Installation

1. Copy the `Website` folder to `Modules/Website`

2. Run migrations:
```bash
php artisan migrate
```

3. The module will auto-register via the service provider.

## Controllers

| Controller | Extends | Purpose |
|------------|---------|---------|
| `AdminWebsiteController` | `AdminController` | Admin panel pages (uses admin layout) |
| `WebsiteController` | `Controller` | Public site routes (future use) |

## Database Tables

### `website_settings`
Stores all website configuration in a single row.

| Column | Type | Description |
|--------|------|-------------|
| site_name | varchar | Website name |
| site_url | varchar | Public URL |
| site_logo | varchar | Logo file path |
| site_favicon | varchar | Favicon file path |
| site_mode | enum | website_only, ecommerce_only, both |
| homepage_id | bigint | Selected homepage page ID |
| contact_email | varchar | Contact email |
| contact_phone | varchar | Contact phone |
| meta_title | varchar | Default SEO title |
| meta_description | text | Default SEO description |
| is_active | boolean | Website on/off |

### `customers` table
Adds `is_website_user` column to track website registrations.

## Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/admin/website` | GET | Dashboard |
| `/admin/website/settings` | GET | Settings form |
| `/admin/website/settings` | POST | Save settings |
| `/admin/website/remove-logo` | POST | Remove logo (AJAX) |
| `/admin/website/remove-favicon` | POST | Remove favicon (AJAX) |

## Usage

### Get Settings
```php
use Modules\Website\Models\WebsiteSetting;

// Get settings instance
$settings = WebsiteSetting::instance();

// Get specific value
$siteName = WebsiteSetting::getValue('site_name', 'Default Name');

// Check mode
if ($settings->hasEcommerce()) {
    // Show shop features
}

if ($settings->hasWebsite()) {
    // Show pages
}
```

### Check Website Customers
```php
// Get all website registered customers
$websiteCustomers = Customer::where('is_website_user', 1)->get();
```

## Future Enhancements (Not in v1)

- Pages management
- Menu builder
- Media library
- Forms & enquiries
- Ecommerce (Categories, Products, Orders)
- Public site routes & views

## Author

EchoPx Technologies
