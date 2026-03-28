<?php
/**
 * Digital Practice - Admin Header
 */
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($admin_title) ? sanitize($admin_title) . ' | ' : ''; ?>Admin - <?php echo SITE_NAME; ?></title>
    
    <!-- Favicon Integration -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>/index.css?v=<?php echo ASSET_VERSION; ?>">
</head>
<body>
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <a href="<?php echo SITE_URL; ?>" class="logo logo-light" style="display:flex; align-items:center;">
            <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Digital Practice Admin" style="height: 35px; width: auto;">
        </a>

        <nav class="admin-nav">
            <a href="<?php echo SITE_URL; ?>/admin/dashboard" class="<?php echo isActive('dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/services" class="<?php echo isActive('services') ? 'active' : ''; ?>">
                <i class="fas fa-cogs"></i> Services
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/products" class="<?php echo isActive('products') ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/team" class="<?php echo isActive('team') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Team
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/partners" class="<?php echo isActive('partners') ? 'active' : ''; ?>">
                <i class="fas fa-handshake"></i> Partners
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/categories" class="<?php echo isActive('categories') ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/blog" class="<?php echo isActive('blog') ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/contact" class="<?php echo isActive('contact') ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Submissions
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/case_studies" class="<?php echo isActive('case_studies') ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i> Case Studies
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/system" class="<?php echo isActive('system') ? 'active' : ''; ?>">
                <i class="fas fa-microchip"></i> System Optimizer
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/settings" class="<?php echo isActive('settings') ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/profile" class="<?php echo isActive('profile') ? 'active' : ''; ?>">
                <i class="fas fa-user-circle"></i> My Profile
            </a>
        </nav>

        <div style="margin-top:auto;padding-top:var(--space-xl);border-top:1px solid rgba(255,255,255,0.08);">
            <a href="<?php echo SITE_URL; ?>/admin/logout" class="admin-nav" style="display:flex;align-items:center;gap:var(--space-sm);color:rgba(255,255,255,0.5);font-size:0.85rem;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header-row" id="mobile-admin-header">
            <a href="<?php echo SITE_URL; ?>/admin/dashboard" class="logo">
                Digital<span>Practice</span>
            </a>
            <button class="btn btn-primary admin-menu-toggle" id="admin-sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <?php if (currentPage() !== 'dashboard'): ?>
        <nav style="margin-bottom: 2rem;">
            <a href="<?php echo SITE_URL; ?>/admin/dashboard" style="color:var(--color-gray-400); font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; text-decoration:none;">
                <i class="fas fa-arrow-left" style="margin-right:5px;"></i> Back to Dashboard
            </a>
        </nav>
        <?php endif; ?>
