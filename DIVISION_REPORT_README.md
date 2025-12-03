# Division Report - Auto-Detect User Division

## ✅ SUDAH SELESAI DIBUAT

Sistem Division Report yang otomatis mendeteksi divisi user dan hanya menampilkan data divisi tersebut.

---

## 🎯 Konsep

**User di Divisi A** → Buka `/report/division` → Otomatis muncul "A Report"  
**User di Divisi B** → Buka `/report/division` → Otomatis muncul "B Report"

**Access Control**: User HANYA bisa lihat report divisi sendiri (auto-detect dari database)

---

## 📂 File yang Dibuat:

1. ✅ **Controller**: `app/Http/Controllers/DivisionReportController.php`
   - Auto-detect divisi dari `Auth::user()` → `Employee::where('email')->division_id`
   - Filter data hanya untuk divisi user yang login
   - Method: `index()`, `getData()`, `getHoliday()`, `print()`

2. ✅ **Routes**: `routes/web.php`
   ```php
   Route::get('/division', [DivisionReportController::class, 'index']);
   Route::get('/division/getData', [DivisionReportController::class, 'getData']);
   Route::get('/division/getHoliday', [DivisionReportController::class, 'getHoliday']);
   Route::get('/division/print', [DivisionReportController::class, 'print']);
   ```

3. ✅ **View**: `resources/views/reports/division.blade.php`
   - Copy dari `spdr.blade.php` dengan modifikasi dinamis
   - Header: `{{ $division->title }} Report`
   - UI sama persis dengan SPDR/TMA report
   - Sudah pakai SweetAlert2 untuk alerts

4. ✅ **PDF Template**: `resources/views/reports/division-pdf.blade.php`
   - PDF untuk export report
   - Dinamis sesuai nama divisi

5. ✅ **Sidebar Menu**: `resources/views/layouts/app.blade.php`
   - Tambah menu "Division Report" di Reports section
   - Semua user bisa akses (auto-filter by division)

---

## 🔐 Access Control Logic

```php
// Di Controller
$user = Auth::user();
$employee = Employee::where('email', $user->email)->first();

// Abort jika tidak ada employee data
if (!$employee || !$employee->division_id) {
    abort(403, 'You are not assigned to any division');
}

// Hanya ambil data untuk divisi user
$divisionId = $employee->division_id;
$employees = Employee::where('division_id', $divisionId)
    ->whereHas('logs', ...)
    ->get();
```

---

## 📊 Cara Pakai:

### **Skenario: User SPDR**
1. Login sebagai user yang assigned ke divisi "SPDR"
2. Klik menu: **Reports > Division Report**
3. Otomatis muncul: **"SPDR Report"**
4. Pilih tanggal
5. Data yang muncul: HANYA employee dari divisi SPDR
6. Print PDF → file: `SPDR_Report_2024-12-04.pdf`

### **Skenario: User Marketing**
1. Login sebagai user yang assigned ke divisi "Marketing"
2. Klik menu: **Reports > Division Report**
3. Otomatis muncul: **"Marketing Report"**
4. Pilih tanggal
5. Data yang muncul: HANYA employee dari divisi Marketing
6. Print PDF → file: `Marketing_Report_2024-12-04.pdf`

---

## 🆚 Perbedaan dengan Report Lama (SPDR, BI, TMA):

| Aspek | Report Lama (SPDR/TMA) | Division Report (Baru) |
|-------|----------------------|----------------------|
| **Query Data** | Hardcoded nama role/category | Dinamis by `division_id` |
| **Access** | Filter by role name | Auto-detect user division |
| **Maintenance** | Harus tambah controller baru | Satu controller untuk semua |
| **Divisi Baru** | Perlu coding | Otomatis support |
| **UI** | Custom per report | Sama untuk semua divisi |

---

## 🚀 Testing:

1. **Test Auto-Detect**:
   - Login sebagai user dari divisi berbeda
   - Buka `/report/division`
   - Cek apakah header report sesuai divisi user

2. **Test Access Control**:
   - Login sebagai user divisi A
   - Cek data yang muncul hanya employee divisi A
   - Pastikan tidak bisa lihat data divisi lain

3. **Test Print PDF**:
   - Pilih tanggal yang ada datanya
   - Klik "Print PDF"
   - Cek PDF ter-generate dengan benar

---

## 📌 Notes:

- Report lama (SPDR, BI, TMA) **tetap bisa dipakai** (backward compatibility)
- Division Report ini **tambahan/alternatif** yang lebih fleksibel
- Kalau mau, bisa hapus report lama dan pakai Division Report saja
- Semua user otomatis punya akses Division Report (filter by division mereka)

---

## 🔧 Customization:

Kalau mau tambah filter subdivision:
```javascript
// Di division.blade.php, tambah dropdown subdivision
<select id="subdivisionFilter">
  @foreach($subdivisions as $sub)
    <option value="{{ $sub->id }}">{{ $sub->title }}</option>
  @endforeach
</select>

// Di getData(), tambah parameter subdivision_id
fetch(`/${mainRoute}/getData?date=${date}&subdivision_id=${subdivisionId}`)
```

Filter subdivision sudah tersedia di controller, tinggal aktifkan di view!

---

**Status**: ✅ **READY TO USE**

Silakan test dengan:
1. Login ke aplikasi
2. Buka menu: **Reports > Division Report**
3. Lihat apakah nama divisi sudah sesuai dengan divisi user
