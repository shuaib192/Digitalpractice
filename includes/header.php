<?php
/**
 * Digital Practice - Site Header
 */
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? sanitize($page_description) : SITE_NAME . ' - ' . SITE_TAGLINE; ?>">
    <meta name="keywords" content="digital practice, product development, IT consulting, Africa technology, enterprise solutions, digital transformation">
    <meta name="author" content="Digital Practice">
    
    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
    <meta property="og:title" content="<?php echo isset($page_title) ? sanitize($page_title) : SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? sanitize($page_description) : SITE_TAGLINE; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/logo.png">

    <title><?php echo isset($page_title) ? sanitize($page_title) . ' | ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>

    <!-- Favicon Integration -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>/index.css?v=<?php echo ASSET_VERSION; ?>">
</head>
<body>

<!-- Custom Cursor -->
<div class="custom-cursor" id="custom-cursor"></div>
<div class="custom-cursor-follower" id="custom-cursor-follower"></div>

<?php
// Ensure DB is available for dynamic header items
global $pdo;
$header_services = [];
if (isset($pdo)) {
    try {
        $header_services = $pdo->query('SELECT title, slug FROM services WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
    } catch(Exception $e) {}
}
?>
<header class="site-header" id="site-header">
    <div class="container header-inner">
        <a href="<?php echo SITE_URL; ?>" class="logo" style="display: flex; align-items: center;">
            <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Digital Practice" style="height: 45px; width: auto;">
        </a>

        <nav class="nav-menu">
            <!-- Company Mega-Menu -->
            <div class="dropdown-container">
                <a href="<?php echo SITE_URL; ?>/company" class="nav-link">Company <i class="fas fa-chevron-down" style="font-size:0.75rem;margin-left:4px;"></i></a>
                <div class="mega-dropdown">
                    <ul class="mega-list">
                        <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about#partners">Partners & Affiliates</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about#team">Our Leadership</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Dynamic Services Menu -->
            <div class="dropdown-container">
                <a href="<?php echo SITE_URL; ?>/services" class="nav-link">Services <i class="fas fa-chevron-down" style="font-size:0.75rem;margin-left:4px;"></i></a>
                <div class="mega-dropdown">
                    <ul class="mega-list">
                        <?php foreach($header_services as $hs): ?>
                            <li><a href="<?php echo SITE_URL; ?>/services/<?php echo $hs['slug']; ?>"><?php echo htmlspecialchars($hs['title']); ?></a></li>
                        <?php endforeach; ?>
                        <?php if(empty($header_services)): ?>
                            <li><a href="#">Business Transformation</a></li>
                            <li><a href="#">Data & AI</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Digital Products Storefront Direct Link -->
            <div class="dropdown-container">
                <a href="<?php echo SITE_URL; ?>/products" class="nav-link">Digital Products</a>
            </div>
            
            
            <!-- Insights & Case Studies: Global Knowledge Base -->
            <div class="dropdown-container">
                <a href="<?php echo SITE_URL; ?>/blog" class="nav-link">Blog & Insights <i class="fas fa-chevron-down" style="font-size:0.75rem;margin-left:4px;"></i></a>
                <div class="mega-dropdown">
                    <ul class="mega-list">
                        <li><a href="<?php echo SITE_URL; ?>/blog">Thought Leadership (Blog)</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/case-studies">Proven Case Studies</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="dropdown-container"><a href="<?php echo SITE_URL; ?>/contact" class="nav-link">Contact Us</a></div>
        </nav>

        <div class="header-cta">
            <button class="menu-toggle" id="menu-toggle" aria-label="Toggle menu" style="transition: color 0.3s;">
                <i class="fas fa-bars-staggered" style="font-size: 1.8rem;"></i>
            </button>
        </div>
    </div>
</header>
