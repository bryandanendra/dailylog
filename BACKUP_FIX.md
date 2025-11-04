# Backup Feature - Bug Fix

## Problem
Error saat download beberapa tabel:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'dailylog.offworks' doesn't exist
```

## Root Cause
Laravel secara default menggunakan **pluralized table names** untuk model. Contoh:
- Model `Offwork` → Laravel mencari tabel `offworks` (dengan 's')
- Model `WorkStatus` → Laravel mencari tabel `work_statuses`

Tapi di database, nama tabel yang sebenarnya adalah:
- `offwork` (singular, tanpa 's')
- `work_status` (singular dengan underscore)

## Solution
Tambahkan property `$table` di model untuk menentukan nama tabel secara eksplisit.

### Fixed Models:

#### 1. Offwork Model
```php
class Offwork extends Model
{
    protected $table = 'offwork'; // ✅ Tambahkan ini
    
    protected $fillable = [
        'title',
        'date',
        'leave_type',
        'employee_id',
        'description',
        'status',
        'archive'
    ];
    // ...
}
```

#### 2. WorkStatus Model
```php
class WorkStatus extends Model
{
    protected $table = 'work_status'; // ✅ Tambahkan ini
    
    protected $fillable = [
        'title',
        'description',
        'archive'
    ];
    // ...
}
```

### Models yang Sudah Benar (Tidak Perlu Diubah):

#### Status Model
```php
class Status extends Model
{
    protected $table = 'status'; // ✅ Sudah ada
    // ...
}
```

#### TimeCutoff Model
```php
class TimeCutoff extends Model
{
    protected $table = 'time_cutoff'; // ✅ Sudah ada
    // ...
}
```

## Table Name Conventions in This Project

### Singular Tables (Non-standard):
- `offwork` → Model: `Offwork`
- `status` → Model: `Status`
- `work_status` → Model: `WorkStatus`
- `time_cutoff` → Model: `TimeCutoff`

### Plural Tables (Standard Laravel):
- `divisions` → Model: `Division`
- `sub_divisions` → Model: `SubDivision`
- `roles` → Model: `Role`
- `positions` → Model: `Position`
- `categories` → Model: `Category`
- `tasks` → Model: `Task`
- `builders` → Model: `Builder`
- `dwelings` → Model: `Dweling`
- `employees` → Model: `Employee`
- `logs` → Model: `Log`
- `holidays` → Model: `Holiday`
- `leave_types` → Model: `LeaveType`
- `notifications` → Model: `Notification`
- `users` → Model: `User`

## Testing After Fix

1. ✅ Offwork export - Should work now
2. ✅ Work Status export - Should work now
3. ✅ Status export - Already working
4. ✅ Time Cutoff export - Already working
5. ✅ All other exports - Should work

## How to Test

1. Login to application
2. Go to `/backup`
3. Click each download button
4. Verify CSV files download correctly
5. Open CSV files to verify data

## Prevention for Future

When creating new models, always check:
1. What is the actual table name in migration?
2. Does it follow Laravel's plural convention?
3. If not, add `protected $table = 'table_name';` in model

## Related Files Modified

1. `app/Models/Offwork.php` - Added `$table` property
2. `app/Models/WorkStatus.php` - Added `$table` property
3. `resources/views/backup/index.blade.php` - Re-enabled Work Status button

## Status

✅ **FIXED** - All backup downloads should now work correctly.
