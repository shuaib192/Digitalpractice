<?php
/**
 * Digital Practice - Admin Logout
 */
require_once __DIR__ . '/../includes/db.php';
session_destroy();
header('Location: ' . SITE_URL . '/admin/login');
exit();
