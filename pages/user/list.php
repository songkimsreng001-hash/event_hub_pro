<?php
$search = trim($_GET['search'] ?? '');
$users = getUsers(!empty($search) ? $search : null);
$total = $users ? $users->num_rows : 0;
?>

<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/70 dark:border-slate-700/60">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                <span><?= __('nav_users') ?></span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                    <?= $total ?> <?= __('members') ?>
                </span>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('users_subtitle') ?></p>
        </div>

        <a href="./?page=user/create" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition-all duration-150 transform active:scale-95">
            <?= getIcon('user-plus', 'w-4 h-4') ?>
            <span><?= __('add_user') ?></span>
        </a>
    </div>

    <!-- Search Controls -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
        <form method="get" action="./" class="flex items-center gap-3">
            <input type="hidden" name="page" value="user/list">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <?= getIcon('search', 'w-4 h-4') ?>
                </div>
                <input type="text" 
                       name="search" 
                       value="<?= htmlspecialchars($search) ?>" 
                       placeholder="<?= __('search_users_placeholder') ?>" 
                       class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white transition">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-emerald-600 hover:bg-slate-800 dark:hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">
                <?= __('search') ?>
            </button>
            <?php if (!empty($search)): ?>
                <a href="./?page=user/list" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition" title="<?= __('reset') ?>">
                    <?= getIcon('x', 'w-5 h-5') ?>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50/80 dark:bg-slate-700/50 text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-400 font-bold border-b border-slate-200/80 dark:border-slate-700/60">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4"><?= __('user_col') ?></th>
                        <th class="py-3.5 px-4"><?= __('role') ?></th>
                        <th class="py-3.5 px-4"><?= __('joined_date') ?></th>
                        <th class="py-3.5 px-4 text-right"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <?php 
                    $index = 0;
                    if ($total > 0):
                        while ($row = $users->fetch_object()):
                            $index++;
                    ?>
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="py-4 px-4 text-center font-bold text-slate-400 dark:text-slate-500 text-xs">
                                <?= $index ?>
                            </td>

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

                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $row->level === 'admin' ? 'bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' ?>">
                                    <?= htmlspecialchars($row->level) ?>
                                </span>
                            </td>

                            <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                <?= date('M d, Y', strtotime($row->created_at ?? 'now')) ?>
                            </td>

                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="./?page=user/update&id=<?= $row->id ?>" 
                                       class="p-2 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-xl transition" 
                                       title="<?= __('edit') ?>">
                                        <?= getIcon('pencil', 'w-4 h-4') ?>
                                    </a>

                                    <a href="./?page=user/delete&id=<?= $row->id ?>" 
                                       class="confirm-action p-2 text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-xl transition" 
                                       title="<?= __('delete') ?>"
                                       data-confirm-title="<?= __('confirm_title') ?>"
                                       data-confirm-text="User <?= htmlspecialchars($row->name) ?> will be deleted permanently."
                                       data-confirm-btn="<?= __('confirm_yes') ?>"
                                       data-confirm-icon="warning"
                                       data-confirm-color="red">
                                        <?= getIcon('trash', 'w-4 h-4') ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>

                    <?php if ($total === 0): ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-700/60 text-slate-400 dark:text-slate-400 flex items-center justify-center mb-2">
                                    <?= getIcon('users', 'w-6 h-6') ?>
                                </div>
                                <p class="text-sm font-medium"><?= __('no_users_found') ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
