# Student Event Management (SEM)

**Institute of Technology, University of Moratuwa**

A modern, secure web application for managing university events. Students can browse, view details, and register for events. Admins can create, edit, delete events, and view all registrations.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

**Author**: Sivaranjan Dinath  
**Registration Number**: 23IT0471  
**Institution**: Institute of Technology, University of Moratuwa

---

## 🎯 Features

### For Students
- **Browse Events**: View all upcoming university events with details (date, venue, organizer, description)
- **Event Registration**: Register for events with optional student ID and contact information
- **Secure Authentication**: Register, login, logout with password hashing (bcrypt)
- **Responsive UI**: Works seamlessly on desktop, tablet, and mobile devices

### For Admins
- **Full Event CRUD**: Create, read, update, and delete events
- **Event Banners**: Upload and manage event images (JPG, PNG, WebP)
- **Registration Management**: View all student registrations with detailed information
- **Admin Dashboard**: Quick navigation to admin panels from the events list

### Security & Data
- **Password Hashing**: All passwords use PHP's `password_hash()` and `password_verify()`
- **Prepared Statements**: All database queries use PDO prepared statements to prevent SQL injection
- **Session Management**: Secure PHP session-based authentication
- **Role-Based Access Control**: Admin-only pages protected with role checks

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | HTML5, CSS3, Bootstrap 5.3, Vanilla JavaScript |
| **Backend** | PHP 8.x, PDO (Database Abstraction) |
| **Database** | MySQL/MariaDB (or SQLite fallback for local dev) |
| **Server** | Apache/Nginx (production) or PHP built-in server (dev) |
| **UI Theme** | GitHub-inspired dark theme |

---

## 📁 Project Structure

```
student-event-management/
├── README.md                          # This file
├── DEV_NOTES.md                       # Development notes & quick start
├── project/
│   ├── config/
│   │   ├── db.php                     # Database configuration & connection
│   │   └── auth.php                   # Authentication functions & helpers
│   ├── controllers/
│   │   ├── handle_login.php           # Login form processing
│   │   ├── handle_register.php        # Registration form processing
│   │   ├── handle_add_event.php       # Add event (admin)
│   │   ├── handle_edit_event.php      # Edit event (admin)
│   │   ├── handle_delete_event.php    # Delete event (admin)
│   │   └── handle_event_register.php  # Event registration processing
│   ├── db/
│   │   ├── db_structure.sql           # MySQL schema (optional)
│   │   └── dev.sqlite                 # Local SQLite DB (auto-created)
│   ├── includes/
│   │   ├── header.php                 # Navigation & layout header
│   │   └── footer.php                 # Layout footer
│   ├── public/
│   │   ├── index.php                  # Landing page redirect
│   │   ├── login.php                  # Login form
│   │   ├── register.php               # Registration form
│   │   ├── home.php                   # Logged-in home page
│   │   ├── events.php                 # Events list (with admin edit buttons)
│   │   ├── event_register.php         # Event registration form
│   │   ├── admin_add_event.php        # Add event form (admin)
│   │   ├── admin_edit_event.php       # Edit event form (admin)
│   │   ├── admin_delete_event.php     # Delete confirmation (admin)
│   │   ├── admin_registrations.php    # View all registrations (admin)
│   │   ├── logout.php                 # Logout handler
│   │   ├── handle_*.php               # Public proxies to controllers
│   │   ├── assets/
│   │   │   ├── css/style.css          # GitHub-themed dark styles
│   │   │   ├── img/
│   │   │   │   ├── events/            # Uploaded event banners
│   │   │   │   └── footer-banner.png
│   │   │   └── js/script.js           # Client-side validation
│   └── scripts/
│       └── seed_events.php            # Seed sample events
└── .gitignore                         # Git ignore file
```

---

## 🗄️ Database Schema

### `users` Table
Stores user accounts (students and admins).

| Column | Type | Notes |
|--------|------|-------|
| `user_id` | INT UNSIGNED PRIMARY KEY | Auto-increment |
| `name` | VARCHAR(100) | Full name |
| `email` | VARCHAR(150) UNIQUE | Email address |
| `password` | VARCHAR(255) | Bcrypt hash |
| `role` | ENUM('student','admin') | User role (default: 'student') |
| `student_id` | VARCHAR(50) NULL | Optional student ID |
| `created_at` | TIMESTAMP | Registration timestamp |

### `events` Table
Stores events created by admins.

| Column | Type | Notes |
|--------|------|-------|
| `event_id` | INT UNSIGNED PRIMARY KEY | Auto-increment |
| `title` | VARCHAR(255) | Event title |
| `date` | DATE | Event date |
| `time` | TIME NULL | Event time (optional) |
| `venue` | VARCHAR(255) | Event location |
| `organizer` | VARCHAR(255) | Organizer name |
| `description` | TEXT | Event description |
| `image_path` | VARCHAR(255) NULL | Path to uploaded banner |
| `created_by` | INT UNSIGNED NULL | Admin who created event |
| `created_at` | TIMESTAMP | Creation timestamp |

