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

🎨 GitHub-Style Dark Theme (New UI)

The entire web application has been redesigned with a modern GitHub-inspired dark theme for better readability and consistency across all pages.

🧱 Improvements

Consistent dark background across every page (no white sections).

High-contrast white text for maximum legibility.

Polished event cards with visible details and hover glow.

Updated forms (login, register, home, events) with smooth shadows and uniform color scheme.

Enhanced navigation bar and footer to match GitHub’s color palette.

🌈 Color Palette
Element	          Color	          Description
Background	      #0d1117	        Primary dark background
Card	            #161b22	        Panels and forms
Text	            #c9d1d9	        Readable light gray text
Accent	          #2f81f7	        Buttons and links
Border	          #30363d	        Dividers and outlines

