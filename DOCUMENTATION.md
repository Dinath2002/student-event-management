# Student Event Management System
## Complete Documentation

---

## PAGE 1: PROJECT OVERVIEW & INTRODUCTION

### Project Title
**Student Event Management System (SEM)**

### Institution
**Institute of Technology, University of Moratuwa**

### Author
**Sivaranjan Dinath**  
**Registration Number: 23IT0471**

### Project Date
**November 2025**

---

### 1.1 Executive Summary

The Student Event Management System (SEM) is a modern, secure web application designed to streamline the management and coordination of university events. The platform enables students to discover, view details, and register for various academic and extracurricular events such as workshops, hackathons, seminars, and conferences.

Administrators have full control over event creation, editing, deletion, and can view comprehensive registration data for better event planning and coordination.

### 1.2 Objectives

- **Primary**: Create an intuitive, responsive platform for event management in a university setting
- **Secondary**: Implement secure authentication and role-based access control
- **Tertiary**: Provide a scalable solution that works with both MySQL (production) and SQLite (development)

### 1.3 Key Features

#### For Students
- Browse all upcoming events with detailed information
- View event schedules, venues, organizers, and descriptions
- Register for events with optional student ID and contact information
- Secure login and registration with password encryption
- Responsive mobile-friendly interface

#### For Administrators
- Create new events with title, date, venue, organizer, and description
- Upload and manage event banners/images (JPG, PNG, WebP)
- Edit event details and replace banners
- Delete events and manage associated registrations
- View all student registrations in a comprehensive table
- Admin buttons integrated directly in the events list for quick access

### 1.4 Technical Highlights

- **Backend**: PHP 8.x with PDO for database abstraction
- **Frontend**: Bootstrap 5.3 with GitHub-inspired dark theme
- **Database**: MySQL/MariaDB (or SQLite fallback for local development)
- **Security**: Password hashing (bcrypt), prepared statements, session-based authentication
- **Architecture**: MVC-like separation (controllers, views, configuration)

---

## PAGE 2: SYSTEM ARCHITECTURE & DATABASE DESIGN

### 2.1 Architecture Overview

The application follows a layered architecture:

```
┌─────────────────────────────────────────┐
│         Presentation Layer              │
│  (HTML, Bootstrap, CSS, JavaScript)     │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│      Application Layer (Controllers)    │
│  (handle_login.php, handle_add_event...)│
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│       Configuration & Auth Layer        │
│  (db.php, auth.php, Session Management)│
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│         Database Layer (PDO)            │
│  (MySQL or SQLite via PDO abstraction)  │
└─────────────────────────────────────────┘
```

### 2.2 Database Schema

#### Users Table
Stores user accounts for both students and administrators.

| Column | Type | Description |
|--------|------|-------------|
| user_id | INT UNSIGNED PK | Auto-increment primary key |
| name | VARCHAR(100) | Full name of the user |
| email | VARCHAR(150) UNIQUE | Email address (unique) |
| password | VARCHAR(255) | Bcrypt hashed password |
| role | ENUM('student','admin') | User role (default: 'student') |
| student_id | VARCHAR(50) NULL | Optional student ID |
| created_at | TIMESTAMP | Account creation timestamp |

#### Events Table
Stores event information created by administrators.

| Column | Type | Description |
|--------|------|-------------|
| event_id | INT UNSIGNED PK | Auto-increment primary key |
| title | VARCHAR(255) | Event title |
| date | DATE | Event date |
| time | TIME NULL | Event time (optional) |
| venue | VARCHAR(255) | Event location/venue |
| organizer | VARCHAR(255) | Organizer name (department/society) |
| description | TEXT | Detailed event description |
| image_path | VARCHAR(255) NULL | Path to event banner image |
| created_by | INT UNSIGNED NULL | Admin user ID who created event |
| created_at | TIMESTAMP | Event creation timestamp |

#### Registrations Table
Tracks student registrations for events.

| Column | Type | Description |
|--------|------|-------------|
| reg_id | INT UNSIGNED PK | Auto-increment primary key |
| user_id | INT UNSIGNED FK | Foreign key to users table |
| event_id | INT UNSIGNED FK | Foreign key to events table |
| student_id | VARCHAR(50) NULL | Student ID (optional override) |
| contact_no | VARCHAR(50) NULL | Contact phone number |
| created_at | TIMESTAMP | Registration timestamp |
| UNIQUE(user_id, event_id) | Constraint | Prevents duplicate registrations |

### 2.3 File Structure

