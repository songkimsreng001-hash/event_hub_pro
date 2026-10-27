<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    setFlash('error', 'Event not found.', 'Not Found');
    redirect('./?page=events');
}

$titleErr = $descErr = $locErr = $dateErr = $capErr = '';
$title    = $event->title;
$desc     = $event->description;
$loc      = $event->location;
$date     = date('Y-m-d\TH:i', strtotime($event->event_date));
$capacity = $event->capacity;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title    = trim($_POST['title'] ?? '');
    $desc     = trim($_POST['desc'] ?? '');
    $loc      = trim($_POST['location'] ?? '');
    $date     = trim($_POST['event_date'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 50);
    $photo    = $_FILES['image'] ?? [];

    if (empty($title)) $titleErr = 'Please enter an event title.';
    if (empty($desc))  $descErr  = 'Please provide a description.';
    if (empty($loc))   $locErr   = 'Please specify a venue or location.';
    if (empty($date))  $dateErr  = 'Please choose event date and time.';
    if ($capacity < 1) $capErr   = 'Capacity must be at least 1 attendee.';

    if (empty($titleErr) && empty($descErr) && empty($locErr) && empty($dateErr) && empty($capErr)) {
        try {
            if (updateEvent($id, $title, $desc, $loc, $date, $capacity, $photo)) {
                setFlash('success', 'Event details have been updated!', 'Changes Saved');
                redirect('./?page=event/detail&id=' . $id);
            } else {
                setFlash('error', 'Failed to update event.', 'Error');
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage(), 'Upload Error');
        }
    }
}
?>

<div class="max-w-3xl mx-auto space-y-6">
    
    <div>
        <a href="./?page=event/detail&id=<?= $id ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
            <?= getIcon('arrow-left', 'w-4 h-4') ?>
            <span><?= __('back') ?></span>
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-6 sm:p-9 transition-colors">
        
        <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-700 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <?= getIcon('pencil', 'w-6 h-6') ?>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white"><?= __('edit') ?>: <?= htmlspecialchars($event->title) ?></h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400"><?= __('crop_desc') ?></p>
            </div>
        </div>

        <form id="editEventForm" method="post" action="./?page=event/edit&id=<?= $id ?>" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="submit" value="1">

            <!-- Banner Image Upload with Auto-Cropper Trigger -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Event Banner Image</label>
                    <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1">
                        <?= getIcon('crop', 'w-3 h-3') ?>
                        <span><?= __('crop_title') ?></span>
                    </span>
                </div>
                <div class="relative group cursor-pointer">
                    <input type="file" name="image" id="eventImg" hidden accept=".jpg,.jpeg,.png,.webp">
                    <label for="eventImg" role="button" class="block">
                        <div class="relative h-48 sm:h-56 w-full rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 group-hover:border-blue-400 transition flex items-center justify-center">
                            <img id="imgPreview" 
                                 src="<?= $event->image ? htmlspecialchars($event->image) : './assets/images/loading.png' ?>" 
                                 alt="Event Banner Preview" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-xs font-semibold p-4 text-center">
                                <?= getIcon('crop', 'w-8 h-8 mb-1.5 text-blue-400') ?>
                                <span>Click to replace &amp; crop banner (16:9 ratio)</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Event Title</label>
                <input type="text" 
                       name="title" 
                       value="<?= htmlspecialchars($title) ?>" 
                       required
                       class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border <?= !empty($titleErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-700 focus:bg-white dark:focus:bg-slate-950 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                <?php if (!empty($titleErr)): ?>
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                        <span><?= htmlspecialchars($titleErr) ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Description</label>
                <textarea name="desc" 
                          rows="4" 
                          required
                          class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border <?= !empty($descErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-700 focus:bg-white dark:focus:bg-slate-950 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition"><?= htmlspecialchars($desc) ?></textarea>
            </div>

            <!-- Location & Date in 2 columns -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('location') ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <?= getIcon('map-pin', 'w-4 h-4') ?>
                        </div>
                        <input type="text" 
                               name="location" 
                               value="<?= htmlspecialchars($loc) ?>" 
                               required
                               class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border <?= !empty($locErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-700 focus:bg-white dark:focus:bg-slate-950 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('date_time') ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <?= getIcon('calendar', 'w-4 h-4') ?>
                        </div>
                        <input type="datetime-local" 
                               name="event_date" 
                               value="<?= htmlspecialchars($date) ?>" 
                               required
                               class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border <?= !empty($dateErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-700 focus:bg-white dark:focus:bg-slate-950 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                    </div>
                </div>
            </div>

            <!-- Capacity -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('capacity') ?></label>
                <div class="relative max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <?= getIcon('users', 'w-4 h-4') ?>
                    </div>
                    <input type="number" 
                           name="capacity" 
                           value="<?= $capacity ?>" 
                           min="1"
                           required
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border <?= !empty($capErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-700 focus:bg-white dark:focus:bg-slate-950 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center gap-3">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all duration-150 transform active:scale-95 flex items-center gap-2">
                    <?= getIcon('check', 'w-4 h-4') ?>
                    <span><?= __('save') ?></span>
                </button>
                <a href="./?page=event/detail&id=<?= $id ?>" class="px-5 py-2.5 rounded-xl font-semibold text-xs sm:text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <?= __('cancel') ?>
                </a>
            </div>

        </form>
    </div>

</div>
