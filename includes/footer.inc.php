<footer class="mt-auto bg-white dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800 py-8 text-slate-500 dark:text-slate-400 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <img src="./assets/images/Event_mgt_logo.jpg" alt="EventHub Pro" class="w-7 h-7 rounded-lg object-cover shadow-xs">
                <span class="font-bold text-slate-800 dark:text-white text-sm"><?= __('app_name') ?></span>
                <span class="text-xs text-slate-400">&bull; <?= __('app_tagline') ?></span>
            </div>
            <div class="text-xs text-slate-400">
                &copy; <?= date('Y') ?> <?= __('app_name') ?>. @kimsreng.song
            </div>
        </div>
    </div>
</footer>

<!-- Crop Photo Modal (Cropper.js) -->
<div id="cropPhotoModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs hidden">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700 animate-in fade-in zoom-in-95 duration-200 flex flex-col max-h-[92vh]">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <?= getIcon('crop', 'w-5 h-5') ?>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base"><?= __('crop_title') ?></h3>
                    <p class="text-xs text-slate-400"><?= __('crop_desc') ?></p>
                </div>
            </div>
            <button type="button" id="cropModalCancelBtn" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <?= getIcon('x', 'w-5 h-5') ?>
            </button>
        </div>

        <!-- Cropper View Area -->
        <div class="p-4 bg-slate-100 dark:bg-slate-900 flex-1 flex items-center justify-center overflow-hidden min-h-[260px] max-h-[380px]">
            <img id="cropperImageElement" src="" alt="To Crop" class="max-w-full max-h-[360px] block">
        </div>

        <!-- Controls (Rotate, Reset, Apply) -->
        <div class="p-4 bg-slate-50 dark:bg-slate-800/90 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1.5">
                <button type="button" id="cropRotateLeftBtn" class="px-2.5 py-1.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100" title="Rotate -90°">
                    ⟲ -90°
                </button>
                <button type="button" id="cropRotateRightBtn" class="px-2.5 py-1.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100" title="Rotate +90°">
                    ⟳ +90°
                </button>
                <button type="button" id="cropResetBtn" class="p-2 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100" title="<?= __('reset') ?>">
                    <?= getIcon('refresh', 'w-3.5 h-3.5') ?>
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="cropModalDismissBtn" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700">
                    <?= __('cancel') ?>
                </button>
                <button type="button" id="cropApplyBtn" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 flex items-center gap-1.5">
                    <?= getIcon('check', 'w-4 h-4') ?>
                    <span><?= __('crop_confirm') ?></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Client Application Configuration & Localized Strings -->
<script>
    window.AppConfig = {
        lang: '<?= getLang() ?>',
        translations: {
            confirmTitle: '<?= addslashes(__('confirm_title')) ?>',
            confirmText: 'This action cannot be undone.',
            confirmYes: '<?= addslashes(__('confirm_yes')) ?>',
            cancel: '<?= addslashes(__('cancel')) ?>',
            lightMode: '<?= addslashes(__('light_mode')) ?>',
            darkMode: '<?= addslashes(__('dark_mode')) ?>',
            cropConfirm: '<?= addslashes(__('crop_confirm')) ?>'
        }
    };
</script>

<!-- Main Client Application Controller -->
<script src="./assets/js/app.js"></script>

<!-- Flash Notification Trigger -->
<?php if (hasFlash()): ?>
    <?php $flash = getFlash(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.showToast) {
                window.showToast('<?= addslashes($flash['type']) ?>', '<?= addslashes($flash['message']) ?>');
            }
        });
    </script>
<?php endif; ?>

</body>
</html>
