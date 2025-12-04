# Book Module - Installation Guide

## Step-by-Step Installation

### Step 1: Extract Module Files

Extract the BookModule folder to your modules directory (typically `app/Modules/` or `modules/`):

```
your-app/
├── app/
├── modules/
│   └── Book/
│       ├── Config/
│       ├── Database/
│       ├── Http/
│       ├── Models/
│       ├── Providers/
│       ├── Resources/
│       ├── Routes/
│       ├── module.json
│       └── composer.json
```

### Step 2: Register Service Provider

Add the BookServiceProvider to your application's providers configuration.

In `config/app.php` or your modules configuration file, add:

```php
Modules\Book\Providers\BookServiceProvider::class,
```

### Step 3: Run Migrations

Run the database migrations to create the books table:

```bash
php artisan migrate
```

This will create the `books` table with the following columns:
- `id` (Primary Key)
- `title` (String, Indexed)
- `author` (String, Indexed)
- `description` (Text, Nullable)
- `created_at` (Timestamp)
- `updated_at` (Timestamp)

### Step 4: Access the Module

Navigate to `/admin/book` in your admin panel to start managing books.

## Features

### List View
- View all books in a paginated table
- Shows: Title, Author, and Action buttons
- Action buttons: Edit and Delete only

### Create Book
- Simple form to add new books
- Fields: Title (required), Author (required), Description (optional)
- Form validation

### Edit Book
- Update existing book information
- Pre-filled form with current data

### Delete Book
- Delete books with confirmation dialog
- Removes book from database

## Configuration

The module is configured via `module.json`:

```json
{
  "name": "Book",
  "alias": "book",
  "description": "Book management module for admin panel",
  "version": "1.0.0",
  "active": 1
}
```

## Database Schema

```sql
CREATE TABLE books (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  description LONGTEXT,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_title (title),
  INDEX idx_author (author)
);
```

## Troubleshooting

### Module not appearing in admin panel
- Ensure the module.json file is present
- Check that BookServiceProvider is registered
- Verify the module path is correct

### Migrations failing
- Check database connection settings
- Ensure permissions are correct for database operations
- Run: `php artisan migrate:refresh` to reset

### Routes not working
- Clear route cache: `php artisan route:clear`
- Check middleware configuration in routes/web.php
- Verify authentication and admin middleware

## Support

For additional help, refer to the README.md file or check the module structure for more details.
