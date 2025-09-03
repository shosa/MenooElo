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
    
    <?php 
    // Calculate font settings first
    $fontFamily = 'Inter';
    $fontImports = '';
    
    if (isset($customFontName) && isset($customFontPath) && $customFontName && $customFontPath): 
        // Custom font uploaded
        $fontFamily = $customFontName;
        $fontImports = "@font-face {
            font-family: '{$customFontName}';
            src: url('" . BASE_URL . "/uploads/{$customFontPath}') format('woff2'),
                 url('" . BASE_URL . "/uploads/{$customFontPath}') format('woff'),
                 url('" . BASE_URL . "/uploads/{$customFontPath}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }";
    elseif (isset($primaryFont) && $primaryFont && $primaryFont !== 'Inter'): 
        // Google Font selected
        $fontFamily = $primaryFont;
        $fontImports = "@import url('https://fonts.googleapis.com/css2?family=" . urlencode($primaryFont) . ":wght@300;400;500;600;700&display=swap');";
    endif;
    ?>
    
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
                        'sans': ['<?= $fontFamily ?? 'Inter' ?>', 'system-ui', 'sans-serif'],
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
    
    
    <?php if ($fontImports): ?>
    <style>
        <?= $fontImports ?>
        
        body, h1, h2, h3, h4, h5, h6, p, span, div, button, input, textarea, select, label, a:not(.fa):not([class*="fa-"]) {
            font-family: '<?= $fontFamily ?>', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        }
        
        /* Preserve Font Awesome icons */
        .fa, .fas, .far, .fal, .fab, [class*="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
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