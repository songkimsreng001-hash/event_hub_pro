<?php
/**
 * Localization / Multi-language Helper (English & Khmer)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle language switch via query parameter ?lang=kh or ?lang=en
if (isset($_GET['lang'])) {
    $requestedLang = strtolower(trim($_GET['lang']));
    if (in_array($requestedLang, ['en', 'kh'])) {
        $_SESSION['lang'] = $requestedLang;
        setcookie('site_lang', $requestedLang, time() + (86400 * 30), '/');
    }
}

// Default to session, cookie, or English
$currentLang = $_SESSION['lang'] ?? $_COOKIE['site_lang'] ?? 'en';
if (!in_array($currentLang, ['en', 'kh'])) {
    $currentLang = 'en';
}

$GLOBALS['currentLang'] = $currentLang;

/**
 * Translations Dictionary
 */
$translations = [
    // General & Brand
    'app_name'           => ['en' => 'EventHub Pro', 'kh' => 'អ៊ីវិនហាប់ ប្រូ'],
    'app_tagline'        => ['en' => 'Modern Event Management', 'kh' => 'ប្រព័ន្ធគ្រប់គ្រងព្រឹត្តិការណ៍'],
    'lang_en'            => ['en' => 'English', 'kh' => 'អង់គ្លេស'],
    'lang_kh'            => ['en' => 'Khmer', 'kh' => 'ភាសាខ្មែរ'],
    'dark_mode'          => ['en' => 'Dark Mode', 'kh' => 'មុខងារងងឹត'],
    'light_mode'         => ['en' => 'Light Mode', 'kh' => 'មុខងារភ្លឺ'],
    'search'             => ['en' => 'Search', 'kh' => 'ស្វែងរក'],
    'search_placeholder' => ['en' => 'Type letter to search event, location...', 'kh' => 'វាយអក្សរដើម្បីស្វែងរកព្រឹត្តិការណ៍ ទីតាំង...'],
    'all'                => ['en' => 'All', 'kh' => 'ទាំងអស់'],
    'actions'            => ['en' => 'Actions', 'kh' => 'សកម្មភាព'],
    'save'               => ['en' => 'Save', 'kh' => 'រក្សាទុក'],
    'cancel'             => ['en' => 'Cancel', 'kh' => 'បោះបង់'],
    'back'               => ['en' => 'Back', 'kh' => 'ត្រឡប់ក្រោយ'],
    'view'               => ['en' => 'View', 'kh' => 'មើល'],
    'edit'               => ['en' => 'Edit', 'kh' => 'កែប្រែ'],
    'delete'             => ['en' => 'Delete', 'kh' => 'លុប'],
    'status'             => ['en' => 'Status', 'kh' => 'ស្ថានភាព'],
    'pending'            => ['en' => 'Pending', 'kh' => 'រង់ចាំអនុម័ត'],
    'approved'           => ['en' => 'Approved', 'kh' => 'បានអនុម័ត'],
    'rejected'           => ['en' => 'Rejected', 'kh' => 'បានបដិសេធ'],
    'close'              => ['en' => 'Close', 'kh' => 'បិទ'],
    'apply'              => ['en' => 'Apply', 'kh' => 'យល់ព្រម'],

    // Navigation & Sidebar
    'nav_dashboard'      => ['en' => 'Dashboard', 'kh' => 'ផ្ទាំងគ្រប់គ្រង'],
    'nav_events'         => ['en' => 'Events', 'kh' => 'ព្រឹត្តិការណ៍'],
    'nav_explore'        => ['en' => 'Explore Events', 'kh' => 'ស្វែងរកព្រឹត្តិការណ៍'],
    'nav_my_bookings'    => ['en' => 'My Registrations', 'kh' => 'ការចុះឈ្មោះរបស់ខ្ញុំ'],
    'nav_users'          => ['en' => 'Manage Users', 'kh' => 'គ្រប់គ្រងអ្នកប្រើប្រាស់'],
    'nav_new_event'      => ['en' => 'Create Event', 'kh' => 'បង្កើតព្រឹត្តិការណ៍'],
    'nav_new_user'       => ['en' => 'Add User', 'kh' => 'បន្ថែមអ្នកប្រើ'],
    'nav_profile'        => ['en' => 'Profile & Settings', 'kh' => 'គណនី & ការកំណត់'],
    'nav_login'          => ['en' => 'Sign In', 'kh' => 'ចូលគណនី'],
    'nav_register'       => ['en' => 'Register', 'kh' => 'ចុះឈ្មោះ'],
    'nav_logout'         => ['en' => 'Log Out', 'kh' => 'ចាកចេញ'],
    'admin_panel'        => ['en' => 'Admin Panel', 'kh' => 'ផ្ទាំងអ្នកគ្រប់គ្រង'],
    'menu_main'          => ['en' => 'MAIN MENU', 'kh' => 'ម៉ឺនុយចម្បង'],
    'menu_admin'         => ['en' => 'ADMINISTRATION', 'kh' => 'ផ្នែករដ្ឋបាល'],
    'menu_settings'      => ['en' => 'PREFERENCES', 'kh' => 'ការកំណត់'],

    // Dashboard
    'dash_overview'      => ['en' => 'Admin Overview', 'kh' => 'ទិដ្ឋភាពទូទៅនៃរដ្ឋបាល'],
    'dash_welcome'       => ['en' => 'Welcome back', 'kh' => 'សូមស្វាគមន៍មកវិញ'],
    'dash_total_events'  => ['en' => 'Total Events', 'kh' => 'ព្រឹត្តិការណ៍សរុប'],
    'dash_total_regs'    => ['en' => 'Registrations', 'kh' => 'ការចុះឈ្មោះសរុប'],
    'dash_pending'       => ['en' => 'Pending Review', 'kh' => 'រង់ចាំពិនិត្យ'],
    'dash_users'         => ['en' => 'Registered Users', 'kh' => 'អ្នកប្រើប្រាស់សរុប'],
    'dash_upcoming'      => ['en' => 'Upcoming Events', 'kh' => 'ព្រឹត្តិការណ៍ខាងមុខ'],
    'dash_recent_activity'=> ['en' => 'Recent Attendee Activity', 'kh' => 'សកម្មភាពចុះឈ្មោះថ្មីៗ'],
    'dash_quick_shortcuts'=> ['en' => 'Quick Shortcuts', 'kh' => 'ផ្លូវកាត់រហ័ស'],

    // Events Page
    'events_title'       => ['en' => 'Browse Events', 'kh' => 'ស្វែងរកព្រឹត្តិការណ៍'],
    'events_subtitle'    => ['en' => 'Discover conferences, workshops, and meetups.', 'kh' => 'ស្វែងរកសន្និសីទ សិក្ខាសាលា និងការជួបជុំផ្សេងៗ។'],
    'events_all'         => ['en' => 'All Events', 'kh' => 'ព្រឹត្តិការណ៍ទាំងអស់'],
    'events_upcoming'    => ['en' => 'Upcoming Only', 'kh' => 'ព្រឹត្តិការណ៍ខាងមុខ'],
    'events_past'        => ['en' => 'Past Events', 'kh' => 'ព្រឹត្តិការណ៍កន្លងផុត'],
    'seats_left'         => ['en' => 'seats left', 'kh' => 'កន្លែងនៅសល់'],
    'sold_out'           => ['en' => 'Sold Out', 'kh' => 'ពេញហើយ'],
    'concluded'          => ['en' => 'Concluded', 'kh' => 'បានបញ្ចប់'],
    'details_register'   => ['en' => 'Details & Registration', 'kh' => 'ព័ត៌មានលម្អិត & ចុះឈ្មោះ'],
    'location'           => ['en' => 'Location', 'kh' => 'ទីតាំង'],
    'date_time'          => ['en' => 'Date & Time', 'kh' => 'កាលបរិច្ឆេទ & ម៉ោង'],
    'capacity'           => ['en' => 'Capacity', 'kh' => 'ចំណុះអតិបរមា'],
    'fill_rate'          => ['en' => 'Filled', 'kh' => 'បានបំពេញ'],
    'no_events_found'    => ['en' => 'No Events Found', 'kh' => 'រកមិនឃើញព្រឹត្តិការណ៍ទេ'],

    // Event Detail & Registration
    'event_detail'       => ['en' => 'Event Details', 'kh' => 'ព័ត៌មានលម្អិតនៃព្រឹត្តិការណ៍'],
    'about_event'        => ['en' => 'About this Event', 'kh' => 'អំពីព្រឹត្តិការណ៍នេះ'],
    'register_now'       => ['en' => 'Register for Event', 'kh' => 'ចុះឈ្មោះចូលរួមឥឡូវនេះ'],
    'event_full'         => ['en' => 'Event Fully Booked', 'kh' => 'ព្រឹត្តិការណ៍នេះពេញហើយ'],
    'cancel_reg'         => ['en' => 'Cancel Registration', 'kh' => 'បោះបង់ការចុះឈ្មោះ'],
    'confirm_booking'    => ['en' => 'Confirm Registration', 'kh' => 'បញ្ជាក់ការចុះឈ្មោះ'],
    'attendee_name'      => ['en' => 'Attendee Name', 'kh' => 'ឈ្មោះអ្នកចូលរួម'],
    'username'           => ['en' => 'Username', 'kh' => 'ឈ្មោះគណនី'],
    'note_optional'      => ['en' => 'Note for Organizer (optional)', 'kh' => 'កំណត់សម្គាល់សម្រាប់អ្នករៀបចំ (ស្រេចចិត្ត)'],
    'manage_regs'        => ['en' => 'Manage Registrations', 'kh' => 'គ្រប់គ្រងការចុះឈ្មោះ'],

    // Photo Crop
    'crop_title'         => ['en' => 'Crop Photo Before Upload', 'kh' => 'កាត់តម្រឹមរូបភាពមុនពេលរក្សាទុក'],
    'crop_desc'          => ['en' => 'Drag and zoom the image to crop nicely.', 'kh' => 'ទាញ និងពង្រីក/បង្រួមរូបភាពតាមតម្រូវការ។'],
    'crop_confirm'       => ['en' => 'Crop & Apply Photo', 'kh' => 'កាត់ & ប្រើប្រាស់រូបភាព'],
    'rotate'             => ['en' => 'Rotate', 'kh' => 'បង្វិល'],
    'reset'              => ['en' => 'Reset', 'kh' => 'កំណត់ឡើងវិញ'],

    // Users
    'user_list'          => ['en' => 'Users List', 'kh' => 'បញ្ជីអ្នកប្រើប្រាស់'],
    'add_user'           => ['en' => 'Add New User', 'kh' => 'បន្ថែមអ្នកប្រើថ្មី'],
    'edit_user'          => ['en' => 'Edit User Profile', 'kh' => 'កែប្រែគណនីអ្នកប្រើ'],
    'role'               => ['en' => 'Role', 'kh' => 'តួនាទី'],
    'joined_date'        => ['en' => 'Joined Date', 'kh' => 'កាលបរិច្ឆេទចូលរួម'],

    // Profile & Password
    'profile_title'      => ['en' => 'Profile & Account Settings', 'kh' => 'គណនី & ការកំណត់'],
    'profile_subtitle'   => ['en' => 'Manage your account credentials, avatar, and security preferences.', 'kh' => 'គ្រប់គ្រងគណនី រូបតំណាង និងសុវត្ថិភាពរបស់អ្នក។'],
    'change_password'    => ['en' => 'Change Password', 'kh' => 'ប្តូរពាក្យសម្ងាត់'],
    'old_password'       => ['en' => 'Current Password', 'kh' => 'ពាក្យសម្ងាត់បច្ចុប្បន្ន'],
    'new_password'       => ['en' => 'New Password', 'kh' => 'ពាក្យសម្ងាត់ថ្មី'],
    'confirm_password'   => ['en' => 'Confirm New Password', 'kh' => 'បញ្ជាក់ពាក្យសម្ងាត់ថ្មី'],
    'upload_photo'       => ['en' => 'Upload Photo', 'kh' => 'ផ្ទុករូបភាពឡើង'],
    'remove_photo'       => ['en' => 'Remove Photo', 'kh' => 'លុបរូបភាព'],
    'save_photo'         => ['en' => 'Save Photo', 'kh' => 'រក្សាទុករូបភាព'],
    'change_avatar'      => ['en' => 'Change', 'kh' => 'ផ្លាស់ប្តូរ'],
    'member_since'       => ['en' => 'Member Since', 'kh' => 'សមាជិកតាំងពី'],
    'account_status'     => ['en' => 'Account Status', 'kh' => 'ស្ថានភាពគណនី'],
    'active'             => ['en' => 'Active', 'kh' => 'សកម្ម'],
    'security_password'  => ['en' => 'Security & Password', 'kh' => 'សុវត្ថិភាព & ពាក្យសម្ងាត់'],
    'security_subtitle'  => ['en' => 'Update your account password to maintain security', 'kh' => 'ធ្វើបច្ចុប្បន្នភាពពាក្យសម្ងាត់គណនីដើម្បីរក្សាសុវត្ថិភាព'],
    'update_password'    => ['en' => 'Update Password', 'kh' => 'ប្តូរពាក្យសម្ងាត់'],

    // Detail & Extra Event Texts
    'back_to_events'     => ['en' => 'Back to All Events', 'kh' => 'ត្រឡប់ទៅព្រឹត្តិការណ៍ទាំងអស់'],
    'back_to_event'      => ['en' => 'Back to Event', 'kh' => 'ត្រឡប់ទៅព្រឹត្តិការណ៍'],
    'event_info'         => ['en' => 'Event Information', 'kh' => 'ព័ត៌មានព្រឹត្តិការណ៍'],
    'attendee_capacity'  => ['en' => 'Attendee Capacity', 'kh' => 'ចំណុះអ្នកចូលរួម'],
    'booked'             => ['en' => 'booked', 'kh' => 'បានកក់'],
    'spots_remaining'    => ['en' => 'spots remaining', 'kh' => 'កន្លែងនៅសល់'],
    'seats_available'    => ['en' => 'seats available', 'kh' => 'កៅអីនៅសល់'],
    'admin_managing_notice' => ['en' => 'You are managing this event as Admin', 'kh' => 'អ្នកកំពុងគ្រប់គ្រងព្រឹត្តិការណ៍នេះក្នុងនាមជាអ្នកគ្រប់គ្រង'],
    'manage_regs_count'  => ['en' => 'Manage Registrations', 'kh' => 'គ្រប់គ្រងការចុះឈ្មោះ'],
    'edit_event_details' => ['en' => 'Edit Event Details', 'kh' => 'កែប្រែព័ត៌មានព្រឹត្តិការណ៍'],
    'delete_event'       => ['en' => 'Delete Event', 'kh' => 'លុបព្រឹត្តិការណ៍'],
    'reg_confirmed_msg'  => ['en' => 'Your spot is confirmed! See you at the venue.', 'kh' => 'កន្លែងរបស់អ្នកត្រូវបានបញ្ជាក់! ជួបគ្នានៅទីតាំងកម្មវិធី។'],
    'reg_pending_msg'    => ['en' => 'Your registration is awaiting approval from organizers.', 'kh' => 'ការចុះឈ្មោះរបស់អ្នកកំពុងរង់ចាំការអនុម័តពីអ្នករៀបចំ។'],
    'cancel_my_reg'      => ['en' => 'Cancel My Registration', 'kh' => 'បោះបង់ការចុះឈ្មោះរបស់ខ្ញុំ'],
    'signin_to_register' => ['en' => 'Sign In to Register', 'kh' => 'ចូលគណនីដើម្បីចុះឈ្មោះ'],
    'signin_to_reserve'  => ['en' => 'Sign in to reserve your seat at this event.', 'kh' => 'ចូលគណនីដើម្បីកក់កៅអីរបស់អ្នកក្នុងព្រឹត្តិការណ៍នេះ។'],
    'new_here'           => ['en' => 'New here?', 'kh' => 'ទើបមកថ្មីមែនទេ?'],
    'create_account'     => ['en' => 'Create an account', 'kh' => 'បង្កើតគណនីថ្មី'],
    'venue_location'     => ['en' => 'Venue Location', 'kh' => 'ទីតាំងកម្មវិធី'],

    // Event Registration Form
    'review_ticket_request' => ['en' => 'Review your ticket request and submit for organizer approval', 'kh' => 'ពិនិត្យមើលសំណើសំបុត្ររបស់អ្នក ហើយដាក់ស្នើទៅកាន់អ្នករៀបចំ'],
    'reg_reviewed_notice'   => ['en' => 'Registrations are reviewed by the host. You can check the approval status anytime in My Registrations.', 'kh' => 'ការចុះឈ្មោះត្រូវបានពិនិត្យដោយម្ចាស់កម្មវិធី។ អ្នកអាចពិនិត្យស្ថានភាពបានគ្រប់ពេលក្នុងផ្ទាំង ការចុះឈ្មោះរបស់ខ្ញុំ។'],
    'note_placeholder'      => ['en' => 'Any questions, special requirements, dietary needs, or comments...', 'kh' => 'សំណួរ តម្រូវការពិសេស របបអាហារ ឬមតិយោបល់ផ្សេងៗ...'],

    // Event Registrations Management
    'attendees'          => ['en' => 'Attendees', 'kh' => 'អ្នកចូលរួម'],
    'seats_occupied'     => ['en' => 'Seats Occupied', 'kh' => 'កៅអីដែលបានកក់'],
    'registered_date'    => ['en' => 'Registered Date', 'kh' => 'កាលបរិច្ឆេទចុះឈ្មោះ'],
    'note_message'       => ['en' => 'Note / Message', 'kh' => 'កំណត់សម្គាល់ / សារ'],
    'approve_attendee'   => ['en' => 'Approve Attendee', 'kh' => 'អនុម័តអ្នកចូលរួម'],
    'reject_attendee'    => ['en' => 'Reject Attendee', 'kh' => 'បដិសេធអ្នកចូលរួម'],
    'no_regs_found'      => ['en' => 'No registrations found under this filter', 'kh' => 'រកមិនឃើញការចុះឈ្មោះក្រោមតម្រងនេះទេ'],

    // My Registrations
    'my_regs_title'      => ['en' => 'My Event Registrations', 'kh' => 'ការចុះឈ្មោះព្រឹត្តិការណ៍របស់ខ្ញុំ'],
    'my_regs_subtitle'   => ['en' => 'Review the status of your event applications and registered tickets.', 'kh' => 'ពិនិត្យមើលស្ថានភាពនៃការដាក់ពាក្យ និងសំបុត្រដែលបានចុះឈ្មោះ។'],
    'browse_more_events' => ['en' => 'Browse More Events', 'kh' => 'ស្វែងរកព្រឹត្តិការណ៍បន្ថែម'],
    'booked_on'          => ['en' => 'Booked', 'kh' => 'បានកក់នៅ'],
    'event_info_link'    => ['en' => 'Event Info', 'kh' => 'ព័ត៌មានព្រឹត្តិការណ៍'],
    'no_regs_yet'        => ['en' => 'No Registrations Found', 'kh' => 'រកមិនឃើញការចុះឈ្មោះទេ'],
    'no_regs_desc'       => ['en' => 'You have not registered for any events yet. Explore upcoming opportunities and secure your spot!', 'kh' => 'អ្នកមិនទាន់បានចុះឈ្មោះសម្រាប់ព្រឹត្តិការណ៍ណាមួយនៅឡើយទេ។ សូមស្វែងរកព្រឹត្តិការណ៍ខាងមុខ!'],

    // User Management
    'members'            => ['en' => 'Members', 'kh' => 'សមាជិក'],
    'users_subtitle'     => ['en' => 'Review registered platform members, update account roles, and manage credentials.', 'kh' => 'ពិនិត្យសមាជិកដែលបានចុះឈ្មោះ ផ្លាស់ប្តូរតួនាទី និងគ្រប់គ្រងគណនី។'],
    'search_users_placeholder' => ['en' => 'Search users by name or username...', 'kh' => 'ស្វែងរកអ្នកប្រើតាមឈ្មោះ ឬឈ្មោះគណនី...'],
    'user_col'           => ['en' => 'User', 'kh' => 'អ្នកប្រើប្រាស់'],
    'edit_user_title'    => ['en' => 'Edit User Profile', 'kh' => 'កែប្រែគណនីអ្នកប្រើ'],
    'edit_user_subtitle' => ['en' => 'Update account credentials, photo, or access level', 'kh' => 'កែប្រែព័ត៌មានគណនី រូបថត ឬកម្រិតសិទ្ធិ'],
    'add_user_title'     => ['en' => 'Add New User', 'kh' => 'បន្ថែមអ្នកប្រើថ្មី'],
    'add_user_subtitle'  => ['en' => 'Create a new member account with custom permissions', 'kh' => 'បង្កើតគណនីសមាជិកថ្មីជាមួយនឹងសិទ្ធិកំណត់ផ្ទាល់ខ្លួន'],
    'back_to_users'      => ['en' => 'Back to Users List', 'kh' => 'ត្រឡប់ទៅបញ្ជីអ្នកប្រើ'],
    'full_name'          => ['en' => 'Full Name', 'kh' => 'ឈ្មោះពេញ'],
    'account_role'       => ['en' => 'Account Role', 'kh' => 'តួនាទីគណនី'],
    'role_user'          => ['en' => 'Standard Member (User)', 'kh' => 'សមាជិកធម្មតា (User)'],
    'role_admin'         => ['en' => 'Administrator (Full Access)', 'kh' => 'អ្នកគ្រប់គ្រង (Admin)'],
    'temp_password'      => ['en' => 'Temporary Password', 'kh' => 'ពាក្យសម្ងាត់បណ្តោះអាសន្ន'],
    'leave_blank_password' => ['en' => 'Leave blank to keep existing password', 'kh' => 'ទុកនៅទទេបើមិនចង់ប្តូរពាក្យសម្ងាត់'],
    'create_user_btn'    => ['en' => 'Create User', 'kh' => 'បង្កើតអ្នកប្រើ'],
    'save_user_changes'  => ['en' => 'Save User Changes', 'kh' => 'រក្សាទុកការកែប្រែ'],
    'no_users_found'     => ['en' => 'No users found', 'kh' => 'រកមិនឃើញអ្នកប្រើប្រាស់ទេ'],

    // Auth
    'signin_title'       => ['en' => 'Sign in to EventHub', 'kh' => 'ចូលទៅកាន់ EventHub'],
    'signin_subtitle'    => ['en' => 'Enter your account details to access your dashboard', 'kh' => 'បញ្ចូលព័ត៌មានគណនីដើម្បីចូលទៅកាន់ផ្ទាំងគ្រប់គ្រង'],
    'signin_btn'         => ['en' => 'Sign In to Account', 'kh' => 'ចូលគណនី'],
    'signup_title'       => ['en' => 'Create an Account', 'kh' => 'បង្កើតគណនីថ្មី'],
    'signup_subtitle'    => ['en' => 'Join EventHub to register and participate in events', 'kh' => 'ចូលរួមជាមួយ EventHub ដើម្បីចុះឈ្មោះ និងចូលរួមព្រឹត្តិការណ៍នានា'],
    'signup_btn'         => ['en' => 'Create My Account', 'kh' => 'បង្កើតគណនីរបស់ខ្ញុំ'],
    'already_have_account' => ['en' => 'Already have an account?', 'kh' => 'មានគណនីរួចហើយ?'],
    'dont_have_account'  => ['en' => 'Do not have an account yet?', 'kh' => 'មិនទាន់មានគណនីមែនទេ?'],
    'demo_accounts'      => ['en' => 'Demo Accounts Available:', 'kh' => 'គណនីសាកល្បង៖'],

    // SweetAlert & Confirmations
    'confirm_title'      => ['en' => 'Are you sure?', 'kh' => 'តើអ្នកប្រាកដដែរឬទេ?'],
    'confirm_yes'        => ['en' => 'Yes, proceed!', 'kh' => 'យល់ព្រមបន្ត!'],
    'confirm_cancel'     => ['en' => 'Cancel', 'kh' => 'បោះបង់'],
    'delete_event_confirm'=> ['en' => 'All registrations for this event will also be deleted.', 'kh' => 'រាល់ការចុះឈ្មោះសម្រាប់ព្រឹត្តិការណ៍នេះក៏នឹងត្រូវលុបផងដែរ។'],
    'cancel_reg_confirm' => ['en' => 'Your spot will be released for other attendees.', 'kh' => 'កន្លែងរបស់អ្នកនឹងត្រូវផ្ទេរជូនអ្នកដទៃ។'],
    'logout_confirm'     => ['en' => 'Do you really want to log out of your session?', 'kh' => 'តើអ្នកពិតជាចង់ចាកចេញពីគណនីមែនទេ?']
];

/**
 * Translation helper function
 */
function __($key, $default = null) {
    global $translations, $currentLang;
    if (isset($translations[$key][$currentLang])) {
        return $translations[$key][$currentLang];
    }
    if (isset($translations[$key]['en'])) {
        return $translations[$key]['en'];
    }
    return $default !== null ? $default : $key;
}

/**
 * Returns current language code ('en' or 'kh')
 */
function getLang() {
    global $currentLang;
    return $currentLang === 'kh' ? 'kh' : 'en';
}
