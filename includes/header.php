<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo getSetting('site_name', 'Timnah Schools'); ?></title>
    <meta name="description" content="<?php echo getSetting('site_tagline', 'Excellence in Education at Timnah Schools'); ?>">
    <meta name="keywords" content="Timnah Schools, education, private school, academic excellence, modern education">
    <meta name="author" content="Timnah Schools">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo getSetting('site_name', 'Timnah Schools'); ?>">
    <meta property="og:description" content="<?php echo getSetting('site_tagline', 'Excellence in Education'); ?>">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <meta property="og:site_name" content="<?php echo getSetting('site_name', 'Timnah Schools'); ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo getSetting('site_name', 'Timnah Schools'); ?>">
    <meta name="twitter:description" content="<?php echo getSetting('site_tagline', 'Excellence in Education'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_URL; ?>/images/favicon.ico">
    <link rel="icon" type="image/png" href="<?php echo ASSETS_URL; ?>/images/favicon.png">
    
    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Fonts (defer loading) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Critical CSS (inline for faster render) -->
    <style>
        :root { --bg-main: #0f0f0f; --primary: #00c853; }
        [data-theme="light"] { --bg-main: #f8f9fa; }
        body { background: var(--bg-main); margin: 0; font-family: 'Poppins', sans-serif; transition: background 0.3s ease; }
        .main-header { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; }
        .theme-toggle { cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-primary); transition: var(--transition); }
        .theme-toggle:hover { background: var(--primary-glow); border-color: var(--primary); transform: rotate(15deg); }
        .theme-toggle .fa-sun { display: none; }
        [data-theme="light"] .theme-toggle .fa-moon { display: none; }
        [data-theme="light"] .theme-toggle .fa-sun { display: block; color: #f39c12; }
    </style>
    
    <script>
        // Apply theme immediately to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    <!-- Noscript fallback for fonts -->
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>
    
    <script>
        window.SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-top">
                <div class="contact-info">
                    <span itemscope itemtype="https://schema.org/Organization">
                        <i class="fas fa-phone"></i> 
                        <span itemprop="telephone"><?php echo getSetting('site_phone', '+1 234 567 890'); ?></span>
                    </span>
                    <span itemscope itemtype="https://schema.org/Organization">
                        <i class="fas fa-envelope"></i> 
                        <span itemprop="email"><?php echo getSetting('site_email', 'info@timnahschools.edu'); ?></span>
                    </span>
                </div>
                
                <div class="social-links">
                    <div class="theme-toggle" id="themeToggle" title="Toggle Theme" style="margin-right: 15px;">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                    </div>
                    <a href="<?php echo getSetting('site_facebook', '#'); ?>" aria-label="Facebook" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php echo getSetting('site_twitter', '#'); ?>" aria-label="Twitter" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo getSetting('site_instagram', '#'); ?>" aria-label="Instagram" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <nav class="main-nav" itemscope itemtype="https://schema.org/School">
            <div class="container">
                <a href="<?php echo SITE_URL; ?>/index.php" class="logo" itemprop="url">
                    <img src="<?php echo ASSETS_URL; ?>/images/logo.png" alt="<?php echo getSetting('site_name', 'Timnah Schools'); ?> Logo" class="logo-img">
                    <span class="logo-text" itemprop="name"><?php echo getSetting('site_name', 'Timnah Schools'); ?></span>
                </a>
                
                <div class="search-container">
                    <div class="search-box" role="search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input type="search" id="globalSearch" placeholder="Search events, news, vacancies..." aria-label="Search" autocomplete="off">
                        <div id="searchResults" role="listbox" aria-hidden="true"></div>
                    </div>
                </div>

                <button class="mobile-toggle" aria-label="Toggle navigation menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <ul class="nav-menu" role="navigation" aria-label="Main navigation">
                    <li class="drawer-header mobile-only">
                        <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                            <img src="<?php echo ASSETS_URL; ?>/images/logo.png" alt="Logo" class="logo-img">
                        </a>
                        <button class="mobile-close" aria-label="Close navigation menu"><i class="fas fa-times"></i></button>
                    </li>

                    <li class="drawer-search-context mobile-only">
                        <div class="drawer-search-field" role="search">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <input type="search" id="mobileSearch" placeholder="Search..." aria-label="Search" autocomplete="off">
                        </div>
                    </li>

                    <li><a href="<?php echo SITE_URL; ?>/index.php" <?php echo $page === 'home' ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about.php" <?php echo $page === 'about' ? 'class="active"' : ''; ?>>About</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/events.php" <?php echo $page === 'events' ? 'class="active"' : ''; ?>>Events</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/facilities.php" <?php echo $page === 'facilities' ? 'class="active"' : ''; ?>>Facilities</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/staff.php" <?php echo $page === 'staff' ? 'class="active"' : ''; ?>>Staff</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/gallery.php" <?php echo $page === 'gallery' ? 'class="active"' : ''; ?>>Gallery</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/vacancies.php" <?php echo $page === 'vacancies' ? 'class="active"' : ''; ?>>Careers</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact.php" <?php echo $page === 'contact' ? 'class="active"' : ''; ?>>Contact</a></li>
                    <li class="enroll-nav-item mobile-only"><a href="<?php echo SITE_URL; ?>/register.php" class="btn-register-mobile">Enroll Now</a></li>
                </ul>
                <div class="nav-overlay"></div>
            </div>
        </nav>
    </header>
    <main itemscope itemtype="https://schema.org/School">