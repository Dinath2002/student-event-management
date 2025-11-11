Local development notes (quick start)

These instructions help you run the app locally with minimal setup.

What was changed to make the project runnable locally:
- Forms now post to public handler proxies (e.g. `/handle_register.php`) so they work under `project/public`.
- The admin add-event image input name is `image` and matches the controller.
- `project/config/db.php` now tries MySQL first. If MySQL cannot be reached it falls back to a local SQLite DB at `project/db/dev.sqlite` and creates compatible tables automatically. It also seeds a demo admin user if the DB is empty.

Run locally (PHP built-in server):

```bash
php -S 127.0.0.1:8000 -t project/public
```

Open http://127.0.0.1:8000 in your browser.

Default local admin (sqlite fallback):
- Email: admin@example.com
- Password: Admin123

Notes about the MySQL SQL file
- `project/db/db_structure.sql` is intended for MySQL but uses different column names (e.g. `banner_path`) than the PHP code (which expects `image_path` and other differences). If you want to run with MySQL, either:
  - Import the SQL and adapt the DB column names to match the PHP code, or
  - Update the PHP to match the SQL before importing.

Next steps I can take (tell me which):
- Convert `project/db/db_structure.sql` to match the PHP schema, and/or
- Update PHP to match the provided SQL, and/or
- Add an install script that creates a MySQL DB and seeds initial data.
