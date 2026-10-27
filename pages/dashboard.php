<?php
$pageTitle = $isAdmin ? __('nav_dashboard') : __('dash_welcome');
?>

<?php if ($isAdmin): ?>
    <?php $stats = getEventStats(); ?>
    <div class="space-y-8">
        
        <!-- Dashboard Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                    <span><?= __('dash_overview') ?></span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300">Live</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('app_tagline') ?></p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="./?page=event/create" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition-all duration-150 transform active:scale-95">
                    <?= getIcon('calendar-plus', 'w-4 h-4') ?>
                    <span><?= __('nav_new_event') ?></span>
                </a>
                <a href="./?page=user/list" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200/90 dark:border-slate-700 shadow-xs transition">
                    <?= getIcon('users', 'w-4 h-4') ?>
                    <span><?= __('nav_users') ?></span>
                </a>
            </div>
        </div>

        <!-- 4 Stats Counter Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Events -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= __('dash_total_events') ?></span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <?= getIcon('calendar', 'w-5 h-5') ?>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white"><?= $stats->total_events ?></span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Active</span>
                </div>
                <div class="mt-3 text-xs text-slate-400">
                    <a href="./?page=events" class="text-emerald-600 dark:text-emerald-400 hover:underline font-medium inline-flex items-center gap-1">
                        <?= __('events_title') ?> <?= getIcon('arrow-right', 'w-3 h-3') ?>
                    </a>
                </div>
            </div>

            <!-- Total Registrations -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= __('dash_total_regs') ?></span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <?= getIcon('ticket', 'w-5 h-5') ?>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white"><?= $stats->total_registrations ?></span>
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Total</span>
                </div>
                <div class="mt-3 text-xs text-slate-400">
                    <span><?= $stats->approved ?> <?= __('approved') ?></span>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:shadow-md transition-shadow duration-200 <?= $stats->pending > 0 ? 'ring-2 ring-amber-400/50 bg-amber-50/20 dark:bg-amber-950/20' : '' ?>">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= __('dash_pending') ?></span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <?= getIcon('clock', 'w-5 h-5') ?>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold <?= $stats->pending > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' ?>"><?= $stats->pending ?></span>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Review</span>
                </div>
                <div class="mt-3 text-xs text-slate-400">
                    <span>Awaiting decision</span>
                </div>
            </div>

            <!-- Registered Users -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= __('dash_users') ?></span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <?= getIcon('users', 'w-5 h-5') ?>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white"><?= $stats->total_users ?></span>
                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Members</span>
                </div>
                <div class="mt-3 text-xs text-slate-400">
                    <a href="./?page=user/list" class="text-purple-600 dark:text-purple-400 hover:underline font-medium inline-flex items-center gap-1">
                        <?= __('nav_users') ?> <?= getIcon('arrow-right', 'w-3 h-3') ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2-Column Main Section: Upcoming Events + Recent Registrations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Upcoming Events Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <?= getIcon('calendar', 'w-4 h-4') ?>
                        </div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-base"><?= __('dash_upcoming') ?></h2>
                    </div>
                    <a href="./?page=events" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                        <?= __('all') ?> <?= getIcon('arrow-right', 'w-3 h-3') ?>
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700/80 flex-grow">
                    <?php
                    $events = getAllEvents(null, 'upcoming');
                    $count = 0;
                    if ($events):
                        while ($row = $events->fetch_object()):
                            if ($count >= 4) break;
                            $count++;
                            $pct = min(100, round(($row->reg_count / max(1, $row->capacity)) * 100));
                            $isFull = $row->reg_count >= $row->capacity;
                    ?>
                        <div class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-slate-50/80 dark:hover:bg-slate-750/50 transition-colors">
                            <div class="min-w-0 flex-1">
                                <a href="./?page=event/detail&id=<?= $row->id ?>" class="font-semibold text-slate-800 dark:text-slate-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition truncate block text-sm sm:text-base">
                                    <?= htmlspecialchars($row->title) ?>
                                </a>
                                <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="flex items-center gap-1">
                                        <?= getIcon('clock', 'w-3.5 h-3.5 text-slate-400') ?>
                                        <?= date('M d, Y &bull; h:i A', strtotime($row->event_date)) ?>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <?= getIcon('map-pin', 'w-3.5 h-3.5 text-slate-400') ?>
                                        <?= htmlspecialchars($row->location) ?>
                                    </span>
                                </div>
                                <div class="mt-2.5 flex items-center gap-3 max-w-xs">
                                    <div class="flex-grow h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full <?= $isFull ? 'bg-red-500' : 'bg-emerald-500' ?>" style="width: <?= $pct ?>%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        <?= $row->reg_count ?> / <?= $row->capacity ?>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="./?page=event/registrations&id=<?= $row->id ?>" 
                                   class="p-2 text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-xl transition" 
                                   title="<?= __('manage_regs') ?>">
                                    <?= getIcon('users', 'w-4 h-4') ?>
                                </a>
                                <a href="./?page=event/edit&id=<?= $row->id ?>" 
                                   class="p-2 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-xl transition" 
                                   title="<?= __('edit') ?>">
                                    <?= getIcon('pencil', 'w-4 h-4') ?>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>

                    <?php if ($count === 0): ?>
                        <div class="p-8 text-center text-slate-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400 flex items-center justify-center mb-2">
                                <?= getIcon('calendar', 'w-6 h-6') ?>
                            </div>
                            <p class="text-sm font-medium"><?= __('no_events_found') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Registration Activity -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <?= getIcon('ticket', 'w-4 h-4') ?>
                        </div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-base"><?= __('dash_recent_activity') ?></h2>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700/80 flex-grow">
                    <?php
                    $latestRegsQuery = $db->query(
                        'SELECT r.*, u.name, u.username, u.photo, e.title as event_title
                         FROM tbl_registrations r
                         JOIN tbl_users u ON u.id = r.user_id
                         JOIN tbl_events e ON e.id = r.event_id
                         ORDER BY r.created_at DESC
                         LIMIT 5'
                    );
                    $regCount = 0;
                    if ($latestRegsQuery):
                        while ($reg = $latestRegsQuery->fetch_object()):
                            $regCount++;
                            $statusBg = match($reg->status) {
                                'approved' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/60',
                                'rejected' => 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-300 border-red-200/60 dark:border-red-800/60',
                                default    => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/60'
                            };
                    ?>
                        <div class="p-4 sm:p-5 flex items-center justify-between gap-3 hover:bg-slate-50/80 dark:hover:bg-slate-750/50 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="<?= htmlspecialchars($reg->photo ?: './assets/images/Profile_PNG.png') ?>" 
                                     alt="<?= htmlspecialchars($reg->name) ?>"
                                     class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 shrink-0"
                                     onerror="this.src='./assets/images/Profile_PNG.png'">
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-800 dark:text-slate-100 text-xs sm:text-sm truncate"><?= htmlspecialchars($reg->name) ?></div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">For: <span class="font-medium text-slate-700 dark:text-slate-300"><?= htmlspecialchars($reg->event_title) ?></span></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5"><?= date('M d, H:i', strtotime($reg->created_at)) ?></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize border <?= $statusBg ?>">
                                    <?= __($reg->status) ?>
                                </span>

                                <?php if ($reg->status === 'pending'): ?>
                                    <form method="post" action="./?page=event/registrations&id=<?= $reg->event_id ?>" class="inline-flex items-center gap-1">
                                        <input type="hidden" name="reg_id" value="<?= $reg->id ?>">
                                        <button type="submit" name="status" value="approved" class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition" title="<?= __('approved') ?>">
                                            <?= getIcon('check', 'w-3.5 h-3.5') ?>
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="p-1.5 rounded-lg bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 hover:bg-red-100 transition" title="<?= __('rejected') ?>">
                                            <?= getIcon('x', 'w-3.5 h-3.5') ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>

                    <?php if ($regCount === 0): ?>
                        <div class="p-8 text-center text-slate-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400 flex items-center justify-center mb-2">
                                <?= getIcon('ticket', 'w-6 h-6') ?>
                            </div>
                            <p class="text-sm font-medium">No registrations received yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

