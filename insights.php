<?php
/**
 * Digital Practice - Insights & Blog (Redirect Architecture)
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Permanent redirection to the new Global Intelligence /blog module
header("HTTP/1.1 301 Moved Permanently");
header("Location: " . SITE_URL . "/blog");
exit;