```
project/
├── config/
│   ├── db.php              # Database config & PDO connection
│   └── auth.php            # Auth functions & helpers
├── controllers/
│   ├── handle_login.php
│   ├── handle_register.php
│   ├── handle_add_event.php
│   ├── handle_edit_event.php
│   ├── handle_delete_event.php
│   └── handle_event_register.php
├── db/
│   ├── db_structure.sql    # MySQL schema
│   └── dev.sqlite          # Local SQLite DB (auto-created)
├── includes/
│   ├── header.php          # Navigation & page header
│   └── footer.php          # Page footer
├── public/
│   ├── index.php, login.php, register.php, etc.
│   ├── assets/
│   │   ├── css/style.css
│   │   ├── img/events/     # Uploaded event images
│   │   └── js/script.js
│   └── handle_*.php        # Public proxies to controllers
└── scripts/
    └── seed_events.php     # Database seeding script
```

### 2.4 Data Flow Diagram

```
User Request
    ↓
Public Page (HTML Form)
    ↓
Form POST to /handle_*.php
    ↓
Controller (Process & Validate)
    ↓
Database Query (PDO Prepared Statements)
    ↓
Database Execution
    ↓
Response/Redirect
    ↓
User Sees Result
```

---

## PAGE 3: FEATURES & FUNCTIONALITY

### 3.1 User Authentication Flow

#### Registration Process
1. User navigates to `/register.php`
2. Fills in name, email, password, optional student ID
3. Form submits to `/handle_register.php`
4. Controller validates input
5. Email checked for duplicates
6. Password hashed using `password_hash(PASSWORD_DEFAULT)`
7. User record inserted into database
8. Success message shown, user redirected to login

#### Login Process
1. User navigates to `/login.php`
2. Enters email and password
3. Form submits to `/handle_login.php`
4. Controller queries database for user by email
5. `password_verify()` checks password against hash
6. On success: user data stored in `$_SESSION['user']`
7. User redirected to `/home.php`
8. On failure: error message shown, user stays on login

#### Logout
1. User clicks "Logout" button
2. Navigates to `/logout.php`
3. Session data cleared with `session_destroy()`
4. User redirected to `/login.php`

### 3.2 Event Management (Admin Features)

#### Create Event
- Admin clicks "Add Event" in navbar
- Fills form: title, date, venue, organizer, description, optional banner
- Form submits to `/handle_add_event.php`
- Image validation: type (JPG/PNG/WebP), size (<2MB)
- Image saved to `/assets/img/events/` with unique filename
- Event record inserted with image path
- Admin redirected to events page with success message

#### Edit Event
- Admin clicks ✏️ button on any event card
- Navigated to `/admin_edit_event.php?id=<event_id>`
- Form pre-populated with existing event data
- Admin can change any field and optionally upload new banner
- Old image automatically deleted if replaced
- Form submits to `/handle_edit_event.php`
- Event record updated in database
- Admin redirected to events page with success message

#### Delete Event
- Admin clicks ✏️ on event → sees "Delete" button
- Clicks "Delete" → taken to `/admin_delete_event.php?id=<event_id>`
- Confirmation page shown with event title and date
- Admin clicks "Yes, delete" to confirm
- Form POSTs to `/handle_delete_event.php`
- Event image file deleted from disk
- All associated registrations deleted (cascade)
- Event record deleted from database
- Admin redirected to events page with success message

### 3.3 Student Features

#### Browse Events
- Any user (logged in or not) can view `/events.php`
- Events displayed in cards with title, date, venue, organizer, description
- Event banners shown as card thumbnails if present

#### Event Registration
- Logged-in students click "Register" on any event
- Taken to `/event_register.php?id=<event_id>`
- Form shows event details and registration fields
- Optional fields: student ID, contact number
- Form submits to `/handle_event_register.php`
- Registration checked for duplicates (unique constraint)
- New registration record inserted
- Student redirected with success message

#### View Registrations (Admin)
- Admins click "Registrations" in navbar
- Taken to `/admin_registrations.php`
- Table shows all registrations organized by event
- Columns: Event, Date, Venue, Student Name, Student ID, Email, Registration Time
- Can be easily exported or printed

### 3.4 Security Measures

- **Password Hashing**: All passwords use PHP's `password_hash()` with default algorithm (bcrypt)
- **SQL Injection Prevention**: All queries use PDO prepared statements with parameterized queries
- **Session Management**: User authentication via `$_SESSION['user']` array
- **Role-Based Access Control**: Admin pages check `is_admin()` function before allowing access
- **Input Validation**: All form inputs validated and sanitized before processing
- **File Upload Security**: Only allowed MIME types accepted; size limit enforced; unique filenames generated

---

## PAGE 4: INSTALLATION & DEPLOYMENT

### 4.1 Prerequisites

