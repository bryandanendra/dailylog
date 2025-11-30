# 🎨 Quick Guide - Cara Mengubah Warna (Color Palette)

## 📍 Lokasi File Utama
**File**: `/Users/a1234/Documents/CODING/dailylog/resources/views/layouts/app.blade.php`

Di dalam file ini, ada section `<style>` (baris 10-292) yang berisi SEMUA pengaturan warna.

---

## 🔵 1. NAVBAR / HEADER (Biru di Atas)

**Lokasi**: Baris **71-81**

```css
.header {
    background-color: #1976d2;  /* ← UBAH WARNA NAVBAR DI SINI */
    color: white;
    padding: 15px 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
    transition: all 0.3s;
}
```

**Contoh Warna Lain:**
- Hijau: `#2e7d32`
- Ungu: `#6a1b9a`
- Merah: `#d84315`
- Hitam: `#212121`

---

## 🔷 2. SIDEBAR (Biru Muda di Kiri)

**Lokasi**: Baris **11-20**

```css
.sidebar {
    min-height: 100vh;
    background-color: #e3f2fd;  /* ← UBAH WARNA SIDEBAR DI SINI */
    width: 200px;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    transition: all 0.3s;
}
```

**Contoh Warna Lain:**
- Hijau Muda: `#e8f5e9`
- Ungu Muda: `#f3e5f5`
- Orange Muda: `#fbe9e7`
- Abu-abu Muda: `#f5f5f5`

---

## 🔗 3. SIDEBAR LINKS (Navigasi)

### Link Normal
**Lokasi**: Baris **30-36**

```css
.sidebar .nav-link {
    color: #1976d2;  /* ← WARNA TEXT LINK */
    padding: 8px 16px;
    border-radius: 0;
    transition: all 0.3s;
    font-size: 0.85rem;
}
```

### Link Hover (saat di-hover)
**Lokasi**: Baris **37-40**

```css
.sidebar .nav-link:hover {
    background-color: #bbdefb;  /* ← WARNA BACKGROUND SAAT HOVER */
    color: #0d47a1;  /* ← WARNA TEXT SAAT HOVER */
}
```

### Link Active (halaman aktif)
**Lokasi**: Baris **41-44**

```css
.sidebar .nav-link.active {
    background-color: #2196f3;  /* ← WARNA BACKGROUND LINK AKTIF */
    color: white;  /* ← WARNA TEXT LINK AKTIF */
}
```

---

## 🔘 4. BUTTONS (Tombol)

Untuk button di search box dan form, ada di **setiap halaman individual**.

**Contoh di**: `resources/views/offwork/holiday.blade.php` (setelah baris 82)

```css
.search-data button {
    background: #0d6efd;  /* ← WARNA BUTTON */
    color: white;
    border: none;
    padding: 0.375rem 0.75rem;
    cursor: pointer;
}

.search-data button:hover {
    background: #0b5ed7;  /* ← WARNA BUTTON SAAT HOVER */
}
```

**Files yang punya button style:**
- `resources/views/offwork/holiday.blade.php`
- `resources/views/offwork/leave.blade.php`
- `resources/views/offwork/index.blade.php`
- `resources/views/tables/*.blade.php` (semua file di folder tables)

---

## 📄 5. BACKGROUND HALAMAN

**Lokasi di app.blade.php**: Baris **247-250**

```css
.content-area {
    padding: 20px;
    background-color: #f8f9fa;  /* ← WARNA BACKGROUND KONTEN */
    min-height: calc(100vh - 140px);
}
```

**Dan juga baris 289-291:**

```css
body {
    background-color: #f8f9fa;  /* ← WARNA BACKGROUND BODY */
}
```

---

## 🎨 PAKET WARNA SIAP PAKAI

### 🟢 TEMA HIJAU
```css
/* Navbar */
.header { background-color: #2e7d32; }

/* Sidebar */
.sidebar { background-color: #e8f5e9; }
.sidebar .nav-link { color: #2e7d32; }
.sidebar .nav-link:hover { background-color: #c8e6c9; color: #1b5e20; }
.sidebar .nav-link.active { background-color: #4caf50; }

/* Buttons */
.search-data button { background: #4caf50; }
.search-data button:hover { background: #45a049; }
```

