# AstonCV (DG1IAD Portfolio 3)

A simple PHP + MySQL CV database website that implements the core assessment requirements:

- Public: list CVs, view CV details, search CVs, register
- Registered users: login, update own CV, logout
- Security features: authentication, authorization, password hashing, form validation, SQL injection protection (prepared statements), XSS output escaping, CSRF tokens, secure session cookie settings

## 1. Setup

1. Create and import database schema:
   - `mysql -u root -p < schema.sql`
2. Configure DB credentials in `config.php` (or via environment variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).
3. Serve the project directory with PHP, for example:
   - `php -S 127.0.0.1:8000`
4. Open:
   - `http://127.0.0.1:8000/index.php`

## 2. Main Files

- `index.php` - list and search CVs
- `view_cv.php` - CV details page
- `register.php` - account registration
- `login.php` - user login
- `edit_cv.php` - user CV update page
- `logout.php` - secure logout
- `includes/` - shared auth, CSRF, DB, and layout components
- `schema.sql` - database creation script