- **PHP**: Version 8.0 or higher
- **Database**: MySQL 5.7+, MariaDB, or SQLite (auto-created)
- **Web Server**: Apache, Nginx, or PHP built-in server
- **Browser**: Any modern browser (Chrome, Firefox, Safari, Edge)

### 4.2 Local Development Setup

#### Quick Start (No MySQL needed)

```bash
# 1. Navigate to project directory
cd student-event-management

# 2. Start PHP built-in server
php -S 127.0.0.1:8000 -t project/public

# 3. Open browser
# http://127.0.0.1:8000

# 4. Login with default admin
# Email: admin@example.com
# Password: Admin123
```

The application will automatically create a local SQLite database at `project/db/dev.sqlite` on first run.

#### MySQL Setup (Production)

```bash
# 1. Import database schema
mysql -u root -p < project/db/db_structure.sql

# 2. Update credentials in project/config/db.php
# Or set environment variables:
export DB_HOST=127.0.0.1
export DB_NAME=student_events_db
export DB_USER=root
export DB_PASS=your_password

# 3. Seed sample events (optional)
php project/scripts/seed_events.php
```

### 4.3 Production Deployment

#### Apache Configuration

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

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name events.example.com;
    root /var/www/sem/project/public;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        include fastcgi_params;
    }
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

#### Security Best Practices

- Change default admin password immediately
- Use HTTPS/SSL certificates
- Set environment variables for database credentials
- Regular database backups
- Keep PHP and dependencies updated
- Implement rate limiting for login attempts
- Monitor access logs for suspicious activity

### 4.4 Troubleshooting

| Issue | Solution |
|-------|----------|
| "Database connection failed" | Check SQLite file exists at `project/db/dev.sqlite` or verify MySQL credentials |
| "Access denied for user 'root'" | Use SQLite fallback or update `DB_*` variables in `project/config/db.php` |
| "Event image not uploading" | Ensure `project/public/assets/img/events/` is writable; check file size < 2MB |
| "Can't register for event" | Ensure logged in; verify event exists in database |

---

## PAGE 5: CONCLUSION & FUTURE ENHANCEMENTS

### 5.1 Project Summary

The Student Event Management System provides a comprehensive solution for university event coordination. With its modern dark-themed interface, robust security measures, and flexible database support, the platform is suitable for both small-scale university departments and larger institutional deployments.

**Key Achievements:**
- ✅ Complete CRUD functionality for events
- ✅ Secure user authentication with role-based access
- ✅ Responsive, user-friendly interface
- ✅ SQLite fallback for easy local development
- ✅ Production-ready MySQL support
- ✅ Comprehensive documentation
- ✅ Zero compile/lint errors
- ✅ Full smoke testing completed

### 5.2 Testing & Verification

All major workflows have been tested and verified:
- ✅ User registration and login
- ✅ Event creation, editing, and deletion
- ✅ Event registration by students
- ✅ Admin registrations viewing
- ✅ Image upload and management
- ✅ Session management and logout
- ✅ Error handling and validation

### 5.3 Future Enhancement Opportunities

1. **Email Notifications**: Send registration confirmations and event reminders to students
2. **Advanced Search**: Full-text search and filtering by category/date range
3. **Admin Dashboard**: Statistics on event attendance, popular events, registrations over time
4. **User Profiles**: Student profile page with registration history and preferences
5. **Event Categories**: Organize events by type (technical, sports, cultural, etc.)
6. **QR Code Check-in**: Generate QR codes for events; quick check-in at venues
7. **Mobile App**: Native Android/iOS applications for event browsing
8. **Two-Factor Authentication**: Enhanced security with OTP or authenticator apps
9. **Event Feedback**: Post-event surveys and rating system
10. **Calendar Integration**: Export events to Google Calendar, iCal format

### 5.4 Maintenance & Support

**Regular Maintenance Tasks:**
- Monitor server logs for errors
- Regular database backups (daily or weekly)
- Update PHP and dependencies quarterly
- Review access logs for suspicious activity
- Clean up old uploaded images periodically
- Update admin passwords regularly

**Support Resources:**
- Project Repository: https://github.com/Dinath2002/student-event-management
- Issue Tracking: Use GitHub Issues for bug reports
- Documentation: See README.md and DEV_NOTES.md

### 5.5 Author Information

**Developer**: Sivaranjan Dinath  
**Registration Number**: 23IT0471  
**Institution**: Institute of Technology, University of Moratuwa  
**Project Period**: November 2025  
**Version**: 1.0.0

---

### Contact & Contributions

For questions, issues, or feature requests, please contact the developer or create an issue on the GitHub repository.

---

**End of Documentation**

*This 5-page document provides a comprehensive overview of the Student Event Management System, covering architecture, features, installation, and deployment.*
