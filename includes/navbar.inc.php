<?php
$currentPage = $_GET['page'] ?? ($user ? 'dashboard' : 'events');
$currentLang = getLang();
?>

<?php if ($isAdmin): ?>
    <!-- ==================== ADMIN TOP HEADER BAR ==================== -->
    <header class="sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/90 dark:border-slate-800 shadow-xs h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-colors duration-200">
        
        <!-- Left: Mobile Sidebar Drawer Toggle + Desktop Sidebar Collapse/Expand Toggle + Search Shortcut -->
        <div class="flex items-center gap-2.5">
            <!-- Mobile Drawer Open Button -->
            <button id="openAdminSidebar" 
                    type="button" 
                    class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 lg:hidden transition"
                    title="Open sidebar">
                <?= getIcon('menu', 'w-5 h-5') ?>
            </button>

            <!-- Mobile Brand Logo -->
            <a href="./?page=dashboard" class="flex lg:hidden items-center gap-2" title="EventHub Pro">
                <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-8 h-8 rounded-xl object-cover">
            </a>

            <!-- Desktop Collapse / Expand Sidebar Button -->
            <button id="toggleAdminSidebarDesktop" 
                    type="button" 
                    class="hidden lg:flex p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700 transition"
                    title="Toggle sidebar">
                <?= getIcon('sidebar', 'w-4 h-4 text-current') ?>
            </button>

            <!-- Quick Search Link -->
            <a href="./?page=events" class="hidden sm:flex items-center gap-2 text-xs text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200/80 dark:border-slate-700 transition">
                <?= getIcon('search', 'w-3.5 h-3.5 text-current') ?>
                <span><?= __('search_placeholder') ?></span>
            </a>
        </div>

        <!-- Right: Language Switcher, Dark Mode, Profile -->
        <div class="flex items-center gap-3">
            
            <!-- Language Pill Toggle (EN / KH) -->
            <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs font-semibold">
                <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'en'])) ?>" 
                   class="px-2.5 py-1 rounded-lg transition <?= $currentLang === 'en' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>"
                   title="English">
                    EN
                </a>
                <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'kh'])) ?>" 
                   class="px-2.5 py-1 rounded-lg transition <?= $currentLang === 'kh' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>"
                   title="ភាសាខ្មែរ">
                    KH
                </a>
            </div>

            <!-- Dark / Light Mode Toggle Button (Font color aligned) -->
            <button type="button" 
                    id="themeToggleBtnAdmin"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200/70 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700 transition"
                    title="<?= __('dark_mode') ?>">
                <span class="dark:hidden flex items-center gap-1.5 text-current">
                    <?= getIcon('moon', 'w-4 h-4 text-current') ?>
                    <span class="hidden sm:inline font-medium text-current"><?= __('dark_mode') ?></span>
                </span>
                <span class="hidden dark:flex items-center gap-1.5 text-current">
                    <?= getIcon('sun', 'w-4 h-4 text-current') ?>
                    <span class="hidden sm:inline font-medium text-current"><?= __('light_mode') ?></span>
                </span>
            </button>

            <!-- Admin Profile Link -->
            <a href="./?page=profile" class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <img class="w-8 h-8 rounded-xl object-cover ring-2 ring-emerald-500/40" 
                     src="<?= htmlspecialchars($user->photo ?: './assets/images/Profile_PNG.png') ?>" 
                     alt="<?= htmlspecialchars($user->name) ?>"
                     onerror="this.src='./assets/images/Profile_PNG.png'">
            </a>

        </div>

    </header>