### 🟣 TEMA UNGU
```css
/* Navbar */
.header { background-color: #6a1b9a; }

/* Sidebar */
.sidebar { background-color: #f3e5f5; }
.sidebar .nav-link { color: #6a1b9a; }
.sidebar .nav-link:hover { background-color: #e1bee7; color: #4a148c; }
.sidebar .nav-link.active { background-color: #9c27b0; }

/* Buttons */
.search-data button { background: #9c27b0; }
.search-data button:hover { background: #8e24aa; }
```

### 🟠 TEMA ORANGE
```css
/* Navbar */
.header { background-color: #d84315; }

/* Sidebar */
.sidebar { background-color: #fbe9e7; }
.sidebar .nav-link { color: #d84315; }
.sidebar .nav-link:hover { background-color: #ffccbc; color: #bf360c; }
.sidebar .nav-link.active { background-color: #ff5722; }

/* Buttons */
.search-data button { background: #ff5722; }
.search-data button:hover { background: #f4511e; }
```

### ⚫ TEMA DARK
```css
/* Navbar */
.header { background-color: #212121; }

/* Sidebar */
.sidebar { background-color: #424242; }
.sidebar .nav-link { color: #ffffff; }
.sidebar .nav-link:hover { background-color: #616161; color: #ffffff; }
.sidebar .nav-link.active { background-color: #757575; }

/* Buttons */
.search-data button { background: #424242; }
.search-data button:hover { background: #212121; }
```

---

## 📝 CARA MENGUBAH WARNA

### Step-by-Step:

1. **Buka file**: `resources/views/layouts/app.blade.php`

2. **Cari section `<style>`** (baris 10-292)

3. **Pilih bagian yang ingin diubah:**
   - Navbar → baris 71
   - Sidebar → baris 13
   - Sidebar Links → baris 30-44
   - Background → baris 247

4. **Ubah nilai warna** (format `#RRGGBB`)

5. **Save file** (Ctrl+S atau Cmd+S)

6. **Refresh browser** dengan **hard refresh**:
   - Windows: `Ctrl + Shift + R` atau `Ctrl + F5`
   - Mac: `Cmd + Shift + R`

---

## 🔍 TOOLS UNTUK MEMILIH WARNA

- **HTML Color Picker**: https://htmlcolorcodes.com/
- **Adobe Color**: https://color.adobe.com/create/color-wheel
- **Coolors**: https://coolors.co/
- **Material Colors**: https://materialui.co/colors/
- **Color Hunt**: https://colorhunt.co/

---

## ⚠️ TIPS PENTING

1. **Konsistensi**: Gunakan warna yang saling matching
2. **Contrast**: Pastikan text terlihat jelas di background
3. **Accessibility**: Hindari kombinasi warna yang sulit dibaca
4. **Testing**: Test di berbagai halaman setelah mengubah warna
5. **Backup**: Simpan nilai warna lama sebelum mengubah

---

## 📋 CHECKLIST SETELAH UBAH WARNA

- [ ] Navbar (header) 
- [ ] Sidebar background
- [ ] Sidebar links (normal, hover, active)
- [ ] Buttons di search box
- [ ] Background konten
- [ ] Test di semua halaman utama
- [ ] Test readability (keterbacaan)
- [ ] Hard refresh browser

---

## 💡 CONTOH PRAKTIS

Misalnya Anda ingin **navbar hijau** dan **sidebar hijau muda**:

1. Buka: `resources/views/layouts/app.blade.php`
2. Baris **71**: Ubah `#1976d2` → `#2e7d32`
3. Baris **13**: Ubah `#e3f2fd` → `#e8f5e9`
4. Baris **30**: Ubah `#1976d2` → `#2e7d32`
5. Baris **38**: Ubah `#bbdefb` → `#c8e6c9`
6. Baris **42**: Ubah `#2196f3` → `#4caf50`
7. Save dan hard refresh!

---

Semoga membantu! 🎨✨
