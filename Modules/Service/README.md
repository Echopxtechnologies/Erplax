# Service Module

Equipment service management module with scheduling and tracking.

## Features

- **Service Management**: Create, edit, view, and delete service records
- **Client Association**: Link services to customers from the customers table
- **Service Scheduling**: Set service frequency (monthly, quarterly, half-yearly, yearly, custom)
- **Automatic Next Service Date**: Calculates next service date when marked as completed
- **Status Tracking**: Track both equipment status (active/inactive) and service status (draft, pending, completed, overdue, canceled)
- **Overdue Detection**: Automatically highlights overdue services
- **DataTable Integration**: Full-featured data table with search, filters, sorting, and pagination
- **Import/Export**: Support for CSV, Excel, and PDF exports, plus Excel import functionality
- **Bulk Operations**: Bulk delete functionality

## Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| client_id | foreign key | Yes | Reference to customers table |
| machine_name | string | Yes | Name of the machine/equipment |
| equipment_no | string | No | Equipment identification number |
| model_no | string | No | Model number |
| serial_number | string | No | Serial number |
| service_frequency | enum | Yes | monthly, quarterly, half_yearly, yearly, custom |
| first_service_date | date | Yes | Initial service date |
| next_service_date | date | Auto | Calculated based on frequency |
| status | enum | No | active (default), inactive |
| service_status | enum | No | draft (default), pending, completed, overdue, canceled |
| notes | text | No | Additional notes |

## Installation

1. Copy the `Service` folder to your `Modules` directory
2. Run migrations: `php artisan migrate`
3. Clear cache: `php artisan cache:clear && php artisan config:clear`

## Routes

| Method | URI | Action |
|--------|-----|--------|
| GET | /admin/service | List all services |
| GET | /admin/service/create | Create form |
| POST | /admin/service | Store new service |
| GET | /admin/service/{id} | View service |
| GET | /admin/service/{id}/edit | Edit form |
| PUT | /admin/service/{id} | Update service |
| DELETE | /admin/service/{id} | Delete service |
| POST | /admin/service/bulk-delete | Bulk delete |
| POST | /admin/service/{id}/mark-completed | Mark as completed |
| POST | /admin/service/{id}/toggle-status | Toggle active/inactive |
| POST | /admin/service/{id}/update-service-status | Update service status |

## Service Frequency Calculations

When a service is marked as completed, the next service date is automatically calculated:

- **Monthly**: +1 month
- **Quarterly**: +3 months
- **Half Yearly**: +6 months
- **Yearly**: +1 year
- **Custom**: No automatic calculation

## Requirements

- Laravel 10+
- PHP 8.1+
- Customers table with `id`, `company`, and `name` fields
