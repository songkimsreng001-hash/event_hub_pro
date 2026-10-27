<?php
$name = $username = '';
$nameErr = $usernameErr = $passwdErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $username      = trim($_POST['username'] ?? '');
    $passwd        = trim($_POST['passwd'] ?? '');
    $confirmPasswd = trim($_POST['confirmPasswd'] ?? '');

    if (empty($name)) {
        $nameErr = 'Please enter your full name.';
    }
    if (empty($username)) {
        $usernameErr = 'Please choose a username.';
    } elseif (strlen($username) < 3) {
        $usernameErr = 'Username must be at least 3 characters.';
    } elseif (usernameExists($username)) {
        $usernameErr = 'This username is already taken. Please pick another.';
    }

    if (empty($passwd)) {
        $passwdErr = 'Please provide a password.';
    } elseif (strlen($passwd) < 6) {
        $passwdErr = 'Password must be at least 6 characters.';
    } elseif ($passwd !== $confirmPasswd) {
        $passwdErr = 'Passwords do not match.';
    }

    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        if (registerUser($name, $username, $passwd)) {
            setFlash('success', 'Registration successful! You can now sign in.', 'Account Created');
            redirect('./?page=login');
        } else {
            setFlash('error', 'Registration failed due to a server error. Please try again.', 'Error');
        }
    }
}
?>

<div class="max-w-md mx-auto my-6 sm:my-10">
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-xl shadow-slate-200/50 dark:shadow-none p-7 sm:p-9">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-3">
                <a href="./" class="inline-block transition transform hover:scale-105" title="EventHub Pro">
                    <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-16 h-16 rounded-2xl object-cover shadow-lg shadow-emerald-600/20 ring-2 ring-emerald-500/30">
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight"><?= __('signup_title') ?></h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('signup_subtitle') ?></p>
        </div>

        <!-- Form -->
        <form method="post" action="./?page=register" class="space-y-4">
            
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
                           placeholder="e.g. Alex Johnson"
                           required
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($nameErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20' ?> rounded-xl text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 transition">
                </div>
                <?php if (!empty($nameErr)): ?>
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                        <span><?= htmlspecialchars($nameErr) ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('username') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="text-sm font-bold">@</span>
                    </div>
                    <input type="text" 
                           name="username" 
                           value="<?= htmlspecialchars($username) ?>" 
                           placeholder="alexj"
                           required
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($usernameErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20' ?> rounded-xl text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 transition">
                </div>
                <?php if (!empty($usernameErr)): ?>
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                        <span><?= htmlspecialchars($usernameErr) ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('old_password') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <?= getIcon('key', 'w-4 h-4') ?>
                    </div>
                    <input type="password" 
                           name="passwd" 
                           placeholder="••••••••"
                           required
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border <?= !empty($passwdErr) ? 'border-red-400 ring-2 ring-red-400/20' : 'border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20' ?> rounded-xl text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 transition">
                </div>
                <?php if (!empty($passwdErr)): ?>
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <?= getIcon('exclamation-circle', 'w-3.5 h-3.5') ?>
                        <span><?= htmlspecialchars($passwdErr) ?></span>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('confirm_password') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <?= getIcon('lock', 'w-4 h-4') ?>
                    </div>
                    <input type="password" 
                           name="confirmPasswd" 
                           placeholder="••••••••"
                           required
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 transition">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/30 transition-all duration-150 transform active:scale-[0.99] flex items-center justify-center gap-2">
                    <?= getIcon('user-plus', 'w-4 h-4') ?>
                    <span><?= __('signup_btn') ?></span>
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="my-6 border-t border-slate-100 dark:border-slate-700 relative">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-800 px-3 text-xs text-slate-400 font-medium">Or</span>
        </div>

        <!-- Login Link -->
        <p class="text-center text-xs text-slate-500 dark:text-slate-400">
            <?= __('already_have_account') ?> 
            <a href="./?page=login" class="font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition"><?= __('nav_login') ?></a>
        </p>

    </div>
</div>
