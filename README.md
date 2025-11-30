# Daily Log System

A web-based Daily Log System built with Laravel.

## Prerequisites

Before you begin, ensure you have the following installed on your machine:
- **PHP** >= 8.1
- **Composer**
- **Node.js** & **NPM**
- **MySQL** or **MariaDB**

## Installation Guide

Follow these steps to set up the project locally:

### 1. Clone the Repository
```bash
git clone <repository-url>
cd dailylog
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```

Open the `.env` file and configure your database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dailylog
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Database Migrations & Seeders
This will create the necessary tables and populate them with initial data (including the default admin account).
```bash
php artisan migrate:fresh --seed
```

> **Note:** The default seeder creates an admin account:
> - **Email:** `alberta@gmail.com`
> - **Password:** `asc123`

### 6. Run the Application
Start the local development server:
```bash
php artisan serve
```

Access the application at: `http://127.0.0.1:8000`

## Troubleshooting

### Database Connection Errors
Ensure your MySQL server is running and the credentials in `.env` are correct. You may need to create the database manually if it doesn't exist:
```sql
CREATE DATABASE dailylog;
```

### Permission Issues (Linux/Mac)
If you encounter permission errors with storage logs:
```bash
chmod -R 775 storage bootstrap/cache
```
