# Laravel Project Installer Package

A clean and easy-to-use Laravel installation wizard with environment setup, database import, admin user creation, and project locking system — perfect for CodeCanyon and distributable Laravel apps.

---

## 🚀 Features

- Welcome screen  
- System requirements checker  
- .env environment setup form  
- Database connection test & SQL file import (no migration)  
- Admin user creation  
- Installed lock file system  
- UI-based installation wizard  
- Middleware protection for installed state  
- Publishable views & assets  

---

## 📂 Folder Structure Overview

```
packages/
└── amdadulshakib/
    └── Installer/
      └── src
          ├── Controllers/
          ├── Middleware/
          ├── routes/
          ├── resources/
          │   └── views/
          ├── routes/
          │   └── web.php
          ├── helper.php
          ├── InstallerServiceProvider.php
          └── composer.json
```

---

## 🧩 Installation

### Option 1: Use from GitHub

Add this to your project's `composer.json`:

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/amdadulshakib/laravel-project-installer"
  }
]
```

Then run:

```bash
composer require amdadulshakib/installer
```

### Option 2: Use Locally (For development)

Add this to your `composer.json`:

```json
"repositories": [
  {
    "type": "path",
    "url": "packages/amdadulshakib/Installer"
  }
]
```

Then install:

```bash
composer require amdadulshakib/installer:@dev
```

---

## ⚙️ Publish Assets and Views

```bash
php artisan vendor:publish --tag=installer-views
php artisan vendor:publish --tag=installer-assets
```

---

## 🛡️ Middleware

This package auto-registers a middleware `install.lock` that prevents accessing the app before installation.

You can apply globally using:

```php
$this->app['router']->pushMiddlewareToGroup('web', \amdadulshakib\Installer\Middleware\IsNotInstalled::class);
```

Middleware Logic:

- If `/storage/installed.lock` does **not** exist, it redirects to `/install`  
- After successful installation, the lock file is created  
- Trying to access `/install` again after installation is **blocked**

---

## 🧪 SQL Import Instead of Migrations

The package does **not** use migrations. Instead, it imports `database.sql` from:

```
packages/amdadulshakib/Installer/database/database.sql
```

Place your pre-built SQL dump file here to set up the database during installation.

---

## ✍️ Usage Steps

1. Visit `/install`  
2. Go through:
   - Welcome  
   - Requirements check  
   - Environment setup  
   - Database setup (will import `database.sql`)  
   - Admin user creation  
   - Finish  
3. After installation, a `storage/installed.lock` file will be created  
4. Any access to `/install` after installation will be **blocked**

---

## 🎨 Styling

To include CSS or JS:

1. Place files in `packages/amdadulshakib/Installer/public/`
2. Publish them using:

```bash
php artisan vendor:publish --tag=installer-assets
```

3. Use in views:

```blade
<link rel="stylesheet" href="{{ asset('vendor/installer/css/installer.css') }}">
<script src="{{ asset('vendor/installer/js/installer.js') }}"></script>
```

---

## 📦 License

Open-source and customizable for commercial or private Laravel projects.

---

## ❤️ Author

**Amdadul Shakib**  
[GitHub Profile](https://github.com/amdadulshakib)