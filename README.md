# Student Event Management (PHP + MySQL)

A simple, secure web app for managing university events. Students can browse events and register; admins can create, edit, and delete events and view participants.

> **Login is the homepage.** Unauthenticated users see the login screen first; successful login redirects to `events.php`.

---

## Features

- **Responsive UI** (Bootstrap 5)
- **Auth**: Register, Login, Logout (PHP sessions, password_hash/verify)
- **Events**: List with search, detail page
- **Admin**: Event CRUD (Create/Read/Update/Delete)
- **Registrations**: Students register for events; admins can view participants
- **Secure DB access** via PDO prepared statements

---

## Tech Stack

- **Frontend**: HTML5, CSS3 (Bootstrap), vanilla JS for client-side validation
- **Backend**: PHP 8.x
- **Database**: MySQL/MariaDB
- **Server (dev)**: PHP built-in server

---

## Directory Structure