### `registrations` Table
Tracks student registrations for events.

| Column | Type | Notes |
|--------|------|-------|
| `reg_id` | INT UNSIGNED PRIMARY KEY | Auto-increment |
| `user_id` | INT UNSIGNED | FK to `users` |
| `event_id` | INT UNSIGNED | FK to `events` |
| `student_id` | VARCHAR(50) NULL | Student ID (optional) |
| `contact_no` | VARCHAR(50) NULL | Contact number |
| `created_at` | TIMESTAMP | Registration timestamp |
| **Unique Constraint** | (user_id, event_id) | Prevents duplicate registrations |

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7+ / MariaDB (or use SQLite fallback)
- Git (optional)

### Installation & Setup

#### Option 1: Local Development (SQLite, No MySQL needed)

```bash
# Clone or navigate to the repository
cd student-event-management

# Start PHP built-in server
php -S 127.0.0.1:8000 -t project/public

# Open in browser
# http://127.0.0.1:8000
```

The app will automatically create a local SQLite database at `project/db/dev.sqlite` and seed a demo admin user.

**Default login (SQLite fallback):**
- Email: `admin@example.com`
- Password: `Admin123`

#### Option 2: Production MySQL Setup

```bash
# 1. Create the database and import schema
mysql -u root -p < project/db/db_structure.sql

# 2. Update database credentials in project/config/db.php
# or set environment variables:
export DB_HOST=127.0.0.1
export DB_NAME=student_events_db
export DB_USER=root
export DB_PASS=your_password

# 3. Seed sample events (optional)
php project/scripts/seed_events.php

# 4. Start the server or configure Apache/Nginx to serve project/public/
```

### First Time Usage

1. **Open the app**: http://127.0.0.1:8000
2. **Register a new account**: Click "Sign up" and fill in the form
3. **Login**: Use your credentials
4. **Browse events**: View upcoming events on the Events page
5. **Register for an event**: Click "Register" on any event card

### Admin Features (Default Admin)

1. **Login as admin**: `admin@example.com` / `Admin123`
2. **Add an event**: Click "Add Event" in the navbar
3. **Edit an event**: Click the ✏️ button on any event card in the events list
4. **Delete an event**: Click "Edit" → "Delete" button
5. **View registrations**: Click "Registrations" in the navbar to see all student registrations

---

## 📚 Key Pages & Flows

### Public Pages
- `/` → Redirects to login if not logged in, events if logged in
- `/login.php` → Login form
- `/register.php` → User registration form
- `/events.php` → Browse all events (with edit buttons for admins)

### Student Pages (Requires login)
- `/home.php` → Welcome/dashboard after login
- `/event_register.php?id=<event_id>` → Register for specific event

### Admin Pages (Requires admin role)
- `/admin_add_event.php` → Create new event with optional banner
- `/admin_edit_event.php?id=<event_id>` → Edit event details or banner
- `/admin_delete_event.php?id=<event_id>` → Confirm event deletion
- `/admin_registrations.php` → View all student registrations in a table

---

## 🔐 Authentication & Authorization

### Login Flow
1. User submits login form (`/login.php`)
2. Form POSTs to `/handle_login.php`
3. Controller verifies email and password
4. On success: user data stored in `$_SESSION['user']`
5. On error: flash message displayed, user redirected to login

### Admin Check
- Admin pages require `require_admin()` which checks `$_SESSION['user']['role'] === 'admin'`
- Non-admin users get 403 Forbidden or redirected

### Session Management
- Sessions stored in PHP's default session handler (files or Redis, depending on config)
- Logout clears session with `session_destroy()`

---

## 📝 API / Form Endpoints

### Authentication
- `POST /handle_login.php` — Process login form
- `POST /handle_register.php` — Process registration form
- `GET /logout.php` — Logout and destroy session

### Events (Admin)
- `POST /handle_add_event.php` — Create new event (form data + optional file upload)
- `POST /handle_edit_event.php` — Update event (form data + optional file upload)
- `POST /handle_delete_event.php` — Delete event

### Registrations
- `POST /handle_event_register.php` — Register student for event

---

## 🎨 Styling & UI

### Theme
The app uses a **GitHub-inspired dark theme** for a modern, professional look.

- **Background**: `#0d1117` (dark gray-blue)
- **Cards/Panels**: `#161b22` (slightly lighter)
- **Text**: `#c9d1d9` (light gray for readability)
- **Accent**: `#2f81f7` (GitHub blue for buttons/links)
- **Borders**: `#30363d` (subtle dividers)

