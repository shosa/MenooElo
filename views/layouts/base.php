<!DOCTYPE html>
<html lang="it" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MenooElo' ?></title>
    <meta name="description" content="<?= $description ?? 'Sistema di Menu Digitali per Ristoranti' ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/custom.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= $customTheme ?? '#3b82f6' ?>',
                        secondary: '#10b981',
                        accent: '#f59e0b',
                        neutral: '#374151',
                        'base-100': '#ffffff',
                        'base-200': '#f3f4f6',
                        'base-300': '#e5e7eb',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <?php if (isset($customTheme) && $customTheme): ?>
    <style>
        :root {
            --primary-color: <?= $customTheme ?>;
        }
    </style>
    <?php endif; ?>
    
    <?= $additionalHead ?? '' ?>
</head>
<body class="h-full bg-base-200 font-sans antialiased">
    <?= $content ?>
    
    <!-- Scripts -->
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>
    <?= $additionalScripts ?? '' ?>
</body>
</html>