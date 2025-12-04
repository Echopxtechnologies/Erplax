# Book Module

A simple book management module for the admin panel. Create, read, update, and delete books with title, description, and author information.

## Features

- **Create Books**: Add new books with title, description, and author
- **List View**: Simple list view showing title, author, and action buttons (Edit & Delete only)
- **Edit Books**: Update existing book information
- **Delete Books**: Remove books from the system
- **Pagination**: Pagination support for large book lists

## Fields

Each book contains:
- **Title** (Required): The name of the book
- **Author** (Required): The author of the book
- **Description** (Optional): A description of the book

## Installation

1. Extract the BookModule folder to your modules directory
2. Add the module to your modules configuration
3. Register the BookServiceProvider in your application
4. Run migrations: `php artisan migrate`
5. Access the book management panel at `/admin/book`

## Routes

- `GET /admin/book` - List all books
- `GET /admin/book/create` - Create book form
- `POST /admin/book` - Store new book
- `GET /admin/book/{id}` - View book details
- `GET /admin/book/{id}/edit` - Edit book form
- `PUT /admin/book/{id}` - Update book
- `DELETE /admin/book/{id}` - Delete book

## Version

Version: 1.0.0
