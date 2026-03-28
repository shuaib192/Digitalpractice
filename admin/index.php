<?php
/**
 * Digital Practice - Admin Entry Point
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect to dashboard
redirect(SITE_URL . '/admin/dashboard');
