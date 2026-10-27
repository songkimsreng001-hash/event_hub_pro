<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    setFlash('error', 'Event not found.', 'Not Found');
    redirect('./?page=events');
}

if ($event->reg_count >= $event->capacity) {
    setFlash('warning', 'Sorry, this event is already fully booked.', 'Event Full');
    redirect('./?page=event/detail&id=' . $id);
}

if (isUserRegistered($event->id, $user->id)) {
    setFlash('info', 'You are already registered for this event.', 'Already Registered');
    redirect('./?page=event/detail&id=' . $id);
}

$note = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $note = trim($_POST['note'] ?? '');

    try {
        if (registerForEvent($event->id, $user->id, $note)) {
            setFlash('success', 'You have registered for "' . $event->title . '"! Your spot is pending approval.', 'Registration Submitted');
            redirect('./?page=my-registrations');
        } else {
            setFlash('error', 'Registration could not be completed. Please try again.', 'Error');
        }
    } catch (Exception $e) {
        setFlash('error', $e->getMessage(), 'Registration Error');
    }
}
?>

<div class="max-w-xl mx-auto space-y-6">
    
    <div>
        <a href="./?page=event/detail&id=<?= $event->id ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition">
            <?= getIcon('arrow-left', 'w-4 h-4') ?>
            <span><?= __('back_to_event') ?></span>
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm p-6 sm:p-9">
        
        <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-700/60 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <?= getIcon('calendar-check', 'w-6 h-6') ?>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 dark:text-white"><?= __('confirm_booking') ?></h1>
                <p class="text-xs text-slate-500 dark:text-slate-400"><?= __('review_ticket_request') ?></p>
            </div>
        </div>

        <!-- Event summary badge -->
        <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-100 dark:border-slate-700/60 mb-6 space-y-2">
            <h2 class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($event->title) ?></h2>
            <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 dark:text-slate-300">
                <div class="flex items-center gap-1.5">
                    <?= getIcon('calendar', 'w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400') ?>
                    <span><?= date('M d, Y', strtotime($event->event_date)) ?></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <?= getIcon('clock', 'w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400') ?>
                    <span><?= date('h:i A', strtotime($event->event_date)) ?></span>
                </div>
                <div class="flex items-center gap-1.5 col-span-2">
                    <?= getIcon('map-pin', 'w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400') ?>
                    <span class="truncate"><?= htmlspecialchars($event->location) ?></span>
                </div>
            </div>
        </div>

        <form method="post" action="./?page=event/register&id=<?= $event->id ?>" class="space-y-5">
            <input type="hidden" name="submit" value="1">

            <!-- Attendee Details (Readonly) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('attendee_name') ?></label>
                    <input type="text" value="<?= htmlspecialchars($user->name) ?>" readonly 
                           class="w-full px-3.5 py-2 text-xs bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-300 font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('username') ?></label>
                    <input type="text" value="@<?= htmlspecialchars($user->username) ?>" readonly 
                           class="w-full px-3.5 py-2 text-xs bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-300 font-medium">
                </div>
            </div>

            <!-- Optional Note -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    <?= __('note_optional') ?>
                </label>
                <textarea name="note" 
                          rows="3" 
                          placeholder="<?= __('note_placeholder') ?>"
                          class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl text-slate-800 dark:text-white transition"><?= htmlspecialchars($note) ?></textarea>
            </div>

            <!-- Notice -->
            <div class="p-3 bg-emerald-50/70 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl border border-emerald-200/60 dark:border-emerald-800/40 flex items-start gap-2">
                <?= getIcon('check-circle', 'w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5') ?>
                <span><?= __('reg_reviewed_notice') ?></span>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/30 transition-all duration-150 transform active:scale-95 flex items-center gap-2">
                    <?= getIcon('check', 'w-4 h-4') ?>
                    <span><?= __('confirm_booking') ?></span>
                </button>
                <a href="./?page=event/detail&id=<?= $event->id ?>" class="px-5 py-2.5 rounded-xl font-semibold text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <?= __('cancel') ?>
                </a>
            </div>

        </form>

    </div>

</div>
