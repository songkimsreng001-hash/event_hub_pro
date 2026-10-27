# EventHub Pro — Event Management Platform

A modern, full-featured Event Management web application crafted with **PHP 8 (OOP/Procedural)**, **Tailwind CSS**, **SVG Icons (Heroicons)**, **SweetAlert2**, and **Cropper.js**.

---

## 🚀 Key Features & Highlights

1. **Admin Dashboard with Dedicated Sidebar Layout**:
   - **Collapsible Sidebar on Desktop** (`w-64`) and slide-over drawer on mobile devices with active route indicators and live pending review counters.
   - **Dual Layout System**: Administrators get an admin workspace with the sidebar and top control header; members and guests get an intuitive top navigation bar.
   - **Quick Action KPIs**: Total Events, Registrations, Pending Approvals with alert badges, and Member Counts.
   - **Interactive Registration Management**: Approve or reject attendee applications in real time.

2. **Light / Dark Mode**:
   - Class-based Tailwind dark mode configuration (`darkMode: 'class'`).
   - Instant flash-prevention script in `<head>` inspecting `localStorage` and OS `prefers-color-scheme`.
   - Accessible Sun/Moon toggle buttons available in both the top navigation bar and admin sidebar.
   - Consistent dark theme classes (`dark:bg-slate-900`, `dark:bg-slate-800`, `dark:border-slate-700`, `dark:text-white`) across all pages, tables, dialogs, and SweetAlert2 alerts.

3. **Photo Crop Before Save (Cropper.js)**:
   - Interactive modal popup with Cropper.js before uploading images.
   - Supports **16:9 aspect ratio** for Event Banners and **1:1 aspect ratio** for User & Profile Avatars.
   - Tools to zoom, rotate ⟲ -90° / ⟳ +90°, and reset crop boundaries.
   - Seamless client-side HTML5 Canvas-to-Blob `DataTransfer` file replacement, preserving existing standard PHP `$_FILES` upload handling.

4. **Bilingual Support: Khmer & English (KH / EN)**:
   - Complete dictionary and helper `__($key)` in `includes/lang.inc.php`.
   - Instant language switching via query parameters (`?lang=kh` / `?lang=en`) with Session and Cookie persistence.
   - Google Font **Kantumruy Pro** combined with **Plus Jakarta Sans** for native, high-legibility Khmer & English typography.

5. **Medium Event Cards & Search with Autocomplete by Letter**:
   - **Medium Event Cards**: Optimized proportions with `h-44` image banner, rounded-3xl containers, metadata badges, 2-line title/description clamps, and visual capacity meters.
   - **Medium Search Box**: Compact `sm:w-80 md:w-96` search field with clear icon and real-time live letter-by-letter filtering.
   - **Live Autocomplete**: Real-time dropdown suggestions showing matching events as letters are typed, highlighting matches and clicking to auto-fill.

6. **SweetAlert2 Toasts & Confirmation Dialogs**:
   - Automated toasts for all system flash messages (`success`, `error`, `warning`, `info`).
   - Interactive confirmation dialogs on dangerous actions (event deletion, user removal, booking cancellation, logout).
   - Client-side image validation (type and file size).

7. **Clean Architecture & Bug Fixes**:
   - **Resolved "Headers Already Sent" Warnings**: Enabled output buffering (`ob_start()`) and separated non-rendering action routes.
   - **Fixed User Creation (`pages/user/create.php`)**: Replaced duplicate event logic with complete user creation CRUD.
   - **Fixed Broken Routes & Aliases**: Unified route aliases so both `event/create` & `event/create_event`, and `event/delete` & `event/delete_event` work reliably.
   - **Dynamic Base URL**: Removed hardcoded path references to ensure universal deployment compatibility.
   - **Security Enhancements**: Modern password hashing (`password_hash` & `password_verify`) with backward-compatibility for legacy plain-text records.

---

## 📁 Clean Folder Structure

```
Event_mgt/
├── assets/
│   ├── css/
│   │   └── style.css               # Clean custom styling (scrollbar, sidebar collapsed, cropper, transitions)
│   ├── js/
│   │   └── app.js                  # Modular client app controller (Cropper, themes, menus, SweetAlert2)
│   └── images/
│       ├── Event_mgt_logo.jpg      # Official project brand logo
│       ├── loading.png             # Event banner fallback placeholder
│       └── Profile_PNG.png         # Default user avatar placeholder
├── database.sql                    # Database schema and seed data
├── includes/
│   ├── footer.inc.php              # Global footer, cropper modal markup, scripts integration
│   ├── header.inc.php              # HTML head, Google Fonts, Tailwind config, style.css link
│   ├── icons.inc.php               # SVG icon helper (getIcon function)
│   ├── lang.inc.php                # Localization dictionary (EN/KH) and __($key) helper
│   ├── navbar.inc.php              # Responsive navigation bar with mobile drawer & theme/lang switchers
│   └── sidebar.inc.php             # Admin sidebar layout with navigation, badges & theme/lang switchers
├── index.php                       # Front controller & dual layout renderer
├── init/
│   ├── db.init.php                 # MySQLi connection & connection fallback screen
│   ├── init.php                    # App initialization, sessions, flash messages, helpers
│   └── func/
│       ├── auth.func.php           # Authentication, password change, image upload
│       ├── event.func.php          # Event CRUD, registrations, statistics
│       └── user.func.php           # User management CRUD
├── pages/
│   ├── dashboard.php               # Admin overview & member dashboard
│   ├── events.php                  # Medium event cards with search & live autocomplete by letter
│   ├── login.php                   # Authentication login form (with brand logo)
│   ├── logout.php                  # Session logout handler
│   ├── my-registrations.php        # User's bookings & approval status
│   ├── profile.php                 # Account profile, avatar upload & crop, password change
│   ├── register.php                # Member self-registration (with brand logo)
│   ├── event/
│   │   ├── cancel.php              # Booking cancellation handler
│   │   ├── create.php              # Create event form (with 16:9 Cropper & preview)
│   │   ├── create_event.php        # Route alias
│   │   ├── delete.php              # Event deletion handler
│   │   ├── delete_event.php        # Route alias
│   │   ├── detail.php              # Event overview, seats counter, register button
│   │   ├── edit.php                # Event update form (with 16:9 Cropper)
│   │   ├── register.php            # Attendee booking confirmation form
│   │   └── registrations.php       # Admin attendee management (Approve / Reject)
│   └── user/
│       ├── create.php              # Admin add new user form (with 1:1 avatar Cropper)
│       ├── delete.php              # Admin delete user handler
│       ├── list.php                # Admin member list & search
│       └── update.php              # Admin edit user form (with 1:1 avatar Cropper)
└── README.md                       # Project documentation
```

---

## ⚙️ Installation & Setup

### 1. Requirements
- **PHP**: 8.0 or higher
- **Web Server**: Apache (XAMPP, WAMP, Laragon, or standalone)
- **Database**: MySQL / MariaDB (Port 3306)

### 2. Database Import
1. Start **Apache** and **MySQL** in your XAMPP Control Panel.
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Click on **Import**, choose `database.sql` from this project directory, and click **Import**.
   *(Creates database `event_mgt` with demo accounts and initial events)*.

### 3. Accessing the Application
Open your web browser and navigate to:
```
http://localhost/Event_mgt/
```

---

## 🔑 Default Credentials

| Role | Username | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | Full Admin Access (Events, Approvals, Users, Sidebar) |
| **Standard User** | `janedoe` | `admin123` | Member Access (Explore, Register, Profile, My Bookings) |
