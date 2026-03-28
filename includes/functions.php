<?php
/**
 * Digital Practice - Helper Functions
 */

/**
 * Sanitize user input
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Get site setting from database
 */
function getSetting($key, $default = '') {
    global $pdo;
    if (!isset($pdo)) return $default;
    
    $stmt = $pdo->prepare('SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1');
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

/**
 * Get current page name for active nav highlighting
 */
function currentPage() {
    $path = basename($_SERVER['PHP_SELF'], '.php');
    return $path ?: 'index';
}

/**
 * Check if a page is active
 */
function isActive($page) {
    return currentPage() === $page ? 'active' : '';
}

/**
 * Redirect helper
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Check admin login
 */
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        redirect(SITE_URL . '/admin/login');
    }
}

/**
 * Generate slug from title
 */
function generateSlug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Flash messages
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function renderFlash() {
    $flash = getFlash();
    if ($flash) {
        $type = $flash['type'] === 'success' ? 'alert-success' : 'alert-error';
        $icon = $flash['type'] === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        echo '<div class="alert ' . $type . '"><i class="' . $icon . '"></i> ' . sanitize($flash['message']) . '</div>';
    }
}

/**
 * Truncate text
 */
function truncateText($text, $length = 120) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Time ago
 */
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' min' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}

/**
 * Handle Image Uploads
 */
function uploadImage($file, $folder = 'uploads') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $upload_dir = __DIR__ . '/../assets/images/' . $folder . '/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $extension;
    $target = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $filename;
    }

    return null;
}

/**
 * Professional Pagination Renderer
 */
function renderPagination($current_page, $total_pages, $base_url) {
    if ($total_pages <= 1) return '';
    
    $html = '<div class="pagination-container" style="display:flex; justify-content:center; gap:0.5rem; margin-top:4rem;">';
    
    // First / Prev
    if ($current_page > 1) {
        $html .= '<a href="' . $base_url . (strpos($base_url, '?') !== false ? '&' : '?') . 'page=1" class="page-btn" title="Frontier"><i class="fas fa-angle-double-left"></i></a>';
        $html .= '<a href="' . $base_url . (strpos($base_url, '?') !== false ? '&' : '?') . 'page=' . ($current_page - 1) . '" class="page-btn"><i class="fas fa-angle-left"></i></a>';
    }

    // Dynamic Range (showing 5 pages around current)
    $start = max(1, $current_page - 2);
    $end = min($total_pages, $current_page + 2);

    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $current_page) ? ' active' : '';
        $html .= '<a href="' . $base_url . (strpos($base_url, '?') !== false ? '&' : '?') . 'page=' . $i . '" class="page-btn' . $active . '">' . $i . '</a>';
    }

    // Next / Last
    if ($current_page < $total_pages) {
        $html .= '<a href="' . $base_url . (strpos($base_url, '?') !== false ? '&' : '?') . 'page=' . ($current_page + 1) . '" class="page-btn"><i class="fas fa-angle-right"></i></a>';
        $html .= '<a href="' . $base_url . (strpos($base_url, '?') !== false ? '&' : '?') . 'page=' . $total_pages . '" class="page-btn" title="Horizon"><i class="fas fa-angle-double-right"></i></a>';
    }

    $html .= '</div>';
    return $html;
}

/**
 * Global Search Sanitizer
 */
function getSearchQuery() {
    return isset($_GET['s']) ? sanitize($_GET['s']) : '';
}

/**
 * Intelligent Category Retrieval
 */
function getBlogCategories() {
    global $pdo;
    try {
        return $pdo->query('SELECT * FROM blog_categories ORDER BY name ASC')->fetchAll();
    } catch(Exception $e) {
        return [];
    }
}
