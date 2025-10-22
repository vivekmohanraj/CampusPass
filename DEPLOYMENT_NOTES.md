# CampusPass Deployment Notes

## ✅ Fixed for EC2 Deployment

All file paths have been converted from **absolute paths** to **relative paths** to work on any server location.

### Changes Made:

#### CSS Links (All View Files)
- **Before:** `/projects/campus_event_ticketing/assets/css/theme.css`
- **After:** `../../assets/css/theme.css`

#### Navigation Links
- **Before:** `/projects/campus_event_ticketing/app/views/login.php`
- **After:** `login.php` (same directory) or `../../logout.php` (parent directories)

#### Form Actions
- **Before:** `/projects/campus_event_ticketing/app/controllers/AuthController.php`
- **After:** `../controllers/AuthController.php`

### Files Updated:
1. ✅ login.php
2. ✅ register.php
3. ✅ student_dashboard.php
4. ✅ admin_dashboard.php
5. ✅ student_events.php
6. ✅ event_list.php
7. ✅ my_tickets.php
8. ✅ participants.php
9. ✅ payments.php
10. ✅ booking_confirmation.php

## 🚀 Deployment Instructions

### On EC2 Instance:

1. **Upload the project** to `/var/www/html/CampusPass/` or your XAMPP `htdocs/CampusPass/`

2. **Access the application:**
   - Login: `http://YOUR_IP/CampusPass/app/views/login.php`
   - Register: `http://YOUR_IP/CampusPass/app/views/register.php`

3. **CSS will now load correctly** because paths are relative!

### Directory Structure:
```
CampusPass/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/          ← All PHP pages here
├── assets/
│   └── css/            ← All CSS files here
├── config.php
└── logout.php
```

### Important Notes:

- ✅ **CSS paths are now relative** - works on any server path
- ✅ **No hardcoded paths** - portable across environments
- ✅ **Session errors fixed** - PHP code before HTML output
- ✅ **Razorpay configured** - All payment methods enabled

## 🔧 If CSS Still Doesn't Load:

1. **Check file permissions:**
   ```bash
   chmod -R 755 /var/www/html/CampusPass/assets/
   ```

2. **Verify Apache configuration:**
   - Ensure `AllowOverride All` is set
   - Check `.htaccess` if present

3. **Clear browser cache:**
   - Hard refresh: `Ctrl + Shift + R` (Windows/Linux) or `Cmd + Shift + R` (Mac)

## 📝 Database Configuration:

Make sure to update database connection in files if needed:
- Default: `localhost`, `root`, `''`, `campus_event_ticketing`
- Update in each PHP file's `mysqli` connection if your EC2 has different credentials

## 🎨 Features:
- Modern UI with gradient designs
- Responsive layout
- Card-based dashboards
- SVG icons throughout
- Smooth animations and transitions