<?php else: ?>
    <!-- ==================== USER / GUEST TOP NAVBAR ==================== -->
    <nav class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 shadow-xs transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left: Logo & Brand -->
                <div class="flex items-center gap-8">
                    <a href="./" class="flex items-center gap-2.5 group">
                        <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-9 h-9 rounded-xl object-cover">
                        <div>
                            <span class="text-xl font-extrabold text-slate-900 dark:text-white">Event<span class="text-emerald-600 dark:text-emerald-400">Hub</span></span>
                            <span class="hidden sm:inline-block text-[10px] tracking-wider uppercase font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded-md ml-1.5 border border-emerald-200/60 dark:border-emerald-800/60">Pro</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex items-center gap-1">
                        <?php if ($user): ?>
                            <a href="./?page=dashboard" 
                               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'dashboard' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                                <?= getIcon('dashboard', 'w-4 h-4') ?>
                                <span><?= __('nav_dashboard') ?></span>
                            </a>
                        <?php endif; ?>

                        <a href="./?page=events" 
                           class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-150 <?= ($currentPage === 'events' || str_starts_with($currentPage, 'event/detail')) ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                            <?= getIcon('calendar', 'w-4 h-4') ?>
                            <span><?= __('nav_explore') ?></span>
                        </a>

                        <?php if ($user): ?>
                            <a href="./?page=my-registrations" 
                               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-150 <?= $currentPage === 'my-registrations' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                                <?= getIcon('ticket', 'w-4 h-4') ?>
                                <span><?= __('nav_my_bookings') ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right: Controls & Profile -->
                <div class="hidden md:flex items-center gap-3">
                    
                    <!-- Language Switcher (EN / KH) -->
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs font-semibold">
                        <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'en'])) ?>" 
                           class="px-2.5 py-1 rounded-lg transition <?= $currentLang === 'en' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>"
                           title="English">
                            EN
                        </a>
                        <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'kh'])) ?>" 
                           class="px-2.5 py-1 rounded-lg transition <?= $currentLang === 'kh' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>"
                           title="ភាសាខ្មែរ">
                            KH
                        </a>
                    </div>

                    <!-- Dark Mode Toggle Button (Font color aligned) -->
                    <button type="button" 
                            id="themeToggleBtnNavbar"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200/70 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700 transition"
                            title="<?= __('dark_mode') ?>">
                        <span class="dark:hidden flex items-center gap-1.5 text-current">
                            <?= getIcon('moon', 'w-4 h-4 text-current') ?>
                            <span class="hidden sm:inline font-medium text-current"><?= __('dark_mode') ?></span>
                        </span>
                        <span class="hidden dark:flex items-center gap-1.5 text-current">
                            <?= getIcon('sun', 'w-4 h-4 text-current') ?>
                            <span class="hidden sm:inline font-medium text-current"><?= __('light_mode') ?></span>
                        </span>
                    </button>

                    <?php if (empty($user)): ?>
                        <a href="./?page=login" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-emerald-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <?= getIcon('login', 'w-4 h-4') ?>
                            <span><?= __('nav_login') ?></span>
                        </a>
                        <a href="./?page=register" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-sm shadow-emerald-500/25 transition">
                            <?= getIcon('user-plus', 'w-4 h-4') ?>
                            <span><?= __('nav_register') ?></span>
                        </a>
                    <?php else: ?>
                        <!-- User Menu Dropdown Trigger -->
                        <div class="relative" id="userMenuWrapper">
                            <button type="button" 
                                    id="userMenuButton"
                                    class="flex items-center p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition border border-slate-200/80 dark:border-slate-700 focus:outline-hidden"
                                    title="<?= htmlspecialchars($user->name) ?>">
                                <img class="w-8 h-8 rounded-xl object-cover ring-2 ring-emerald-500/30" 
                                     src="<?= htmlspecialchars($user->photo ?: './assets/images/Profile_PNG.png') ?>" 
                                     alt="<?= htmlspecialchars($user->name) ?>"
                                     onerror="this.src='./assets/images/Profile_PNG.png'">
                            </button>

                            <!-- Dropdown Panel -->
                            <div id="userMenuDropdown" 
                                 class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-slate-200/80 dark:border-slate-700 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700">
                                    <p class="text-[11px] text-slate-400">@<?= htmlspecialchars($user->username) ?></p>
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate"><?= htmlspecialchars($user->name) ?></p>
                                </div>

                                <div class="py-1">
                                    <a href="./?page=profile" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-700 transition">
                                        <?= getIcon('user', 'w-4 h-4 text-slate-400') ?>
                                        <span><?= __('nav_profile') ?></span>
                                    </a>
                                    <a href="./?page=my-registrations" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-700 transition">
                                        <?= getIcon('ticket', 'w-4 h-4 text-slate-400') ?>
                                        <span><?= __('nav_my_bookings') ?></span>
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 dark:border-slate-700 pt-1">
                                    <a href="./?page=logout" 
                                       class="confirm-action flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition"
                                       data-confirm-title="<?= __('confirm_title') ?>"
                                       data-confirm-text="<?= __('logout_confirm') ?>"
                                       data-confirm-btn="<?= __('nav_logout') ?>"
                                       data-confirm-icon="question">
                                        <?= getIcon('logout', 'w-4 h-4') ?>
                                        <span><?= __('nav_logout') ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="flex md:hidden items-center gap-2">
                    <button id="mobileMenuBtn" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <?= getIcon('menu', 'w-6 h-6') ?>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobileMenuDropdown" class="hidden md:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-3 pb-6 space-y-3">
            <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-7 h-7 rounded-lg object-cover">
                <span class="text-sm font-extrabold text-slate-900 dark:text-white">Event<span class="text-emerald-600">Hub</span> <span class="text-[10px] uppercase font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/60 px-1 py-0.5 rounded">Pro</span></span>
            </div>
            <a href="./?page=events" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                <?= getIcon('calendar', 'w-4 h-4 text-emerald-600') ?>
                <span><?= __('nav_explore') ?></span>
            </a>
            <?php if ($user): ?>
                <a href="./?page=my-registrations" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <?= getIcon('ticket', 'w-4 h-4 text-emerald-600') ?>
                    <span><?= __('nav_my_bookings') ?></span>
                </a>
                <a href="./?page=profile" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <?= getIcon('user', 'w-4 h-4 text-emerald-600') ?>
                    <span><?= __('nav_profile') ?></span>
                </a>
            <?php endif; ?>

            <!-- Language & Dark Mode in Mobile Menu -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-750 flex items-center justify-between">
                <div class="flex items-center gap-1 text-xs font-semibold">
                    <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'en'])) ?>" class="px-2.5 py-1 rounded-lg <?= $currentLang === 'en' ? 'bg-emerald-600 text-white' : 'text-slate-600 dark:text-slate-400' ?>">EN</a>
                    <a href="?<?= http_build_query(array_merge($_GET, ['lang' => 'kh'])) ?>" class="px-2.5 py-1 rounded-lg <?= $currentLang === 'kh' ? 'bg-emerald-600 text-white font-bold' : 'text-slate-600 dark:text-slate-400' ?>">KH</a>
                </div>
                <button type="button" 
                        id="themeToggleBtnMobile" 
                        class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="dark:hidden flex items-center gap-1.5 text-current">
                        <?= getIcon('moon', 'w-4 h-4 text-current') ?>
                        <span class="font-medium text-current"><?= __('dark_mode') ?></span>
                    </span>
                    <span class="hidden dark:flex items-center gap-1.5 text-current">
                        <?= getIcon('sun', 'w-4 h-4 text-current') ?>
                        <span class="font-medium text-current"><?= __('light_mode') ?></span>
                    </span>
                </button>
            </div>

            <div class="pt-2">
                <?php if (empty($user)): ?>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="./?page=login" class="text-center py-2 px-4 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50">
                            <?= __('nav_login') ?>
                        </a>
                        <a href="./?page=register" class="text-center py-2 px-4 rounded-xl bg-emerald-600 text-xs font-semibold text-white shadow-xs">
                            <?= __('nav_register') ?>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="./?page=logout" 
                       class="confirm-action flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40"
                       data-confirm-title="<?= __('confirm_title') ?>"
                       data-confirm-btn="<?= __('nav_logout') ?>"
                       data-confirm-icon="question">
                        <?= getIcon('logout', 'w-4 h-4') ?>
                        <span><?= __('nav_logout') ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<?php endif; ?>
