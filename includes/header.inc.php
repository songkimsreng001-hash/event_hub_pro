<?php
$htmlLang = getLang();
?>
<!DOCTYPE html>
<html lang="<?= $htmlLang ?>" class="h-full bg-slate-50 dark:bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('app_name') ?> | <?= __('app_tagline') ?></title>
    <link rel="icon" type="image/jpeg" href="./assets/images/Event_mgt_logo.jpg">
    <!-- Dark Mode & Sidebar Initializer (Prevents Theme Flash) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        if (localStorage.getItem('sidebar_collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>

    <!-- Google Fonts (Plus Jakarta Sans & Kantumruy Pro for Khmer) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Kantumruy Pro"', 'Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            750: '#233044',
                            850: '#162032',
                            950: '#0b1120',
                        },
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05)',
                        'hover': '0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }
    </script>

    <!-- Cropper.js CDN (For Photo Cropping) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Custom Stylesheet -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body
    class="min-h-full flex flex-col text-slate-800 dark:text-slate-100 antialiased bg-slate-50/70 dark:bg-slate-950 selection:bg-emerald-500 selection:text-white transition-colors duration-200">