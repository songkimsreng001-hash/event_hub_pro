<?php
$username = '';
$usernameErr = $passwdErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $passwd   = trim($_POST['passwd'] ?? '');

    if (empty($username)) {
        $usernameErr = 'Please enter your username.';
    }
    if (empty($passwd)) {
        $passwdErr = 'Please enter your password.';
    }

    if (empty($usernameErr) && empty($passwdErr)) {
        $auth = logUserIn($username, $passwd);
        if ($auth !== false) {
            $_SESSION['user_id'] = $auth->id;
            setFlash('success', 'Welcome back, ' . $auth->name . '!', 'Signed In Successfully');
            redirect('./?page=dashboard');
        } else {
            $usernameErr = 'Invalid username or password.';
        }
    }
}
?>

<div class="max-w-md mx-auto my-6 sm:my-12">
    <!-- Card Container -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-xl shadow-slate-200/50 dark:shadow-none p-7 sm:p-9">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-3">
                <a href="./" class="inline-block transition transform hover:scale-105" title="EventHub Pro">
                    <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-16 h-16 rounded-2xl object-cover shadow-lg shadow-emerald-600/20 ring-2 ring-emerald-500/30">
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight"><?= __('signin_title') ?></h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('signin_subtitle') ?></p>
        </div>

        <!-- Form -->
        <form method="post" action="./?page=login" class="space-y-5">
            
            <!-- Username Input -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"><?= __('username') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <?= getIcon('user', 'w-4 h-4') ?>
                    </div>
                    <input type="text" 
                           name="username" 
                           value="<?= htmlspecialchars($username) ?>" 
                           placeholder="admin / janedoe"
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

            <!-- Password Input -->
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

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/30 transition-all duration-150 transform active:scale-[0.99] flex items-center justify-center gap-2">
                <?= getIcon('login', 'w-4 h-4') ?>
                <span><?= __('signin_btn') ?></span>
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 border-t border-slate-100 dark:border-slate-700 relative">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-800 px-3 text-xs text-slate-400 font-medium">Or</span>
        </div>

        <!-- Register Link -->
        <p class="text-center text-xs text-slate-500 dark:text-slate-400">
            <?= __('dont_have_account') ?> 
            <a href="./?page=register" class="font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition"><?= __('create_account') ?></a>
        </p>
        </div>

    </div>
</div>
