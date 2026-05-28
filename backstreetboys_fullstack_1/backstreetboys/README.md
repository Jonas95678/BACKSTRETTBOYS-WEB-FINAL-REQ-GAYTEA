# 🎵 Backstreet Boys Website – Full Stack PHP/MySQL

## Project Structure

```
backstreetboys/
├── index.php                   ← Main website (pulls from DB)
├── config/
│   └── database.php            ← DB connection + helpers
├── includes/
│   ├── header.php
│   └── footer.php
├── uploads/
│   ├── members/                ← Member photos
│   └── albums/                 ← Album cover images
├── dashboard/
│   ├── login.php               ← Admin login
│   ├── logout.php
│   ├── index.php               ← Dashboard overview
│   ├── settings.php            ← Site settings
│   ├── layout_header.php       ← Shared sidebar/topbar
│   ├── layout_footer.php
│   ├── members/
│   │   ├── index.php           ← List all members
│   │   ├── create.php          ← Add member
│   │   ├── edit.php            ← Edit member
│   │   ├── delete.php          ← Delete member
│   │   └── toggle.php          ← Show/hide member
│   ├── tophits/
│   │   ├── index.php
│   │   ├── create.php          ← Add/Edit hit (shared form)
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── toggle.php
│   └── history/
│       ├── index.php
│       ├── create.php          ← Add/Edit event
│       ├── edit.php
│       ├── delete.php
│       └── toggle.php
└── database.sql                ← Full schema + seed data
```

---

## ⚙️ Installation

### 1. Set Up Database
```sql
-- In phpMyAdmin or MySQL CLI:
source /path/to/database.sql
```
Or import `database.sql` via phpMyAdmin → Import tab.

### 2. Configure Database Connection
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'backstreetboys_db');
```
Also update `SITE_URL` to match your server:
```php
define('SITE_URL', 'http://localhost/backstreetboys');
```

### 3. Set Upload Permissions
```bash
chmod 755 uploads/
chmod 755 uploads/members/
chmod 755 uploads/albums/
```

### 4. Place in Web Root
Copy the entire `backstreetboys/` folder to:
- XAMPP: `C:/xampp/htdocs/backstreetboys/`
- WAMP:  `C:/wamp64/www/backstreetboys/`
- Linux: `/var/www/html/backstreetboys/`

### 5. Access the Site
- **Website:** `http://localhost/backstreetboys/index.php`
- **Dashboard:** `http://localhost/backstreetboys/dashboard/login.php`
- **Login:** `admin` / `admin123`

---

## 🔧 How It Works

### Front-End ↔ Database Flow
```
Dashboard CRUD → MySQL DB → index.php reads DB → Website displays
```

1. You add/edit/delete a **Member** in the dashboard
2. It writes to the `members` table
3. `index.php` queries `SELECT * FROM members WHERE is_active=1`
4. The member appears (or disappears) on the live website

### Toggle Visibility
Each record has an `is_active` flag. Use the 👁️ toggle button in the dashboard to show/hide content on the website **without deleting it**.

### Display Order
Each table has a `display_order` column. Lower numbers appear first.

---

## 🗄️ Database Tables

| Table           | Purpose                          |
|-----------------|----------------------------------|
| `members`       | Band member profiles             |
| `top_hits`      | Songs with album/year/YouTube    |
| `history`       | Timeline events                  |
| `albums`        | Album reference data             |
| `site_settings` | Hero text, footer, about section |
| `admin_users`   | Dashboard login accounts         |

---

## 🔐 Change Admin Password
Run this in phpMyAdmin:
```sql
UPDATE admin_users
SET password = '$2y$10$YOUR_HASHED_PASSWORD'
WHERE username = 'admin';
```
Generate hash with PHP: `echo password_hash('yourpassword', PASSWORD_DEFAULT);`
