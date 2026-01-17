# Overseas Candidate Progress Management System (OCPMS)

OCPMS is a Laravel 10 web application that centralises the onboarding process for overseas job candidates. It replaces spreadsheet-based tracking with role-aware dashboards, structured candidate records, and secure document storage.

## Tech Stack

- **Backend**: PHP 8.2, Laravel 10, Laravel Breeze authentication
- **Frontend**: Blade templates, Tailwind CSS, Alpine.js, Chart.js
- **Database**: MySQL 8+ (SQLite in tests), Eloquent ORM
- **Storage**: Laravel public disk for document uploads

## Getting Started

1. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run dev   # or npm run build for production
   ```
2. **Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update database credentials (`DB_*`) for MySQL. Document uploads use the `public` filesystem disk by default; if you change it, update `FILESYSTEM_DISK`.
3. **Database**
   ```bash
   php artisan migrate --seed
   ```
4. **Storage symlink**
   ```bash
   php artisan storage:link
   ```
5. **Run tests / dev server**
   ```bash
   php artisan test
   php artisan serve
   ```

### Default Accounts

| Role   | Email                | Password |
|--------|----------------------|----------|
| Admin  | `admin@ocpms.test`   | `password` |
| Staff  | `staff@ocpms.test`   | `password` |
| Candidate | `candidate@ocpms.test` | `password` |

Candidate users are redirected to their own progress page, while Admin & Staff can access dashboards and management tools.

## Core Modules

- **Authentication & Authorization**: Breeze scaffolding with role gates (admin, staff, candidate). Access to candidate data is restricted per role.
- **Candidate Management**: CRUD UI with search, position/visa/medical filters, pagination, and aggregated progress data.
- **Progress Tracking**: Down-payment, medical, visa, ticket, departure date, and remarks fields stored in `candidate_progress`.
- **Document Center**: Upload/download/delete passport, medical, visa, and ticket files stored on the public disk.
- **Dashboard & Reporting**: Real-time widgets (totals, medical backlog, document count), visa distribution chart (Chart.js), upcoming departures, and latest candidates.

## Routing Overview

| Method | Endpoint                                 | Description                         |
|--------|------------------------------------------|-------------------------------------|
| GET    | `/login`                                 | Breeze login                        |
| GET    | `/dashboard`                             | Role-aware dashboard                |
| GET    | `/candidates`                            | Candidate list & filters            |
| POST   | `/candidates`                            | Create candidate + progress         |
| GET    | `/candidates/{candidate}`                | Candidate details + document center |
| PUT    | `/candidates/{candidate}`                | Update candidate + progress         |
| DELETE | `/candidates/{candidate}`                | Delete candidate                    |
| POST   | `/candidates/{candidate}/documents`      | Upload document                     |
| DELETE | `/candidates/{candidate}/documents/{id}` | Remove document                     |

## Further Enhancements

- Notifications (email/WhatsApp) for visa/passport reminders
- Activity/audit logs
- Public REST API for mobile integrations

Pull requests and feature ideas are welcome!
