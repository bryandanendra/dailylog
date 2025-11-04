# Cara Kerja Backup System

## Konsep Dasar

Backup system yang diterapkan adalah **REAL-TIME EXPORT**, bukan archive system.

### Alur Kerja:

```
User Click Download Button
         ↓
    Route Handler
         ↓
  BackupController Method
         ↓
  Query Database (Eloquent)
         ↓
  Transform Data (dengan relasi)
         ↓
  Generate CSV Format
         ↓
  Stream ke Browser
         ↓
  File Downloaded
```

## Detail Proses

### 1. **Tidak Ada Penyimpanan di Server**
- File CSV **TIDAK disimpan** di server
- Data langsung di-stream ke browser menggunakan `Response::stream()`
- Setelah download selesai, tidak ada file yang tersisa di server

### 2. **Query Real-Time**
```php
// Contoh: Export Employees
$data = Employee::with(['division', 'subDivision', 'role', 'position'])
    ->get()
    ->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'division_name' => $item->division ? $item->division->title : '',
            // ... dst
        ];
    })
    ->toArray();
```

**Penjelasan:**
- `Employee::with([...])` → Load data employee beserta relasinya (eager loading)
- `->get()` → Ambil semua data dari database
- `->map()` → Transform data, include nama relasi (bukan hanya ID)
- `->toArray()` → Convert ke array untuk CSV

### 3. **Generate CSV**
```php
private function generateCSV($data, $filename)
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($data) {
        $file = fopen('php://output', 'w');
        
        // Add BOM for UTF-8 (agar Excel bisa baca dengan benar)
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if (!empty($data)) {
            // Write header (nama kolom)
            fputcsv($file, array_keys($data[0]));
            
            // Write data (isi baris per baris)
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
        }
        
        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}
```

**Penjelasan:**
- `php://output` → Stream langsung ke output buffer
- BOM (Byte Order Mark) → Agar Excel mengenali UTF-8
- `fputcsv()` → Format CSV otomatis (handle comma, quotes, dll)

### 4. **Export All (ZIP)**
Untuk export all, prosesnya sedikit berbeda:
```php
1. Create ZIP file di storage/app/public/
2. Loop semua tabel
3. Generate CSV content untuk setiap tabel
4. Add ke ZIP file
5. Close ZIP
6. Download ZIP
7. Delete ZIP file setelah download (deleteFileAfterSend)
```

## Kenapa Beberapa Download Tidak Bekerja?

### Masalah yang Ditemukan:

#### 1. **Inkonsistensi Nama Field**
**Masalah:** Controller menggunakan `name` tapi model menggunakan `title`

**Contoh Error:**
```php
// SALAH ❌
'division_name' => $item->division->name  // Field 'name' tidak ada!

// BENAR ✅
'division_name' => $item->division->title  // Field 'title' ada di model
```

**Tabel yang menggunakan `title`:**
- divisions
- sub_divisions
- roles
- positions
- categories
- tasks
- builders
- dwelings
- status
- work_status
- holidays
- offwork
- notifications

**Tabel yang menggunakan `name`:**
- employees
- users
- leave_types

#### 2. **Missing Relationships**
Jika relationship tidak didefinisikan di model, akan error saat `->with()`

#### 3. **Field yang Tidak Ada**
Mengakses field yang tidak ada di database akan menyebabkan error

## Solusi yang Diterapkan

### ✅ Fix 1: Perbaiki Field Names
Sudah diperbaiki di BackupController:
- Gunakan `title` untuk master data tables
- Gunakan `name` untuk employee, user, leave_types

### ✅ Fix 2: Include Semua Field yang Ada
Tambahkan field yang sebelumnya terlewat:
- `archive` field
- `username` di employees
- `join_date`, `is_admin`, dll di employees

### ✅ Fix 3: Safe Access dengan Null Check
```php
'division_name' => $item->division ? $item->division->title : ''
```
Jika relasi null, return empty string, tidak error.

## Testing

### Cara Test Setiap Download:

1. **Login ke aplikasi**
2. **Akses `/backup`**
3. **Test satu per satu:**
   - Klik download button
   - Check apakah file ter-download
   - Buka file CSV di Excel/LibreOffice
   - Verify data benar

### Jika Masih Error:

#### Check Laravel Log:
```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50
```

#### Check Browser Console:
- F12 → Network tab
- Klik download button
- Check response (200 OK atau error?)

#### Common Errors:

**Error 500 - Internal Server Error**
- Biasanya karena field tidak ada
- Check `storage/logs/laravel.log`

**Error 404 - Not Found**
- Route tidak terdaftar
- Check `routes/web.php`

**Download tapi file kosong**
- Query tidak return data
- Check apakah ada data di database

**File corrupt / tidak bisa dibuka**
- Biasanya karena error di tengah generate
- Check log untuk error

## Keuntungan Real-Time Export

✅ **Always Up-to-Date:** Data selalu terbaru dari database
✅ **No Storage Used:** Tidak memakan space server
✅ **No Cleanup Needed:** Tidak perlu hapus file lama
✅ **Secure:** File tidak tersimpan di server

## Kekurangan Real-Time Export

❌ **Slow untuk Data Besar:** Jika data jutaan rows, akan lambat
❌ **Server Load:** Query besar bisa membebani server
❌ **No Resume:** Jika gagal, harus download ulang

## Rekomendasi untuk Production

Jika data sudah besar (>100k rows per tabel):

1. **Add Pagination/Chunking:**
```php
Employee::chunk(1000, function($employees) {
    // Process 1000 at a time
});
```

2. **Add Queue System:**
```php
// Generate di background
dispatch(new GenerateBackupJob($table));
```

3. **Add Progress Indicator:**
```javascript
// Show progress bar saat download
```

4. **Add Date Range Filter:**
```php
// Export hanya data bulan ini, dll
```

## Kesimpulan

Backup system ini adalah **REAL-TIME EXPORT** yang:
- Query database saat user klik download
- Transform data dengan relasi
- Stream langsung ke browser
- Tidak simpan file di server

Error yang terjadi sudah diperbaiki dengan menyesuaikan nama field (`title` vs `name`).
