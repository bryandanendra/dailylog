# Default Admin User - Daily Log System

## 📋 Credential Admin Default

Setelah menjalankan seeder, akun admin berikut akan otomatis dibuat:

**Username**: `admin` ← **GUNAKAN INI UNTUK LOGIN!**  
**Password**: `admin123`

> ⚠️ **PENTING**: Login menggunakan **USERNAME**, bukan email!  
> Email admin: `admin@dailylog.com` (untuk referensi saja)

**Permissions**:
- ✅ `is_admin`: true (Full admin access)
- ✅ `can_approve`: true (Dapat approve daily log)

---

## 🔄 Cara Menjalankan Seeder

### Opsi 1: Fresh Migration + Seed (Recommended)
Menghapus semua data lama dan membuat ulang dengan data default:

```bash
php artisan migrate:fresh --seed
```

### Opsi 2: Seed Saja (Jika sudah migrate)
Hanya menjalankan seeder tanpa reset database:

```bash
php artisan db:seed
```

### Opsi 3: Seed Spesifik (UserSeeder saja)
Jika hanya ingin menjalankan UserSeeder:

```bash
php artisan db:seed --class=UserSeeder
```

---

## ⚠️ Catatan Penting

1. **Jangan lupa ganti password** setelah login pertama kali untuk keamanan.
2. Jika menjalankan `migrate:fresh --seed`, **SEMUA data akan dihapus** dan diganti dengan data default.
3. Seeder UserSeeder sudah ditambahkan ke `DatabaseSeeder`, jadi akan otomatis dijalankan.

---

## 🔧 Menambah User Lain

Jika ingin menambah user lain, edit file:
`database/seeders/UserSeeder.php`

Uncomment bagian user demo atau tambahkan user baru dengan format:

```php
User::create([
    'name' => 'Nama User',
    'email' => 'email@example.com',
    'password' => Hash::make('password123'),
    'is_admin' => false,
    'can_approve' => false,
]);
```

---

## 📝 Struktur User Table

Kolom yang tersedia di tabel `users`:
- `name` - Nama lengkap user
- `email` - Email (unique, untuk login)
- `password` - Password (hashed)
- `is_admin` - Boolean (true = admin, false = user biasa)
- `can_approve` - Boolean (true = bisa approve log, false = tidak bisa)
- `email_verified_at` - Timestamp verifikasi email (optional)
- `remember_token` - Token untuk "remember me" (auto)
- `created_at` - Timestamp dibuat
- `updated_at` - Timestamp terakhir diupdate

---

Semoga membantu! 🚀