### Frameworks
- **Bootstrap 5.3**: Responsive grid, buttons, forms, navbar
- **Custom CSS**: `project/public/assets/css/style.css` for dark theme overrides
- **Vanilla JS**: Simple client-side form validation in `project/public/assets/js/script.js`

---

## 🔧 Configuration

### Database Connection
Edit or set environment variables in `project/config/db.php`:

```php
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'student_events_db';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
```

### File Uploads
- **Upload directory**: `project/public/assets/img/events/`
- **Allowed types**: JPG, PNG, WebP
- **Max size**: 2 MB
- **Auto-cleanup**: Old images removed when replaced

### Session
- Default PHP session handler (files)
- Can be configured in `php.ini` or at runtime

---

## 🧪 Testing

### Smoke Test (Manual)
1. Start server: `php -S 127.0.0.1:8000 -t project/public`
2. Visit http://127.0.0.1:8000
3. Register a new student account
4. Login
5. Browse events, register for one
6. (As admin) Add, edit, delete an event

### Automated Seed Script
```bash
# Seed sample events into the database
php project/scripts/seed_events.php
```

---

## 📦 Dependencies

- **PHP 8.0+** (built-in PDO, sessions, password hashing)
- **Bootstrap 5.3** (CDN)
- **SQLite** or **MySQL/MariaDB** (database)

No external PHP dependencies (no Composer required).

---

## 🐛 Troubleshooting

### "Database connection failed"
- **Local dev**: SQLite fallback should activate automatically. Check `project/db/dev.sqlite` exists.
- **MySQL**: Verify credentials in `project/config/db.php` or environment variables.
- **Permissions**: Ensure `project/db/` directory is writable for SQLite.

### "Access denied for user 'root'@'localhost'"
- Using SQLite fallback is fine for local dev.
- For MySQL production, update `DB_HOST`, `DB_USER`, `DB_PASS` credentials.

### "Event image not uploading"
- Check `project/public/assets/img/events/` directory exists and is writable: `chmod 755 project/public/assets/img/events/`
- Verify file size < 2 MB
- Ensure file type is JPG, PNG, or WebP

### "Can't register for an event"
- Ensure you are logged in
- Check the event exists (doesn't show error, but registration fails silently)

---

## 🚢 Deployment

### Apache/Nginx with PHP-FPM

1. **Clone repository** to web root:
   ```bash
   git clone https://github.com/Dinath2002/student-event-management.git /var/www/sem
   ```

2. **Set permissions**:
   ```bash
   chown -R www-data:www-data /var/www/sem
   chmod -R 755 /var/www/sem/project
   chmod -R 770 /var/www/sem/project/db
   ```

3. **Configure database**: Import `project/db/db_structure.sql` into MySQL
4. **Update credentials**: Set `DB_*` env vars or edit `project/config/db.php`

4. **Apache VirtualHost** (example):
   ```apache
   <VirtualHost *:80>
       ServerName events.example.com
       DocumentRoot /var/www/sem/project/public
       
       <Directory /var/www/sem/project/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

5. **Nginx config** (example):
   ```nginx
   server {
       listen 80;
       server_name events.example.com;
       root /var/www/sem/project/public;
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php-fpm.sock;
           fastcgi_index index.php;
           include fastcgi_params;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       }
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
   }
   ```

### Environment Variables (Production)
```bash
export DB_HOST=your-db-host
export DB_NAME=student_events_db
export DB_USER=your-db-user
export DB_PASS=your-secure-password
```

---

## 📋 Future Enhancements

- [ ] Email notifications for event registrations
- [ ] Event search & filtering
- [ ] Admin dashboard with statistics (events created, registrations, etc.)
- [ ] Event categories & tags
- [ ] User profile page with registration history
- [ ] CSV export of registrations
- [ ] Two-factor authentication (2FA)
- [ ] Event cancellation notifications
- [ ] QR code check-in at events
- [ ] API endpoints for mobile app integration

---

## 📄 License

This project is open-source and available under the **MIT License**.

---

## 👨‍💻 Author & Support

**Project**: Student Event Management System  
**Version**: 1.0.0  
**Last Updated**: November 2025  
**Author**: Sivaranjan Dinath (23IT0471)  
**Institution**: Institute of Technology, University of Moratuwa

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/Dinath2002/student-event-management).

---

## 📞 Quick Reference

| Task | How To |
|------|--------|
| Start dev server | `php -S 127.0.0.1:8000 -t project/public` |
| Login as admin | Email: `admin@example.com`, Password: `Admin123` |
| Add event | Login as admin → "Add Event" navbar link |
| Edit event | Click ✏️ button on any event card |
| Delete event | Click ✏️ → "Delete" button on edit page |
| View registrations | Login as admin → "Registrations" navbar link |
| Seed events | `php project/scripts/seed_events.php` |
| Import MySQL schema | `mysql -u root -p < project/db/db_structure.sql` |

---

**Happy event managing! 🎉**
