# StudentSponsorship Module

A comprehensive student sponsorship portal module for managing school students, university students, and sponsors.

## Features

### School Students
- Full CRUD operations for school students
- Auto-generated unique student IDs (SCH + Year + 5-digit number)
- Age-Grade validation based on Sri Lankan education system
- Profile photo upload
- Guardian information
- Bank details for sponsorship payments
- Import/Export (CSV, Excel)
- DataTable with search, filters, sorting, pagination
- Bulk delete

## Installation

1. Copy the `StudentSponsorship` folder to your `Modules` directory
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

## File Structure

```
StudentSponsorship/
├── Config/
│   └── config.php              # Module configuration
├── Database/
│   └── Migrations/             # Database migrations
├── Http/
│   └── Controllers/
│       └── SchoolStudentController.php
├── Models/
│   ├── SchoolName.php
│   └── SchoolStudent.php
├── Providers/
│   ├── RouteServiceProvider.php
│   └── StudentSponsorshipServiceProvider.php
├── Resources/
│   └── views/
│       ├── school-students/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── menu.blade.php
├── Routes/
│   └── web.php
├── composer.json
└── module.json
```

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | /admin/studentsponsorship/school-students | school-students.index | List all students |
| GET/POST | /admin/studentsponsorship/school-students/data | school-students.data | DataTable endpoint |
| GET | /admin/studentsponsorship/school-students/create | school-students.create | Create form |
| POST | /admin/studentsponsorship/school-students | school-students.store | Store new student |
| GET | /admin/studentsponsorship/school-students/{id} | school-students.show | View student |
| GET | /admin/studentsponsorship/school-students/{id}/edit | school-students.edit | Edit form |
| PUT | /admin/studentsponsorship/school-students/{id} | school-students.update | Update student |
| DELETE | /admin/studentsponsorship/school-students/{id} | school-students.destroy | Delete student |
| POST | /admin/studentsponsorship/school-students/bulk-delete | school-students.bulk-delete | Bulk delete |

## Configuration

Edit `Config/config.php` to customize:
- Grade-to-age mapping
- School grades list
- School types

## Age-Grade Validation

The module validates student age against expected grade ranges based on the Sri Lankan education system:
- Grade 1: Age 5-6
- Grade 2: Age 6-7
- ...
- Grade 11 (O/L): Age 15-16
- Grade 12-14 (A/L): Age 16-19

If age doesn't match the expected range, a reason must be provided.

## Future Modules (Coming Soon)

- University Students
- Sponsors
- Sponsor Transactions
- Report Cards
- Dashboard & Analytics

## Version

1.0.0
