<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$pendingCount = 0;
if (isset($db)) {
    $r = $db->query("SELECT COUNT(*) as cnt FROM tbl_registrations WHERE status = 'pending'");
    if ($r && $row = $r->fetch_object()) {
        $pendingCount = (int)$row->cnt;
    }
}
$currentLang = getLang();
?>

<!-- Mobile Sidebar Overlay Backdrop -->
<div id="adminSidebarBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

<!-- Admin Sidebar Aside -->
<aside id="adminSidebar" 
       class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200/90 dark:border-slate-800 flex flex-col justify-between transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0">
    
    <div class="flex flex-col min-h-0 flex-1">
        
        <!-- Brand Header & Collapse Toggle Button -->
        <div class="h-16 flex items-center justify-between px-3.5 border-b border-slate-100 dark:border-slate-800 shrink-0 overflow-hidden">
            <!-- Brand Link -->
            <a href="./?page=dashboard" class="flex items-center gap-2.5 group overflow-hidden" title="EventHub Pro">
                <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-9 h-9 rounded-xl object-cover shrink-0">
                <div class="sidebar-label whitespace-nowrap overflow-hidden">
                    <span class="text-base font-extrabold text-slate-900 dark:text-white">Event<span class="text-emerald-600 dark:text-emerald-400">Hub</span></span>
                    <span class="text-[10px] tracking-wider uppercase font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded-md ml-1 border border-emerald-200/60 dark:border-emerald-800/60">Admin</span>
                </div>
            </a>

            <!-- Desktop Collapse / Expand Toggle Button -->
            <button id="toggleSidebarCollapseBtn" 
                    type="button" 
                    class="hidden lg:flex items-center justify-center p-1.5 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition shrink-0"
                    title="Toggle sidebar size">
                <span class="sidebar-collapse-icon-collapse">
                    <?= getIcon('chevron-double-left', 'w-4 h-4 text-current') ?>
                </span>
                <span class="sidebar-collapse-icon-expand hidden">
                    <?= getIcon('chevron-double-right', 'w-4 h-4 text-current') ?>
                </span>
            </button>

            <!-- Mobile Close Drawer Button -->
            <button id="closeAdminSidebar" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 lg:hidden">
                <?= getIcon('x', 'w-5 h-5') ?>
            </button>
        </div>

        <!-- Admin Profile Mini-Badge -->
        <div class="sidebar-user-box p-3.5 mx-3 my-3 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-100 dark:border-slate-750 flex items-center gap-3 shrink-0 transition-all">
            <a href="./?page=profile" title="<?= htmlspecialchars($user->name) ?>" class="shrink-0 block">
                <img src="<?= htmlspecialchars($user->photo ?: './assets/images/Profile_PNG.png') ?>" 
                     alt="<?= htmlspecialchars($user->name) ?>"
                     class="w-9 h-9 rounded-xl object-cover ring-2 ring-emerald-500/30"
                     onerror="this.src='./assets/images/Profile_PNG.png'">
            </a>
            <div class="sidebar-label min-w-0 flex-1 overflow-hidden">
                <div class="text-xs font-bold text-slate-900 dark:text-white truncate"><?= htmlspecialchars($user->name) ?></div>
                <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold uppercase flex items-center gap-1">
                    <?= getIcon('shield', 'w-3 h-3') ?>
                    <span><?= htmlspecialchars($user->level) ?></span>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="px-2.5 space-y-5 overflow-y-auto overflow-x-hidden flex-1 py-1">
            
            <!-- MAIN SECTION -->
            <div>
                <p class="sidebar-label px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5"><?= __('menu_main') ?></p>
                <div class="sidebar-divider hidden border-t border-slate-100 dark:border-slate-800 my-2 mx-1"></div>
                <div class="space-y-1">
                    <a href="./?page=dashboard" 
                       title="<?= __('nav_dashboard') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'dashboard' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('dashboard', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_dashboard') ?></span>
                    </a>

                    <a href="./?page=events" 
                       title="<?= __('nav_explore') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= ($currentPage === 'events' || str_starts_with($currentPage, 'event/detail')) ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('calendar', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_explore') ?></span>
                    </a>

                    <a href="./?page=my-registrations" 
                       title="<?= __('nav_my_bookings') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'my-registrations' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('ticket', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_my_bookings') ?></span>
                    </a>
                </div>
            </div>

            <!-- ADMINISTRATION SECTION -->
            <div>
                <p class="sidebar-label px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5"><?= __('menu_admin') ?></p>
                <div class="sidebar-divider hidden border-t border-slate-100 dark:border-slate-800 my-2 mx-1"></div>
                <div class="space-y-1">
                    <a href="./?page=event/create" 
                       title="<?= __('nav_new_event') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= ($currentPage === 'event/create' || $currentPage === 'event/create_event') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('calendar-plus', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_new_event') ?></span>
                    </a>

                    <a href="./?page=user/list" 
                       title="<?= __('nav_users') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'user/list' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('users', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_users') ?></span>
                    </a>

                    <a href="./?page=user/create" 
                       title="<?= __('nav_new_user') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'user/create' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('user-plus', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_new_user') ?></span>
                    </a>

                    <?php if ($pendingCount > 0): ?>
                        <div class="pt-1">
                            <a href="./?page=dashboard" 
                               title="<?= __('dash_pending') ?>: <?= $pendingCount ?>"
                               class="sidebar-nav-link relative flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/80">
                                <span class="flex items-center gap-2.5">
                                    <span class="relative">
                                        <?= getIcon('clock', 'w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0') ?>
                                        <span class="lg:hidden absolute -top-1 -right-1 w-2 h-2 rounded-full bg-amber-500"></span>
                                    </span>
                                    <span class="sidebar-label truncate"><?= __('dash_pending') ?></span>
                                </span>
                                <span class="sidebar-label px-2 py-0.5 rounded-full text-[10px] bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-200 font-extrabold"><?= $pendingCount ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SETTINGS SECTION -->
            <div>
                <p class="sidebar-label px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5"><?= __('menu_settings') ?></p>
                <div class="sidebar-divider hidden border-t border-slate-100 dark:border-slate-800 my-2 mx-1"></div>
                <div class="space-y-1">
                    <a href="./?page=profile" 
                       title="<?= __('nav_profile') ?>"
                       class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'profile' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <?= getIcon('user', 'w-5 h-5 shrink-0') ?>
                        <span class="sidebar-label truncate"><?= __('nav_profile') ?></span>
                    </a>
                </div>
            </div>

        </nav>
    </div>

    <!-- Sidebar Bottom: Controls (Aligned Dark Mode, KH/EN Language, Logout) -->
    <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/95 space-y-2 shrink-0">
        
        <!-- Language Switcher & Dark Mode Container -->
        <div class="p-1 bg-white dark:bg-slate-800/90 rounded-xl border border-slate-200/80 dark:border-slate-700 flex flex-col gap-1">
            
            <!-- Language Pill Toggle -->
            <div class="flex items-center justify-around gap-1 text-xs font-semibold p-0.5">
                <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'en'])) ?>" 
                   class="flex-1 text-center py-1 rounded-lg transition <?= $currentLang === 'en' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>"
                   title="English">
                    EN
                </a>
                <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'kh'])) ?>" 
                   class="flex-1 text-center py-1 rounded-lg transition <?= $currentLang === 'kh' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>"
                   title="ភាសាខ្មែរ">
                    KH
                </a>
            </div>

            <!-- Dark / Light Mode Toggle Button (Font color aligned with icon and text) -->
            <button type="button" 
                    id="themeToggleBtnSidebar"
                    class="sidebar-nav-link flex items-center justify-center lg:justify-start gap-2.5 w-full px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition"
                    title="<?= __('dark_mode') ?>">
                <span class="dark:hidden flex items-center gap-2.5 text-current shrink-0">
                    <?= getIcon('moon', 'w-4 h-4 text-current shrink-0') ?>
                    <span class="sidebar-label font-medium text-current truncate"><?= __('dark_mode') ?></span>
                </span>
                <span class="hidden dark:flex items-center gap-2.5 text-current shrink-0">
                    <?= getIcon('sun', 'w-4 h-4 text-current shrink-0') ?>
                    <span class="sidebar-label font-medium text-current truncate"><?= __('light_mode') ?></span>
                </span>
            </button>
        </div>

        <!-- Logout Button -->
        <a href="./?page=logout" 
           title="<?= __('nav_logout') ?>"
           class="sidebar-nav-link confirm-action flex items-center justify-center gap-2 w-full py-2 px-3 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition"
           data-confirm-title="<?= __('confirm_title') ?>"
           data-confirm-text="<?= __('logout_confirm') ?>"
           data-confirm-btn="<?= __('nav_logout') ?>"
           data-confirm-icon="question">
            <?= getIcon('logout', 'w-4 h-4 shrink-0') ?>
            <span class="sidebar-label truncate"><?= __('nav_logout') ?></span>
        </a>
    </div>

</aside>
