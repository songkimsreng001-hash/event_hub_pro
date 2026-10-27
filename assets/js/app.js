/**
 * EventHub Pro - Client Application Controller
 * Handles UI interactions, themes, modals, Cropper.js, and SweetAlert2
 */

(function () {
    'use strict';

    // Retrieve i18n translations or set default fallbacks
    const i18n = (window.AppConfig && window.AppConfig.translations) ? window.AppConfig.translations : {
        confirmTitle: 'Are you sure?',
        confirmText: 'This action cannot be undone.',
        confirmYes: 'Yes, proceed',
        cancel: 'Cancel',
        lightMode: 'Light Mode',
        darkMode: 'Dark Mode',
        cropConfirm: 'Image cropped successfully!'
    };

    // =========================================================================
    // 1. SweetAlert2 Toast Helper
    // =========================================================================
    const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        customClass: {
            popup: 'dark:bg-slate-800 dark:text-white dark:border dark:border-slate-700 shadow-xl rounded-2xl'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    }) : null;

    window.showToast = function (icon, message) {
        if (Toast) {
            Toast.fire({
                icon: icon || 'info',
                title: message || ''
            });
        }
    };

    // =========================================================================
    // 2. Global Action Confirmation Dialogs (.confirm-action)
    // =========================================================================
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.confirm-action');
        if (!btn) return;

        e.preventDefault();
        const title = btn.getAttribute('data-confirm-title') || i18n.confirmTitle;
        const text = btn.getAttribute('data-confirm-text') || i18n.confirmText;
        const confirmBtnText = btn.getAttribute('data-confirm-btn') || i18n.confirmYes;
        const icon = btn.getAttribute('data-confirm-icon') || 'warning';
        const isDelete = btn.classList.contains('text-red-600') ||
            btn.classList.contains('bg-red-600') ||
            btn.getAttribute('data-confirm-color') === 'red';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: isDelete ? '#dc2626' : '#059669',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: confirmBtnText,
                cancelButtonText: i18n.cancel,
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white font-sans',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-medium text-sm transition',
                    cancelButton: 'px-5 py-2.5 rounded-xl font-medium text-sm transition'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (btn.tagName === 'A' && btn.href) {
                        window.location.href = btn.href;
                    } else if (btn.form) {
                        btn.form.submit();
                    } else {
                        const form = btn.closest('form');
                        if (form) form.submit();
                    }
                }
            });
        } else {
            // Fallback if Swal is not loaded
            if (confirm(title + '\n' + text)) {
                if (btn.tagName === 'A' && btn.href) {
                    window.location.href = btn.href;
                } else if (btn.form) {
                    btn.form.submit();
                } else {
                    const form = btn.closest('form');
                    if (form) form.submit();
                }
            }
        }
    });

    // =========================================================================
    // 3. Interactive Cropper.js Modal Controller
    // =========================================================================
    let currentCropper = null;
    let activeInput = null;
    let activePreview = null;
    let originalFileName = 'cropped_image.jpg';

    const cropModal = document.getElementById('cropPhotoModal');
    const cropperImg = document.getElementById('cropperImageElement');
    const cropApplyBtn = document.getElementById('cropApplyBtn');
    const cropResetBtn = document.getElementById('cropResetBtn');
    const cropRotateLeftBtn = document.getElementById('cropRotateLeftBtn');
    const cropRotateRightBtn = document.getElementById('cropRotateRightBtn');
    const cropModalCancelBtn = document.getElementById('cropModalCancelBtn');
    const cropModalDismissBtn = document.getElementById('cropModalDismissBtn');

    function closeCropModal() {
        if (cropModal) cropModal.classList.add('hidden');
        if (currentCropper) {
            currentCropper.destroy();
            currentCropper = null;
        }
    }

    if (cropModalCancelBtn) cropModalCancelBtn.addEventListener('click', closeCropModal);
    if (cropModalDismissBtn) cropModalDismissBtn.addEventListener('click', closeCropModal);

    if (cropRotateLeftBtn) {
        cropRotateLeftBtn.addEventListener('click', () => {
            if (currentCropper) currentCropper.rotate(-90);
        });
    }

    if (cropRotateRightBtn) {
        cropRotateRightBtn.addEventListener('click', () => {
            if (currentCropper) currentCropper.rotate(90);
        });
    }

    if (cropResetBtn) {
        cropResetBtn.addEventListener('click', () => {
            if (currentCropper) currentCropper.reset();
        });
    }

    if (cropApplyBtn) {
        cropApplyBtn.addEventListener('click', () => {
            if (!currentCropper || !activeInput) return;

            const canvas = currentCropper.getCroppedCanvas({
                maxWidth: 1280,
                maxHeight: 1280,
                imageSmoothingQuality: 'high'
            });

            if (!canvas) return;

            canvas.toBlob((blob) => {
                if (!blob) return;

                // Create a replacement File object from cropped canvas
                const croppedFile = new File([blob], originalFileName, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                // Assign to file input via DataTransfer API
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                activeInput.files = dataTransfer.files;

                // Update UI preview
                if (activePreview) {
                    activePreview.src = URL.createObjectURL(blob);
                }

                closeCropModal();

                if (window.showToast) {
                    window.showToast('success', i18n.cropConfirm);
                }
            }, 'image/jpeg', 0.9);
        });
    }

    window.attachCropperToInput = function (inputId, previewImgId, aspectRatio) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewImgId);
        if (!input || !preview) return;

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            // Validate type
            const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowed.includes(file.type)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File',
                        text: 'Please choose a JPG, PNG, or WEBP image file.',
                        confirmButtonColor: '#059669'
                    });
                } else {
                    alert('Please choose a JPG, PNG, or WEBP image file.');
                }
                this.value = '';
                return;
            }

            activeInput = this;
            activePreview = preview;
            originalFileName = file.name;

            const fileUrl = URL.createObjectURL(file);
            if (cropperImg) cropperImg.src = fileUrl;
            if (cropModal) cropModal.classList.remove('hidden');

            if (currentCropper) currentCropper.destroy();

            setTimeout(() => {
                if (typeof Cropper !== 'undefined' && cropperImg) {
                    currentCropper = new Cropper(cropperImg, {
                        aspectRatio: aspectRatio,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false
                    });
                }
            }, 50);
        });
    };

    // Auto-attach Cropper to known avatar and event image inputs
    document.addEventListener('DOMContentLoaded', function () {
        window.attachCropperToInput('profileUpload', 'profilePreview', 1);
        window.attachCropperToInput('userPhotoUpload', 'userPhotoPreview', 1);
        window.attachCropperToInput('eventImg', 'imgPreview', 16 / 9);
    });

    // =========================================================================
    // 4. Unified Event Controller (Theme, Sidebar, Navigation)
    // =========================================================================
    document.addEventListener('click', function (e) {
        // Theme Toggle Buttons
        const themeBtn = e.target.closest('#themeToggleBtnAdmin, #themeToggleBtnNavbar, #themeToggleBtnSidebar, #themeToggleBtnMobile');
        if (themeBtn) {
            e.preventDefault();
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            document.querySelectorAll('#themeToggleBtnAdmin, #themeToggleBtnNavbar, #themeToggleBtnSidebar, #themeToggleBtnMobile').forEach(b => {
                b.setAttribute('title', isDark ? i18n.lightMode : i18n.darkMode);
            });
            return;
        }

        // Sidebar Collapse / Expand Toggle
        const collapseBtn = e.target.closest('#toggleSidebarCollapseBtn, #toggleAdminSidebarDesktop');
        if (collapseBtn) {
            e.preventDefault();
            const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
            return;
        }

        // Mobile Sidebar Drawer Open
        if (e.target.closest('#openAdminSidebar')) {
            e.preventDefault();
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }
            return;
        }

        // Mobile Sidebar Drawer Close
        if (e.target.closest('#closeAdminSidebar, #adminSidebarBackdrop')) {
            e.preventDefault();
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
            return;
        }

        // User Mobile Menu Toggle
        if (e.target.closest('#mobileMenuBtn')) {
            e.preventDefault();
            const mobileDropdown = document.getElementById('mobileMenuDropdown');
            if (mobileDropdown) {
                mobileDropdown.classList.toggle('hidden');
            }
            return;
        }

        // Desktop User Menu Dropdown Toggle
        const userBtn = e.target.closest('#userMenuButton');
        const userDropdown = document.getElementById('userMenuDropdown');
        if (userBtn && userDropdown) {
            e.preventDefault();
            userDropdown.classList.toggle('hidden');
            return;
        }
        if (userDropdown && !userDropdown.classList.contains('hidden') && !e.target.closest('#userMenuWrapper')) {
            userDropdown.classList.add('hidden');
        }
    });

})();

