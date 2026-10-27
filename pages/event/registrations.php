<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    setFlash('error', 'Event not found.', 'Not Found');
    redirect('./?page=events');
}

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg_id'], $_POST['status'])) {
    $reg_id = (int)$_POST['reg_id'];
    $status = $_POST['status'];
    if (updateRegistrationStatus($reg_id, $status)) {
        setFlash('success', 'Registration marked as ' . htmlspecialchars($status) . '!', 'Status Updated');
    } else {
        setFlash('error', 'Could not update registration status.', 'Error');
    }
    redirect('./?page=event/registrations&id=' . $id);
}

$statusFilter = $_GET['status'] ?? 'all';
$regs = getEventRegistrations($id, $statusFilter !== 'all' ? $statusFilter : null);
$totalRegs = $regs ? $regs->num_rows : 0;
?>

<div class="space-y-6">
    
    <!-- Top Back Navigation -->
    <div>
        <a href="./?page=event/detail&id=<?= $event->id ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition">
            <?= getIcon('arrow-left', 'w-4 h-4') ?>
            <span><?= __('back_to_event') ?></span>
        </a>
    </div>

    <!-- Event Header Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800/40"><?= __('attendees') ?></span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white"><?= htmlspecialchars($event->title) ?></h1>
            <div class="flex flex-wrap items-center gap-4 mt-2 text-xs text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-1">
                    <?= getIcon('calendar', 'w-3.5 h-3.5 text-slate-400') ?>
                    <?= date('M d, Y', strtotime($event->event_date)) ?>
                </span>
                <span class="flex items-center gap-1">
                    <?= getIcon('clock', 'w-3.5 h-3.5 text-slate-400') ?>
                    <?= date('h:i A', strtotime($event->event_date)) ?>
                </span>
                <span class="flex items-center gap-1">
                    <?= getIcon('map-pin', 'w-3.5 h-3.5 text-slate-400') ?>
                    <?= htmlspecialchars($event->location) ?>
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-700/40 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-700/60 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold">
                <?= getIcon('users', 'w-5 h-5') ?>
            </div>
            <div>
                <div class="text-base font-extrabold text-slate-900 dark:text-white"><?= $event->reg_count ?> / <?= $event->capacity ?></div>
                <div class="text-[11px] text-slate-400 dark:text-slate-400 font-medium"><?= __('seats_occupied') ?></div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700/80 pb-3">
        <a href="./?page=event/registrations&id=<?= $id ?>&status=all" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition <?= $statusFilter === 'all' ? 'bg-slate-900 dark:bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <?= __('all') ?> (<?= $event->reg_count ?>)
        </a>
        <a href="./?page=event/registrations&id=<?= $id ?>&status=pending" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition <?= $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <?= __('pending') ?>
        </a>
        <a href="./?page=event/registrations&id=<?= $id ?>&status=approved" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition <?= $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <?= __('approved') ?>
        </a>
        <a href="./?page=event/registrations&id=<?= $id ?>&status=rejected" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition <?= $statusFilter === 'rejected' ? 'bg-red-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <?= __('rejected') ?>
        </a>
    </div>

    <!-- Attendees Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50/80 dark:bg-slate-700/50 text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-400 font-bold border-b border-slate-200/80 dark:border-slate-700/60">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4"><?= __('user_col') ?></th>
                        <th class="py-3.5 px-4"><?= __('registered_date') ?></th>
                        <th class="py-3.5 px-4"><?= __('note_message') ?></th>
                        <th class="py-3.5 px-4"><?= __('status') ?></th>
                        <th class="py-3.5 px-4 text-right"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <?php 
                    $index = 0;
                    if ($totalRegs > 0):
                        while ($row = $regs->fetch_object()):
                            $index++;
                            $statusBadge = match($row->status) {
                                'approved' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/40',
                                'rejected' => 'bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 border-red-200/80 dark:border-red-800/40',
                                default    => 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/40'
                            };
                    ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="py-4 px-4 text-center font-bold text-slate-400 dark:text-slate-500 text-xs">
                                <?= $index ?>
                            </td>

                            <!-- Attendee Profile -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars($row->photo ?: './assets/images/Profile_PNG.png') ?>" 
                                         alt="<?= htmlspecialchars($row->name) ?>"
                                         class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 shrink-0"
                                         onerror="this.src='./assets/images/Profile_PNG.png'">
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($row->name) ?></div>
                                        <div class="text-xs text-slate-400 dark:text-slate-400">@<?= htmlspecialchars($row->username) ?></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                <?= date('M d, Y', strtotime($row->created_at)) ?>
                                <span class="text-slate-400 dark:text-slate-500 block text-[11px]"><?= date('h:i A', strtotime($row->created_at)) ?></span>
                            </td>

                            <!-- Note -->
                            <td class="py-4 px-4 text-xs text-slate-600 dark:text-slate-300 max-w-xs">
                                <?php if (!empty($row->note)): ?>
                                    <span class="italic bg-slate-50 dark:bg-slate-700/60 px-2.5 py-1 rounded-lg border border-slate-100 dark:border-slate-700 block">
                                        "<?= htmlspecialchars($row->note) ?>"
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-300 dark:text-slate-600">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border <?= $statusBadge ?>">
                                    <?= __($row->status) ?>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <?php if ($row->status !== 'approved'): ?>
                                        <form method="post" action="./?page=event/registrations&id=<?= $id ?>" class="inline">
                                            <input type="hidden" name="reg_id" value="<?= $row->id ?>">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition" 
                                                    title="<?= __('approve_attendee') ?>">
                                                <?= getIcon('check', 'w-4 h-4') ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($row->status !== 'rejected'): ?>
                                        <form method="post" action="./?page=event/registrations&id=<?= $id ?>" class="inline">
                                            <input type="hidden" name="reg_id" value="<?= $row->id ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/60 transition" 
                                                    title="<?= __('reject_attendee') ?>">
                                                <?= getIcon('x', 'w-4 h-4') ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>

                    <?php if ($totalRegs === 0): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-700/60 text-slate-400 dark:text-slate-400 flex items-center justify-center mb-2">
                                    <?= getIcon('users', 'w-6 h-6') ?>
                                </div>
                                <p class="text-sm font-medium"><?= __('no_regs_found') ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