<?php else: ?>
    <!-- ==================== USER DASHBOARD ==================== -->
    <div class="space-y-8">
        
        <!-- Welcome Banner Card -->
        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-emerald-900/10">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="<?= htmlspecialchars($user->photo ?: './assets/images/Profile_PNG.png') ?>" 
                         class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white/20 shadow-md shrink-0" 
                         alt="<?= htmlspecialchars($user->name) ?>"
                         onerror="this.src='./assets/images/Profile_PNG.png'">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider px-2 py-0.5 rounded-md bg-white/20 text-white backdrop-blur-xs">Member</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-1">
                            <?= __('dash_welcome') ?>, <?= htmlspecialchars($user->name) ?>!
                        </h1>
                        <p class="text-emerald-100 text-sm mt-1"><?= __('events_subtitle') ?></p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="./?page=events" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs bg-white text-emerald-800 hover:bg-emerald-50 shadow-md shadow-emerald-900/20 transition-all duration-150 transform active:scale-95">
                        <?= getIcon('calendar', 'w-4 h-4 text-emerald-600') ?>
                        <span><?= __('nav_explore') ?></span>
                    </a>
                    <a href="./?page=my-registrations" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs bg-emerald-600/40 hover:bg-emerald-600/60 border border-white/20 text-white transition-all duration-150">
                        <?= getIcon('ticket', 'w-4 h-4') ?>
                        <span><?= __('nav_my_bookings') ?></span>
                    </a>
                </div>
            </div>
            
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- 2 Column Layout: User's Registrations & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: My Registrations Summary -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <?= getIcon('ticket', 'w-4 h-4') ?>
                        </div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-base"><?= __('nav_my_bookings') ?></h2>
                    </div>
                    <a href="./?page=my-registrations" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                        <?= __('all') ?> <?= getIcon('arrow-right', 'w-3 h-3') ?>
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700/80 flex-grow">
                    <?php
                    $myRegs = getMyRegistrations($user->id);
                    $myCount = 0;
                    if ($myRegs):
                        while ($reg = $myRegs->fetch_object()):
                            $myCount++;
                            if ($myCount > 4) break;
                            $statusClass = match($reg->status) {
                                'approved' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/60',
                                'rejected' => 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-300 border-red-200/60 dark:border-red-800/60',
                                default    => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/60'
                            };
                    ?>
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/80 dark:hover:bg-slate-750/50 transition-colors">
                            <div class="flex items-start gap-4 min-w-0">
                                <img src="<?= htmlspecialchars($reg->image ?: './assets/images/loading.png') ?>" 
                                     alt="<?= htmlspecialchars($reg->title) ?>"
                                     class="w-14 h-14 rounded-xl object-cover shrink-0 ring-1 ring-slate-200 dark:ring-slate-700"
                                     onerror="this.src='./assets/images/loading.png'">
                                <div class="min-w-0">
                                    <a href="./?page=event/detail&id=<?= $reg->event_id ?>" class="font-bold text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition truncate block text-sm sm:text-base">
                                        <?= htmlspecialchars($reg->title) ?>
                                    </a>
                                    <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <?= getIcon('clock', 'w-3.5 h-3.5 text-slate-400') ?>
                                            <?= date('M d, Y &bull; h:i A', strtotime($reg->event_date)) ?>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <?= getIcon('map-pin', 'w-3.5 h-3.5 text-slate-400') ?>
                                            <?= htmlspecialchars($reg->location) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize border <?= $statusClass ?>">
                                    <?= __($reg->status) ?>
                                </span>
                                <a href="./?page=event/detail&id=<?= $reg->event_id ?>" class="px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                                    <?= __('view') ?>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>

                    <?php if ($myCount === 0): ?>
                        <div class="p-10 text-center text-slate-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400 flex items-center justify-center mb-3">
                                <?= getIcon('calendar-x', 'w-6 h-6') ?>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200"><?= __('no_events_found') ?></h3>
                            <a href="./?page=events" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                                <?= getIcon('calendar', 'w-3.5 h-3.5') ?> <?= __('nav_explore') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Col: Shortcuts -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs p-6">
                    <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4 flex items-center gap-2">
                        <?= getIcon('user', 'w-4 h-4 text-emerald-600') ?>
                        <span><?= __('dash_quick_shortcuts') ?></span>
                    </h3>

                    <div class="space-y-2.5">
                        <a href="./?page=events" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100/70 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <?= getIcon('calendar', 'w-4 h-4') ?>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-emerald-700"><?= __('nav_explore') ?></div>
                                </div>
                            </div>
                            <?= getIcon('arrow-right', 'w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-transform') ?>
                        </a>

                        <a href="./?page=my-registrations" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-blue-50/50 dark:hover:bg-blue-950/20 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100/70 dark:bg-blue-900/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <?= getIcon('ticket', 'w-4 h-4') ?>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-blue-700"><?= __('nav_my_bookings') ?></div>
                                </div>
                            </div>
                            <?= getIcon('arrow-right', 'w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-transform') ?>
                        </a>

                        <a href="./?page=profile" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-purple-200 dark:hover:border-purple-800 hover:bg-purple-50/50 dark:hover:bg-purple-950/20 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100/70 dark:bg-purple-900/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                                    <?= getIcon('lock', 'w-4 h-4') ?>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-purple-700"><?= __('nav_profile') ?></div>
                                </div>
                            </div>
                            <?= getIcon('arrow-right', 'w-4 h-4 text-slate-400 group-hover:text-purple-600 transition-transform') ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
<?php endif; ?>
