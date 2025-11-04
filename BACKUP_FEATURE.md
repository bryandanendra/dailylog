# Backup Feature Documentation

## Overview
The backup feature allows users to export database tables to CSV format for backup and data portability purposes.

## Files Created

### 1. BackupController
**Location:** `app/Http/Controllers/BackupController.php`

**Features:**
- Export individual tables to CSV format
- Export all tables as a ZIP file
- Includes related data (e.g., employee names, division names) in exports
- UTF-8 BOM support for proper Excel compatibility
- Timestamped filenames for easy identification

**Methods:**
- `index()` - Display backup page
- `exportDivisions()` - Export divisions table
- `exportSubDivisions()` - Export sub divisions with division names
- `exportRoles()` - Export roles table
- `exportPositions()` - Export positions table
- `exportCategories()` - Export categories table
- `exportTasks()` - Export tasks table
- `exportBuilders()` - Export builders table
- `exportDwelings()` - Export dwelings table
- `exportStatus()` - Export status table
- `exportWorkStatus()` - Export work status table
- `exportEmployees()` - Export employees with related data
- `exportLogs()` - Export daily logs with all relationships
- `exportOffwork()` - Export off work records
- `exportHolidays()` - Export holidays table
- `exportLeaveTypes()` - Export leave types table
- `exportUsers()` - Export users (excluding passwords)
- `exportNotifications()` - Export notifications table
- `exportTimeCutoff()` - Export time cutoff settings
- `exportAll()` - Export all tables as ZIP file

### 2. Backup View
**Location:** `resources/views/backup/index.blade.php`

**Features:**
- Clean, organized UI with Bootstrap cards
- Grouped by data type (Master Data, Transaction Data, System Data)
- Color-coded sections for easy navigation
- Download all tables button (ZIP format)
- Individual download buttons for each table
- Hover effects for better UX

**Sections:**
1. **Master Data Tables** (Blue cards)
   - Divisions, Sub Divisions, Roles, Positions
   - Categories, Tasks, Builders, Dwelings
   - Status, Work Status, Leave Types

2. **Transaction Data Tables** (Green cards)
   - Employees, Daily Logs, Off Work, Holidays

3. **System Data Tables** (Info cards)
   - Users, Notifications, Time Cutoff

### 3. Routes
**Location:** `routes/web.php`

All routes are protected by authentication middleware:
- `GET /backup` - Display backup page
- `GET /backup/divisions` - Download divisions CSV
- `GET /backup/subdivisions` - Download sub divisions CSV
- `GET /backup/roles` - Download roles CSV
- `GET /backup/positions` - Download positions CSV
- `GET /backup/categories` - Download categories CSV
- `GET /backup/tasks` - Download tasks CSV
- `GET /backup/builders` - Download builders CSV
- `GET /backup/dwelings` - Download dwelings CSV
- `GET /backup/status` - Download status CSV
- `GET /backup/workstatus` - Download work status CSV
- `GET /backup/employees` - Download employees CSV
- `GET /backup/logs` - Download logs CSV
- `GET /backup/offwork` - Download off work CSV
- `GET /backup/holidays` - Download holidays CSV
- `GET /backup/leavetypes` - Download leave types CSV
- `GET /backup/users` - Download users CSV
- `GET /backup/notifications` - Download notifications CSV
- `GET /backup/timecutoff` - Download time cutoff CSV
- `GET /backup/all` - Download all tables as ZIP

## Usage

### Access the Backup Page
1. Login to the system
2. Navigate to `/backup` route
3. The backup page will display all available tables

### Download Individual Table
1. Click on the "Download CSV" button for the desired table
2. The CSV file will be downloaded with a timestamp in the filename
3. Format: `tablename_YYYY-MM-DD_HHMMSS.csv`

### Download All Tables
1. Click on the "Download All Tables (ZIP)" button at the top
2. A ZIP file containing all tables will be downloaded
3. Format: `backup_all_YYYY-MM-DD_HHMMSS.zip`

## CSV Format
- UTF-8 encoding with BOM for Excel compatibility
- First row contains column headers
- Data includes related information (e.g., employee names, not just IDs)
- Timestamps are included for audit purposes

## Technical Details

### Dependencies
- Laravel Framework
- PHP ZipArchive extension (for ZIP export)
- Bootstrap 5 (for UI)
- Bootstrap Icons (for icons)

### Data Relationships
The export includes related data to make the CSV files more readable:
- **Sub Divisions**: Includes division name
- **Employees**: Includes division, sub division, role, and position names
- **Logs**: Includes employee, category, task, builder, dweling, and status names
- **Off Work**: Includes employee name

### Security
- All routes are protected by authentication middleware
- User passwords are excluded from user exports
- CSRF protection is maintained

## Future Enhancements
Potential improvements:
1. Add date range filtering for transaction data
2. Add scheduled automatic backups
3. Add restore functionality from CSV
4. Add backup to cloud storage (S3, Google Drive, etc.)
5. Add compression options for large datasets
6. Add email notification when backup is complete
7. Add backup history and management

## Testing
To test the backup feature:
1. Ensure you have data in the database
2. Login to the application
3. Navigate to `/backup`
4. Test individual table downloads
5. Test the "Download All" feature
6. Verify CSV files open correctly in Excel/LibreOffice
7. Check that related data is included correctly

## Troubleshooting

### CSV not opening correctly in Excel
- The CSV includes UTF-8 BOM which should handle this
- If issues persist, try opening with "Import Data" in Excel

### ZIP file not creating
- Check that PHP ZipArchive extension is installed
- Verify write permissions on `storage/app/public` directory

### Missing related data
- Ensure relationships are properly defined in models
- Check that foreign keys exist in the database

## Notes
- CSV files are generated on-the-fly and not stored on the server
- ZIP files are temporarily stored and deleted after download
- Large tables may take time to export
- Consider adding pagination for very large datasets in future versions
