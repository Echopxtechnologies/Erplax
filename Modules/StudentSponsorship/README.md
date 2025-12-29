# StudentSponsorship Module

A comprehensive Laravel module for managing School and University student sponsorships.

## Features

### School Students
- ✅ Full CRUD operations
- ✅ DataTable with server-side processing
- ✅ HashID URL obfuscation (security)
- ✅ Photo upload with Spatie Media Library
- ✅ Report cards (Term1, Term2, Term3)
- ✅ Grade-Age validation
- ✅ Export/Import Excel
- ✅ Bulk actions

### University Students
- ✅ Full CRUD operations
- ✅ DataTable with server-side processing
- ✅ HashID URL obfuscation (security)
- ✅ Photo upload with Spatie Media Library
- ✅ Report cards (Semester-based: 1Y1S - 5Y2S)
- ✅ Export/Import Excel
- ✅ Bulk actions

## Installation

1. Copy the `StudentSponsorship` folder to `Modules/`
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Register the module in `config/app.php` providers:
   ```php
   Modules\StudentSponsorship\Providers\StudentSponsorshipServiceProvider::class,
   ```

## Directory Structure

```
StudentSponsorship/
├── Config/
│   └── config.php
├── Database/
│   └── Migrations/
│       ├── create_school_names_table.php
│       ├── create_school_students_table.php
│       ├── create_school_report_cards_table.php
│       ├── create_university_names_table.php
│       ├── create_university_programs_table.php
│       ├── create_university_students_table.php
│       └── create_university_report_cards_table.php
├── Helpers/
│   └── HashId.php
├── Http/
│   └── Controllers/
│       ├── SchoolStudentController.php
│       └── UniversityStudentController.php
├── Models/
│   ├── SchoolName.php
│   ├── SchoolStudent.php
│   ├── UniversityName.php
│   ├── UniversityProgram.php
│   ├── UniversityReportCard.php
│   └── UniversityStudent.php
├── Providers/
│   ├── RouteServiceProvider.php
│   └── StudentSponsorshipServiceProvider.php
├── Resources/
│   └── views/
│       ├── menu.blade.php
│       ├── school-students/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   ├── _form.blade.php
│       │   └── import.blade.php
│       └── university-students/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           ├── show.blade.php
│           └── _form.blade.php
└── Routes/
    ├── web.php
    └── api.php
```

## Routes

### School Students
- `GET /admin/studentsponsorship/school-students` - List
- `GET /admin/studentsponsorship/school-students/create` - Create form
- `POST /admin/studentsponsorship/school-students` - Store
- `GET /admin/studentsponsorship/school-students/{hash}` - Show
- `GET /admin/studentsponsorship/school-students/{hash}/edit` - Edit form
- `PUT /admin/studentsponsorship/school-students/{hash}` - Update
- `DELETE /admin/studentsponsorship/school-students/{hash}` - Delete

### University Students
- `GET /admin/studentsponsorship/university-students` - List
- `GET /admin/studentsponsorship/university-students/create` - Create form
- `POST /admin/studentsponsorship/university-students` - Store
- `GET /admin/studentsponsorship/university-students/{hash}` - Show
- `GET /admin/studentsponsorship/university-students/{hash}/edit` - Edit form
- `PUT /admin/studentsponsorship/university-students/{hash}` - Update
- `DELETE /admin/studentsponsorship/university-students/{hash}` - Delete

## Security

- HashID obfuscation prevents ID enumeration attacks
- XSS prevention with input sanitization
- CSRF protection on all forms
- File upload validation (type, size)

## Requirements

- Laravel 10+
- PHP 8.1+
- Spatie Media Library
- PhpSpreadsheet (for import/export)

## Configuration

Update `.env` for custom HashID salt:
```
STUDENT_HASHID_SALT=your-custom-salt-here
```
