<?php
$currentUser = loggedInUser();

$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr = '';

// Handle Password Change
if (isset($_POST['changePasswd'])) {
    $oldPasswd        = trim($_POST['oldPasswd'] ?? '');
    $newPasswd        = trim($_POST['newPasswd'] ?? '');
    $confirmNewPasswd = trim($_POST['confirmNewPasswd'] ?? '');

    if (empty($oldPasswd)) {
        $oldPasswdErr = 'Please enter your current password.';
    } elseif (!isUserHasPassword($oldPasswd)) {
        $oldPasswdErr = 'Your current password is incorrect.';
    }

    if (empty($newPasswd)) {
        $newPasswdErr = 'Please enter your new password.';
    } elseif (strlen($newPasswd) < 6) {
        $newPasswdErr = 'New password must be at least 6 characters.';
    } elseif ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'New passwords do not match.';
    }

    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassword($newPasswd)) {
            setFlash('success', 'Your password has been updated successfully!', 'Security Updated');
            redirect('./?page=profile');
        } else {
            setFlash('error', 'Failed to update password. Please try again.', 'Error');
        }
    }
}

// Handle Photo Upload
if (isset($_POST['uploadPhoto']) && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
    if (empty($photo['name'])) {
        setFlash('warning', 'Please select an image file to upload.', 'No File Selected');
    } else {
        try {
            if (changeProfileImage($photo)) {
                setFlash('success', 'Profile photo updated successfully!', 'Photo Updated');
                redirect('./?page=profile');
            } else {
                setFlash('error', 'Failed to update profile photo.', 'Error');
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage(), 'Upload Error');
        }
    }
}

// Handle Photo Deletion
if (isset($_POST['deletePhoto'])) {
    if (deleteProfileImage()) {
        setFlash('info', 'Profile photo removed. Restored default avatar.', 'Avatar Reset');
        redirect('./?page=profile');
    }
}
?>

<div class="space-y-8">
    
    <!-- Page Header -->
    <div class="pb-2 border-b border-slate-200/70 dark:border-slate-700/60">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
            <span><?= __('profile_title') ?></span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('profile_subtitle') ?></p>
    </div>

    <!-- 2 Column Profile Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Avatar & Account Details Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-6 sm:p-7 flex flex-col items-center text-center">
            
            <form method="post" action="./?page=profile" enctype="multipart/form-data" class="w-full flex flex-col items-center">
                <!-- Avatar Preview with upload overlay -->
                <div class="relative group cursor-pointer mb-4">
                    <input name="photo" type="file" id="profileUpload" hidden accept=".jpg,.jpeg,.png,.webp">
                    <label for="profileUpload" role="button" class="block relative">
                        <img id="profilePreview" 
                             src="<?= htmlspecialchars($currentUser->photo ?: './assets/images/Profile_PNG.png') ?>" 
                             alt="<?= htmlspecialchars($currentUser->name) ?>"
                             class="w-32 h-32 rounded-3xl object-cover ring-4 ring-emerald-500/20 shadow-lg group-hover:opacity-90 transition"
                             onerror="this.src='./assets/images/Profile_PNG.png'">
                        <div class="absolute inset-0 bg-slate-900/40 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-xs font-semibold">
                            <?= getIcon('camera', 'w-6 h-6 mb-1') ?>
                            <span><?= __('change_avatar') ?></span>
                        </div>
                    </label>
                </div>

                <h2 class="text-xl font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($currentUser->name) ?></h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">@<?= htmlspecialchars($currentUser->username) ?></p>

                <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $currentUser->level === 'admin' ? 'bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300' : 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300' ?>">
                        <?= getIcon('shield', 'w-3.5 h-3.5') ?>
                        <?= htmlspecialchars($currentUser->level) ?>
                    </span>
                </div>

                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-4">JPG, PNG or WEBP (Max 2MB)</p>

                <!-- Avatar Actions -->
                <div class="mt-5 flex items-center gap-2 w-full">
                    <button type="submit" name="uploadPhoto" 
                            class="flex-1 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition flex items-center justify-center gap-1.5">
                        <?= getIcon('check', 'w-3.5 h-3.5') ?> <?= __('save_photo') ?>
                    </button>

                    <button type="submit" name="deletePhoto" 
                            class="confirm-action py-2 px-3 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-950/40 text-slate-600 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 text-xs font-semibold transition flex items-center justify-center gap-1"
                            data-confirm-title="<?= __('confirm_title') ?>"
                            data-confirm-text="<?= __('remove_photo') ?>?"
                            data-confirm-btn="<?= __('confirm_yes') ?>"
                            data-confirm-icon="question">
                        <?= getIcon('trash', 'w-3.5 h-3.5') ?>
                    </button>
                </div>
            </form>

            <div class="w-full mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/60 text-left text-xs space-y-2 text-slate-500 dark:text-slate-400">
                <div class="flex justify-between">
                    <span><?= __('member_since') ?>:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-200"><?= date('M Y', strtotime($currentUser->created_at ?? 'now')) ?></span>
                </div>
                <div class="flex justify-between">
                    <span><?= __('account_status') ?>:</span>
                    <span class="font-medium text-emerald-600 dark:text-emerald-400"><?= __('active') ?></span>
                </div>
            </div>

        </div>

        <!-- Right: Change Password Card -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/60">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <?= getIcon('lock', 'w-5 h-5') ?>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white"><?= __('security_password') ?></h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400"><?= __('security_subtitle') ?></p>
                </div>
            </div>

            <form method="post" action="./?page=profile" class="space-y-5 max-w-lg">
                
                <!-- Current Password -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('old_password') ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <?= getIcon('key', 'w-4 h-4') ?>
                        </div>
                        <input type="password" 
                               name="oldPasswd" 
                               value="<?= htmlspecialchars($oldPasswd) ?>" 
                               required
                               placeholder="<?= __('old_password') ?>"
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($oldPasswdErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                    </div>
                    <?php if (!empty($oldPasswdErr)): ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                            <span><?= htmlspecialchars($oldPasswdErr) ?></span>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('new_password') ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <?= getIcon('lock', 'w-4 h-4') ?>
                        </div>
                        <input type="password" 
                               name="newPasswd" 
                               required
                               placeholder="<?= __('new_password') ?> (min. 6 chars)"
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($newPasswdErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                    </div>
                    <?php if (!empty($newPasswdErr)): ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                            <span><?= htmlspecialchars($newPasswdErr) ?></span>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('confirm_password') ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <?= getIcon('lock', 'w-4 h-4') ?>
                        </div>
                        <input type="password" 
                               name="confirmNewPasswd" 
                               required
                               placeholder="<?= __('confirm_password') ?>"
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 rounded-xl text-slate-800 dark:text-white transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" name="changePasswd" 
                            class="py-2.5 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold shadow-sm shadow-purple-600/30 transition flex items-center gap-2">
                        <?= getIcon('key', 'w-4 h-4') ?>
                        <span><?= __('update_password') ?></span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
