<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$targetUser = readUser($id);

if ($targetUser === null) {
    setFlash('error', 'User not found.', 'Not Found');
    redirect('./?page=user/list');
}

$nameErr = $usernameErr = $passwdErr = '';
$name     = $targetUser->name;
$username = $targetUser->username;
$level    = $targetUser->level;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwd   = trim($_POST['passwd'] ?? '');
    $level    = in_array($_POST['level'] ?? '', ['admin', 'user']) ? $_POST['level'] : $targetUser->level;
    $photo    = $_FILES['photo'] ?? null;

    if (empty($name)) {
        $nameErr = 'Please enter user name.';
    }
    if (empty($username)) {
        $usernameErr = 'Please choose a username.';
    } elseif ($username !== $targetUser->username && usernameExists($username)) {
        $usernameErr = 'This username is already taken by another account.';
    }
    if (!empty($passwd) && strlen($passwd) < 6) {
        $passwdErr = 'New password must be at least 6 characters.';
    }

    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        try {
            if (updateUser($id, $name, $username, $passwd, $photo, $level)) {
                setFlash('success', 'User "' . $name . '" has been updated successfully!', 'Changes Saved');
                redirect('./?page=user/list');
            } else {
                setFlash('error', 'Failed to update user.', 'Error');
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage(), 'Upload Error');
        }
    }
}
?>

<div class="max-w-2xl mx-auto space-y-6">
    
    <div>
        <a href="./?page=user/list" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition">
            <?= getIcon('arrow-left', 'w-4 h-4') ?>
            <span><?= __('back_to_users') ?></span>
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm p-6 sm:p-9">
        
        <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-700/60 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <?= getIcon('pencil', 'w-6 h-6') ?>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white"><?= __('edit_user_title') ?></h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400"><?= __('edit_user_subtitle') ?></p>
            </div>
        </div>

        <form method="post" action="./?page=user/update&id=<?= $id ?>" enctype="multipart/form-data" class="space-y-6">
            
            <!-- Photo Upload with Preview -->
            <div class="flex flex-col items-center sm:flex-row sm:items-center gap-5 p-4 bg-slate-50 dark:bg-slate-750 rounded-2xl border border-slate-100 dark:border-slate-700">
                <div class="relative group cursor-pointer shrink-0">
                    <input type="file" name="photo" id="userPhotoUpload" hidden accept=".jpg,.jpeg,.png,.webp">
                    <label for="userPhotoUpload" role="button" class="block relative">
                        <img id="userPhotoPreview" 
                             src="<?= htmlspecialchars($targetUser->photo ?: './assets/images/Profile_PNG.png') ?>" 
                             alt="<?= htmlspecialchars($targetUser->name) ?>" 
                             class="w-20 h-20 rounded-2xl object-cover ring-2 ring-blue-500/30 group-hover:opacity-85 transition"
                             onerror="this.src='./assets/images/Profile_PNG.png'">
                        <div class="absolute inset-0 bg-slate-900/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                            <?= getIcon('camera', 'w-5 h-5') ?>
                        </div>
                    </label>
                </div>
                <div>
                    <label for="userPhotoUpload" role="button" class="text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        <?= __('change_avatar') ?>
                    </label>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Click image to choose a new photo (JPG, PNG, WEBP - Max 2MB).</p>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('full_name') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <?= getIcon('user', 'w-4 h-4') ?>
                    </div>
                    <input type="text" 
                           name="name" 
                           value="<?= htmlspecialchars($name) ?>" 
                           required
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($nameErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                </div>
                <?php if (!empty($nameErr)): ?>
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                        <span><?= htmlspecialchars($nameErr) ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Username & Role in 2 cols -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('username') ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                            @
                        </div>
                        <input type="text" 
                               name="username" 
                               value="<?= htmlspecialchars($username) ?>" 
                               required
                               class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($usernameErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                    </div>
                    <?php if (!empty($usernameErr)): ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                            <span><?= htmlspecialchars($usernameErr) ?></span>
                        </p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('account_role') ?></label>
                    <select name="level" class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-slate-800 dark:text-white transition">
                        <option value="user" <?= $level === 'user' ? 'selected' : '' ?>><?= __('role_user') ?></option>
                        <option value="admin" <?= $level === 'admin' ? 'selected' : '' ?>><?= __('role_admin') ?></option>
                    </select>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    <?= __('new_password') ?> <span class="text-slate-400 font-normal lowercase">(<?= __('leave_blank_password') ?>)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <?= getIcon('key', 'w-4 h-4') ?>
                    </div>
                    <input type="password" 
                           name="passwd" 
                           placeholder="<?= __('leave_blank_password') ?>"
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($passwdErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20' ?> rounded-xl text-slate-800 dark:text-white transition">
                </div>
                <?php if (!empty($passwdErr)): ?>
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                        <span><?= htmlspecialchars($passwdErr) ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Buttons -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl font-bold text-sm text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all duration-150 transform active:scale-95 flex items-center gap-2">
                    <?= getIcon('check', 'w-4 h-4') ?>
                    <span><?= __('save_user_changes') ?></span>
                </button>
                <a href="./?page=user/list" class="px-5 py-2.5 rounded-xl font-semibold text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <?= __('cancel') ?>
                </a>
            </div>

        </form>

    </div>

</div>
