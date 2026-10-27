<?php
$regs = getMyRegistrations($user->id);
$total = ($regs && is_object($regs)) ? $regs->num_rows : 0;
?>

<div class="space-y-8">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/70 dark:border-slate-700/60">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                <span><?= __('my_regs_title') ?></span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                    <?= $total ?> <?= __('all') ?>
                </span>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('my_regs_subtitle') ?></p>
        </div>

        <a href="./?page=events" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 border border-emerald-200/60 dark:border-emerald-800/40 transition duration-150">
            <?= getIcon('calendar', 'w-4 h-4 text-emerald-600 dark:text-emerald-400') ?>
            <span><?= __('browse_more_events') ?></span>
        </a>
    </div>

    <!-- Registrations List -->
    <?php if ($total > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php while ($row = $regs->fetch_object()): 
                $statusBadge = match($row->status) {
                    'approved' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/40',
                    'rejected' => 'bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 border-red-200/80 dark:border-red-800/40',
                    default    => 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/40'
                };
                $dateStr = date('M d, Y', strtotime($row->event_date));
                $timeStr = date('h:i A', strtotime($row->event_date));
                $imgSrc  = $row->image ? htmlspecialchars($row->image) : './assets/images/loading.png';
            ?>
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs hover:shadow-md transition-shadow overflow-hidden flex flex-col sm:flex-row">
                    
                    <!-- Thumbnail -->
                    <div class="sm:w-44 h-40 sm:h-auto bg-slate-100 dark:bg-slate-700/50 relative shrink-0">
                        <img src="<?= $imgSrc ?>" 
                             alt="<?= htmlspecialchars($row->title) ?>"
                             class="w-full h-full object-cover"
                             onerror="this.src='./assets/images/loading.png'">
                        <div class="absolute top-3 left-3 sm:hidden">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border <?= $statusBadge ?> bg-white/95 dark:bg-slate-800/95 backdrop-blur-xs">
                                <?= __($row->status) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5 flex flex-col justify-between flex-grow">
                        <div>
                            <div class="hidden sm:flex items-center justify-between gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider border <?= $statusBadge ?>">
                                    <?= __($row->status) ?>
                                </span>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                    <?= __('booked_on') ?> <?= date('M d, Y', strtotime($row->created_at)) ?>
                                </span>
                            </div>

                            <h3 class="font-bold text-slate-900 dark:text-white text-base line-clamp-1 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                                <a href="./?page=event/detail&id=<?= $row->event_id ?>">
                                    <?= htmlspecialchars($row->title) ?>
                                </a>
                            </h3>

                            <div class="mt-2 space-y-1 text-xs text-slate-500 dark:text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <?= getIcon('calendar', 'w-3.5 h-3.5 text-slate-400') ?>
                                    <span><?= $dateStr ?> &bull; <?= $timeStr ?></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <?= getIcon('map-pin', 'w-3.5 h-3.5 text-slate-400') ?>
                                    <span class="truncate"><?= htmlspecialchars($row->location) ?></span>
                                </div>
                            </div>

                            <?php if (!empty($row->note)): ?>
                                <p class="mt-2.5 text-xs text-slate-600 dark:text-slate-300 italic bg-slate-50 dark:bg-slate-700/50 p-2 rounded-lg border border-slate-100 dark:border-slate-700">
                                    "<?= htmlspecialchars($row->note) ?>"
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Action buttons -->
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-3">
                            <a href="./?page=event/detail&id=<?= $row->event_id ?>" 
                               class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 flex items-center gap-1">
                                <span><?= __('event_info_link') ?></span>
                                <?= getIcon('arrow-right', 'w-3.5 h-3.5') ?>
                            </a>

                            <?php if ($row->status !== 'rejected'): ?>
                                <a href="./?page=event/cancel&event_id=<?= $row->event_id ?>" 
                                   class="confirm-action text-xs font-semibold text-red-600 dark:text-red-400 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/30 px-2.5 py-1.5 rounded-lg transition"
                                   data-confirm-title="<?= __('confirm_title') ?>"
                                   data-confirm-text="<?= __('cancel_reg_confirm') ?>"
                                   data-confirm-btn="<?= __('confirm_yes') ?>"
                                   data-confirm-icon="warning"
                                   data-confirm-color="red">
                                    <?= __('cancel_reg') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 p-12 text-center max-w-lg mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4">
                <?= getIcon('ticket', 'w-8 h-8') ?>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white"><?= __('no_regs_yet') ?></h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('no_regs_desc') ?></p>
            <div class="mt-6">
                <a href="./?page=events" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition">
                    <?= getIcon('calendar', 'w-4 h-4') ?>
                    <span><?= __('events_all') ?></span>
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>
