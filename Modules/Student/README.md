# Student Module

A Laravel module for managing students with DataTable support.

## Features

- Student CRUD operations
- DataTable with search, export, pagination
- Permission-based access control
- Clean, responsive UI

## Requirements

- Laravel 10+
- DataTableTrait (in app/Traits/)
- datatable.js & datatable.css (in public folder)

## Installation

See `INSTALLATION_GUIDE.md` for detailed instructions.

## Usage

Access the module at: `/admin/student`

## Permissions

- `student.list.read` - View students list
- `student.create.create` - Create new student
- `student.list.edit` - Edit student
- `student.list.delete` - Delete student
