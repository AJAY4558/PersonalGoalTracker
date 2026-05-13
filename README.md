# 🎯 Personal Goal Tracker

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![MongoDB](https://img.shields.io/badge/MongoDB-47A248?style=for-the-badge&logo=mongodb&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A complete full-stack Laravel MVC web application for tracking personal goals.**  
Built for BTech Web Development (MVC Architecture) syllabus covering all 15 units.

[Features](#-features) • [Tech Stack](#-tech-stack) • [Setup](#-quick-setup) • [Routes](#-routes) • [Screenshots](#-screenshots) • [Syllabus Coverage](#-syllabus-coverage)

</div>

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| 🔐 **Authentication** | Register, Login with Remember Me, Logout, Session handling, CSRF protection |
| 🎯 **Goal Management** | Full CRUD — Create, Read, Update, Delete goals |
| 📊 **Progress Tracking** | 0–100% progress slider with Chart.js visualizations |
| 📅 **Deadlines** | Set deadlines, overdue detection, days-remaining counter |
| 🏷️ **Categories** | Organize goals by category (Health, Career, Finance, etc.) |
| 🔍 **Search & Filter** | Search by title/description, filter by status/category/priority |
| 📁 **File Uploads** | Attach images/documents (JPG, PNG, PDF, DOC) to goals |
| 📧 **Email Notifications** | Welcome, goal completion, and deadline reminder emails |
| 🌐 **Multilingual** | English & Hindi (हिन्दी) with session-based language switcher |
| 📈 **Dashboard** | Stats cards + Chart.js doughnut/bar/line charts |
| ⚙️ **Admin Panel** | System-wide stats at `/admin/dashboard` |
| 🍃 **MongoDB Logging** | Activity logs stored in MongoDB with file fallback |
| ⏰ **Artisan Scheduler** | `php artisan goals:send-reminders` runs daily |

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 12 |
| **Language** | PHP 8.2 |
| **Database** | SQLite (default) / MySQL |
| **NoSQL** | MongoDB (activity logs via `mongodb/laravel-mongodb`) |
| **Frontend** | Bootstrap 5.3, Chart.js 4, Bootstrap Icons |
| **Templating** | Laravel Blade |
| **Auth** | Laravel built-in guards |
| **Mail** | Laravel Mail (log driver for dev) |
| **ORM** | Eloquent + Query Builder |
| **Fonts** | Google Fonts — Inter |

---

## 🚀 Quick Setup

### Prerequisites
- PHP 8.2+
- Composer 2.x
- SQLite (included with PHP) or MySQL

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/AJAY4558/PersonalGoalTracker.git
cd PersonalGoalTracker

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Run migrations + seed demo data
php artisan migrate:fresh --seed

# 6. Create storage symlink (for file uploads)
php artisan storage:link

# 7. Start the development server
php artisan serve
```

🌐 **Open:** http://localhost:8000

### Demo Login Credentials
| Field | Value |
|-------|-------|
| **Email** | `demo@goaltracker.com` |
| **Password** | `password123` |

---

## 🗄️ Database Configuration

### SQLite (Default — Zero Config)
Already configured in `.env`. Just run `migrate:fresh --seed` and you're done.

### MySQL (XAMPP / Production)
1. Start MySQL in XAMPP Control Panel
2. Create database `goal_tracker` in phpMyAdmin
3. Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goal_tracker
DB_USERNAME=root
DB_PASSWORD=
```

4. Run `php artisan migrate:fresh --seed`

---

## 📁 Project Structure

```
PersonalGoalTracker/
├── app/
│   ├── Console/Commands/
│   │   └── SendDeadlineReminders.php    # Custom Artisan command
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Register | Login | Logout
│   │   │   ├── GoalController.php       # Full Resource CRUD
│   │   │   ├── DashboardController.php  # Stats + Chart data
│   │   │   ├── ProfileController.php    # Profile + Avatar
│   │   │   └── LocaleController.php     # EN/HI Language switcher
│   │   ├── Middleware/
│   │   │   └── SetLocale.php            # Global locale middleware
│   │   └── Requests/
│   │       ├── RegisterRequest.php      # Registration validation
│   │       ├── StoreGoalRequest.php     # Create goal validation
│   │       └── UpdateGoalRequest.php    # Update goal validation
│   ├── Mail/
│   │   ├── WelcomeMail.php
│   │   ├── GoalCompletedMail.php
│   │   └── DeadlineReminderMail.php
│   ├── Models/
│   │   ├── User.php                     # hasMany Goals
│   │   ├── Goal.php                     # belongsTo User + Category
│   │   ├── Category.php                 # hasMany Goals
│   │   └── ActivityLog.php              # MongoDB model
│   └── Services/
│       └── ActivityLogService.php       # MongoDB + file fallback
├── database/
│   ├── migrations/                      # 6 migration files
│   └── seeders/                         # CategorySeeder + UserSeeder
├── lang/
│   ├── en/messages.php                  # English translations
│   └── hi/messages.php                  # Hindi translations
├── public/css/app.css                   # Custom stylesheet
├── resources/views/
│   ├── layouts/                         # app.blade.php + guest.blade.php
│   ├── partials/                        # navbar, sidebar, footer, alerts
│   ├── auth/                            # login, register
│   ├── dashboard/                       # index (charts), admin
│   ├── goals/                           # index, create, edit, show, _form
│   ├── profile/                         # index
│   ├── mail/                            # welcome, goal_completed, reminder
│   └── home.blade.php                   # Landing page
└── routes/
    ├── web.php                          # All web routes
    └── console.php                      # Artisan scheduler
```

---

## 🌐 Routes

| Method | URL | Controller | Description |
|--------|-----|-----------|-------------|
| GET | `/` | — | Landing page |
| GET | `/register` | AuthController | Registration form |
| POST | `/register` | AuthController | Process registration + send welcome email |
| GET | `/login` | AuthController | Login form |
| POST | `/login` | AuthController | Authenticate + remember me + cookie |
| POST | `/logout` | AuthController | Logout + session invalidate |
| GET | `/dashboard` | DashboardController | Stats + Chart.js |
| GET | `/goals` | GoalController@index | List + search + filter |
| POST | `/goals` | GoalController@store | Store new goal + file upload |
| GET | `/goals/create` | GoalController@create | Create form |
| GET | `/goals/{goal}` | GoalController@show | View goal (Route Model Binding) |
| GET | `/goals/{goal}/edit` | GoalController@edit | Edit form |
| PUT | `/goals/{goal}` | GoalController@update | Update + Query Builder |
| DELETE | `/goals/{goal}` | GoalController@destroy | Delete + storage cleanup |
| GET | `/profile` | ProfileController | View profile |
| PUT | `/profile` | ProfileController | Update name/email/avatar |
| PUT | `/profile/password` | ProfileController | Change password |
| PUT | `/profile/locale` | ProfileController | Save locale preference |
| GET | `/locale/{lang}` | LocaleController | Switch language (en\|hi) |
| GET | `/admin/dashboard` | DashboardController | Admin panel |

---

## ⚡ Artisan Commands

```bash
# Run migrations fresh with demo data
php artisan migrate:fresh --seed

# List all registered routes
php artisan route:list

# Send deadline reminders (check next 3 days)
php artisan goals:send-reminders

# Send reminders for goals due in next 7 days
php artisan goals:send-reminders --days=7

# Run the task scheduler manually
php artisan schedule:run

# Create storage symlink
php artisan storage:link

# Clear all caches
php artisan optimize:clear
```

---

## 📧 Email Setup (Mailtrap for Testing)

Emails currently log to `storage/logs/laravel.log` (default `MAIL_MAILER=log`).

To receive real emails via [Mailtrap](https://mailtrap.io):

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

### Email Events
| Trigger | Email |
|---------|-------|
| User registers | Welcome email with feature list |
| Goal status → `completed` | Congratulations email |
| Goal deadline within 3 days | Reminder email (via Artisan command) |

---

## 🍃 MongoDB Integration

Activity logs are stored in MongoDB via `mongodb/laravel-mongodb`.

**Enable MongoDB:**
1. Install [MongoDB Community Server](https://www.mongodb.com/try/download/community)
2. Enable `ext-mongodb` in `php.ini` (XAMPP: `C:\xampp\php\php.ini`)
3. Add to `.env`:
```env
MONGODB_URI=mongodb://localhost:27017
MONGODB_DATABASE=goal_tracker_logs
```

**Without MongoDB:** `ActivityLogService` automatically falls back to writing logs in `storage/logs/laravel.log`. ✅

---

## 📚 Syllabus Coverage — All 15 Units

| # | Unit | Implementation |
|---|------|---------------|
| 1 | Framework & Setup | Laravel 12, Composer, `.env`, MVC directory structure, Artisan |
| 2 | Authentication | Register, Login (Remember Me), Logout, Sessions, Cookies, Redirects, Named Routes |
| 3 | Goal Management | Eloquent CRUD, Route Model Binding, File Upload, Search & Filter |
| 4 | Routing | Basic, Named, Grouped, Prefixed, Resource, Parameter Constraints (`en\|hi`) |
| 5 | Controllers & Middleware | 5 controllers, SetLocale middleware, Form Requests, RESTful routes |
| 6 | Blade Templates | Master layout, Template inheritance, Sections/Yields, Loops, Conditions, PHP vars |
| 7 | Requests & File Handling | FormRequest classes, `$request->file()`, `Storage::disk()`, JSON responses |
| 8 | Email | 3 Mailable classes, Blade email templates, Mail::to()->send() |
| 9 | Localization & Sessions | `lang/en/` + `lang/hi/`, `__()` helper, `session()`, `app()->setLocale()` |
| 10 | Form Validation | Custom rules, `after:today`, `min/max`, error messages, old input |
| 11 | Database | 6 migrations, 2 seeders, User→Goals + Category→Goals relationships |
| 12 | MongoDB | `ActivityLog` model (MongoDB), `ActivityLogService` with graceful fallback |
| 13 | Dashboard | 4 stat cards, Chart.js doughnut/bar/line, upcoming deadlines, recent goals |
| 14 | Security | Auth middleware, CSRF on all forms, validation, session regeneration on login |
| 15 | Project Output | Complete project, README, seeders, mail config, localization, Artisan commands |

---

## 🎨 UI Screenshots

> The app features a **dark sidebar** design with a **light content area**, responsive mobile layout, Bootstrap 5 components, and Chart.js analytics.

**Pages:** Landing → Register/Login → Dashboard (with charts) → Goals List → Goal Detail → Profile → Admin

---

## 🔐 Security Features

- ✅ CSRF tokens on every form (`@csrf`)
- ✅ `Auth` middleware on all protected routes
- ✅ Session regeneration on login (prevents session fixation)
- ✅ Session invalidation on logout
- ✅ Form Request validation (server-side)
- ✅ Route Model Binding with ownership check (`abort_if`)
- ✅ Hashed passwords (`bcrypt` via `password` cast)
- ✅ File type/size validation before upload
- ✅ Parameter constraint on locale route (`where('lang', 'en|hi')`)

---

## 📝 License

MIT License — Free to use for educational/project purposes.

---

<div align="center">

Built with ❤️ using **Laravel 12** | PHP 8.2 | Bootstrap 5 | Chart.js

⭐ Star this repo if it helped you!

</div>
