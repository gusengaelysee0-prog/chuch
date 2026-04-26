# ADEPR Nyanza Backend (PHP + MySQL)

## 1) Database setup

1. Create/import schema:
   - Run `backend/sql/schema.sql` in phpMyAdmin or MySQL client.
2. Ensure DB name is `chuch_db`.

## 2) Connection

Edit credentials in `backend/config/database.php` if needed:
- host
- port
- dbName
- username
- password

## 3) Create first admin (one-time)

Since no default plaintext password is stored, seed the first admin once:

`POST /chuch/backend/api/admin/seed-admin.php`

JSON body:
```json
{
  "username": "superadmin",
  "password": "admin123"
}
```

## 4) Login

Use:
- `admin/login.php` (admin panel login)
- Website login redirects to `admin/dashboard.php`

## 5) Protected admin pages

- `admin/dashboard.php`
- `admin/images.php`
- `admin/videos.php`
- `admin/updates.php`
- `admin/notifications.php`
- `admin/admins.php`
- `admin/messages.php`

All are guarded by session checks in `backend/middleware/auth.php`.

## 6) Upload folders

These are created automatically when uploading:
- `uploads/images`
- `uploads/updates`
