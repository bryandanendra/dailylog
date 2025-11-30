# Daily Log System - Color Palette & Theme Guide

## Primary Color Palette

### 1. **Primary Blue** (Main Theme Color)
- **Primary Color**: `#1976d2` (Header background, primary actions)
- **Light Blue**: `#2196f3` (Active sidebar links)
- **Lighter Blue**: `#bbdefb` (Hover states)
- **Ultra Light Blue**: `#e3f2fd` (Sidebar background, notification highlights)
- **Dark Blue**: `#0d47a1` (Hover text color)
- **Bootstrap Blue**: `#0d6efd` (Buttons, search buttons)
- **Bootstrap Blue Hover**: `#0b5ed7` (Button hover states)

### 2. **Neutral Colors**
- **Background**: `#f8f9fa` (Main content area background)
- **White**: `#ffffff` (Cards, modals)
- **Gray Borders**: `#ced4da` (Input borders)
- **Light Gray**: `#e0e0e0` (Dropdown borders)
- **Text Gray**: `#666` (Secondary text)
- **Dark Text**: `#333` (Primary text)

### 3. **Status Colors**
- **Danger/Error**: `#f44336` (Delete buttons, error badges)
- **Warning**: (Bootstrap warning class)
- **Success**: (Bootstrap success class)
- **Info**: (Bootstrap info class)

## Where to Change Colors

### 1. **Header Color**
**File**: `resources/views/layouts/app.blade.php`
**Line**: 71
```css
.header {
    background-color: #1976d2; /* Change this for header color */
    color: white;
}
```

### 2. **Sidebar Color**
**File**: `resources/views/layouts/app.blade.php`
**Line**: 11-13
```css
.sidebar {
    min-height: 100vh;
    background-color: #e3f2fd; /* Change this for sidebar background */
    width: 200px;
}
```

**Link Colors** (Line 27-40):
```css
.sidebar .nav-link {
    color: #1976d2; /* Change this for sidebar link color */
}

.sidebar .nav-link:hover {
    background-color: #bbdefb; /* Change this for hover background */
    color: #0d47a1; /* Change this for hover text color */
}

.sidebar .nav-link.active {
    background-color: #2196f3; /* Change this for active link background */
    color: white;
}
```

### 3. **Button Colors**
**Files**: Various blade.php files with inline styles
**Example**: `resources/views/offwork/holiday.blade.php` (lines 106-133)
```css
.search-data button {
    background: #0d6efd; /* Change this for button background */
    color: white;
}

.search-data button:hover {
    background: #0b5ed7; /* Change this for button hover */
}
```

### 4. **Main Content Background**
**File**: `resources/views/layouts/app.blade.php`
**Line**: 247
```css
.content-area {
    padding: 20px;
    background-color: #f8f9fa; /* Change this for main content background */
}
```

## Quick Theme Change Guide

### Option 1: Green Theme
```css
/* Header */
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

### Option 2: Purple Theme
```css
/* Header */
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

### Option 3: Orange/Red Theme
```css
/* Header */
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

## How to Apply Theme Changes

1. **Open**: `resources/views/layouts/app.blade.php`
2. **Find**: The `<style>` section (lines 10-282)
3. **Replace**: The color values according to your theme preference
4. **Save**: The file
5. **Refresh**: Your browser (Ctrl+F5 or Cmd+Shift+R for hard refresh)

## Additional Notes

- All pages inherit the color scheme from `layouts/app.blade.php`
- Some pages have inline styles that may need individual updates
- For consistent theming, consider creating a separate CSS file
- Bootstrap classes (btn-primary, btn-danger, etc.) use Bootstrap's default colors
- You can override Bootstrap colors by adding custom CSS with higher specificity

## Recommended Color Tools

- **Adobe Color**: https://color.adobe.com/
- **Coolors**: https://coolors.co/
- **Material Design Colors**: https://materialui.co/colors/
- **Color Hunt**: https://colorhunt.co/

## Testing Your Theme

After changing colors, test these areas:
- [ ] Header and navigation
- [ ] Sidebar links (normal, hover, active states)
- [ ] Buttons (normal and hover)
- [ ] Forms and inputs
- [ ] Tables
- [ ] Modals
- [ ] Notifications
- [ ] Cards and content areas
