<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    setFlash('error', 'Event not found or has been deleted.', 'Not Found');
    redirect('./?page=events');
}

$isFull        = $event->reg_count >= $event->capacity;
$isRegistered  = $user ? isUserRegistered($event->id, $user->id) : false;
$userReg       = $isRegistered ? getUserRegistration($event->id, $user->id) : null;
$pct           = min(100, round(($event->reg_count / max(1, $event->capacity)) * 100));
$imgSrc        = $event->image ? htmlspecialchars($event->image) : './assets/images/loading.png';
$dateStr       = date('l, F d, Y', strtotime($event->event_date));
$timeStr       = date('h:i A', strtotime($event->event_date));
$isPast        = strtotime($event->event_date) < time();
?>

<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Top Back Navigation -->
    <div>
        <a href="./?page=events" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition">
            <?= getIcon('arrow-left', 'w-4 h-4') ?>
            <span><?= __('back_to_events') ?></span>
        </a>
    </div>

    <!-- 2 Column Layout: Details + Action Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Hero Banner, Title & Description -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Banner Image Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm overflow-hidden">
                <div class="relative h-64 sm:h-80 bg-slate-100 dark:bg-slate-700/50">
                    <img src="<?= $imgSrc ?>" 
                         alt="<?= htmlspecialchars($event->title) ?>"
                         class="w-full h-full object-cover"
                         onerror="this.src='./assets/images/loading.png'">
                    
                    <div class="absolute top-4 right-4">
                        <?php if ($isPast): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-900/80 text-white backdrop-blur-xs shadow-md">
                                <?= __('concluded') ?>
                            </span>
                        <?php elseif ($isFull): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-600 text-white shadow-md">
                                <?= __('sold_out') ?>
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                                <?= $event->capacity - $event->reg_count ?> <?= __('seats_available') ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug">
                        <?= htmlspecialchars($event->title) ?>
                    </h1>

                    <!-- Key Event Metadata Pills -->
                    <div class="flex flex-wrap items-center gap-4 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/60 text-xs text-slate-600 dark:text-slate-300">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <?= getIcon('calendar', 'w-4 h-4') ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 dark:text-slate-200"><?= $dateStr ?></div>
                                <div class="text-[11px] text-slate-400 dark:text-slate-400"><?= $timeStr ?></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                <?= getIcon('map-pin', 'w-4 h-4') ?>
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($event->location) ?></div>
                                <div class="text-[11px] text-slate-400 dark:text-slate-400"><?= __('venue_location') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/60">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white mb-3"><?= __('about_event') ?></h2>
                        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line font-normal">
                            <?= htmlspecialchars($event->description) ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Right Col: Booking Card & Status -->
        <div class="space-y-6">
            
            <!-- Registration / Action Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm p-6 sm:p-7 sticky top-24">
                
                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4 flex items-center gap-2">
                    <?= getIcon('ticket', 'w-5 h-5 text-emerald-600 dark:text-emerald-400') ?>
                    <span><?= __('event_info') ?></span>
                </h3>

                <!-- Capacity meter -->
                <div class="mb-6 bg-slate-50 dark:bg-slate-700/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/60">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">
                        <span><?= __('attendee_capacity') ?></span>
                        <span class="font-bold text-slate-800 dark:text-white"><?= $event->reg_count ?> / <?= $event->capacity ?></span>
                    </div>
                    <div class="w-full h-2 bg-slate-200/70 dark:bg-slate-600 rounded-full overflow-hidden">
                        <div class="h-full rounded-full <?= $isFull ? 'bg-red-500' : 'bg-emerald-500' ?>" style="width: <?= $pct ?>%"></div>
                    </div>
                    <div class="mt-2 text-[11px] text-slate-400 dark:text-slate-400 flex justify-between">
                        <span><?= $pct ?>% <?= __('booked') ?></span>
                        <span><?= max(0, $event->capacity - $event->reg_count) ?> <?= __('spots_remaining') ?></span>
                    </div>
                </div>

                <!-- Registration Actions Section -->
                <?php if ($isAdmin): ?>
                    <!-- Admin Actions -->
                    <div class="space-y-2.5">
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 text-xs rounded-xl border border-amber-200/60 dark:border-amber-800/40 font-semibold mb-3 flex items-center gap-2">
                            <?= getIcon('shield', 'w-4 h-4') ?>
                            <span><?= __('admin_managing_notice') ?></span>
                        </div>

                        <a href="./?page=event/registrations&id=<?= $event->id ?>" 
                           class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition flex items-center justify-center gap-2">
                            <?= getIcon('users', 'w-4 h-4') ?>
                            <span><?= __('manage_regs_count') ?> (<?= $event->reg_count ?>)</span>
                        </a>

                        <a href="./?page=event/edit&id=<?= $event->id ?>" 
                           class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition flex items-center justify-center gap-2">
                            <?= getIcon('pencil', 'w-4 h-4') ?>
                            <span><?= __('edit_event_details') ?></span>
                        </a>

                        <a href="./?page=event/delete&id=<?= $event->id ?>" 
                           class="confirm-action w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 border border-red-200 dark:border-red-900/50 transition flex items-center justify-center gap-2"
                           data-confirm-title="<?= __('confirm_title') ?>"
                           data-confirm-text="<?= __('delete_event_confirm') ?>"
                           data-confirm-btn="<?= __('confirm_yes') ?>"
                           data-confirm-icon="warning"
                           data-confirm-color="red">
                            <?= getIcon('trash', 'w-4 h-4') ?>
                            <span><?= __('delete_event') ?></span>
                        </a>
                    </div>

                <?php elseif ($user): ?>
                    <!-- Logged in User Actions -->
                    <?php if ($isRegistered): ?>
                        <div class="space-y-4">
                            <?php 
                            $statusStyle = match($userReg->status ?? 'pending') {
                                'approved' => 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/40',
                                'rejected' => 'bg-red-50 dark:bg-red-950/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800/40',
                                default    => 'bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800/40'
                            };
                            ?>
                            <div class="p-4 rounded-2xl border <?= $statusStyle ?>">
                                <div class="flex items-center gap-2 font-bold text-sm">
                                    <?= getIcon('check-circle', 'w-4 h-4') ?>
                                    <span><?= __('status') ?>: <?= __($userReg->status ?? 'pending') ?></span>
                                </div>
                                <p class="text-xs mt-1 text-slate-600 dark:text-slate-300">
                                    <?= ($userReg->status ?? '') === 'approved' 
                                        ? __('reg_confirmed_msg')
                                        : __('reg_pending_msg') ?>
                                </p>
                            </div>

                            <a href="./?page=event/cancel&event_id=<?= $event->id ?>" 
                               class="confirm-action w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 border border-red-200 dark:border-red-900/50 transition flex items-center justify-center gap-1.5"
                               data-confirm-title="<?= __('confirm_title') ?>"
                               data-confirm-text="<?= __('cancel_reg_confirm') ?>"
                               data-confirm-btn="<?= __('confirm_yes') ?>"
                               data-confirm-icon="warning"
                               data-confirm-color="red">
                                <?= getIcon('calendar-x', 'w-4 h-4') ?>
                                <span><?= __('cancel_my_reg') ?></span>
                            </a>
                        </div>

                    <?php elseif ($isFull): ?>
                        <button disabled 
                                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-700/60 cursor-not-allowed flex items-center justify-center gap-2">
                            <?= getIcon('calendar-x', 'w-4 h-4') ?>
                            <span><?= __('event_full') ?></span>
                        </button>
                    <?php else: ?>
                        <a href="./?page=event/register&id=<?= $event->id ?>" 
                           class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/30 transition-all duration-150 transform active:scale-[0.99] flex items-center justify-center gap-2">
                            <?= getIcon('calendar-check', 'w-4 h-4') ?>
                            <span><?= __('register_now') ?></span>
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Guest Actions -->
                    <div class="space-y-3">
                        <p class="text-xs text-slate-500 dark:text-slate-400"><?= __('signin_to_reserve') ?></p>
                        <a href="./?page=login" 
                           class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/30 transition flex items-center justify-center gap-2">
                            <?= getIcon('login', 'w-4 h-4') ?>
                            <span><?= __('signin_to_register') ?></span>
                        </a>
                        <p class="text-center text-xs text-slate-400 dark:text-slate-500">
                            <?= __('new_here') ?> <a href="./?page=register" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline"><?= __('create_account') ?></a>
                        </p>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>

</div>
