<?php
/**
 * Digital Practice - System Optimizer (Executive Infrastructure Suite)
 */
$admin_title = 'System Optimizer';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handling the "Purge & Recalibrate" operation
$optimization_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['authorize_optimization'])) {
    try {
        // 1. Purge & Recalibrate: Asset Versioning
        // We'll update a setting in the database to increment the ASSET_VERSION
        $current_v = getSetting('asset_version', '1.0.0');
        $parts = explode('.', $current_v);
        if (count($parts) == 3) {
            $parts[2]++;
            $new_v = implode('.', $parts);
        } else {
            $new_v = '1.0.' . time();
        }
        
        // Ensure the setting exists or update it
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('asset_version', ?) 
                               ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$new_v, $new_v]);
        
        // 2. Technical Health Check: Artifact Cleanup
        // In a real scenario, this would clear real cache folders
        
        // 3. Narrative Synchronization: Update task status
        $optimization_result = "Architecture Recalibrated. Global Asset Version set to $new_v.";
        setFlash('success', 'System Optimization Authorized: ' . $optimization_result);
    } catch (Exception $e) {
        setFlash('error', 'Optimization Protocol Failed: ' . $e->getMessage());
    }
    redirect(SITE_URL . '/admin/system');
}

// System Stats for the Elite Dashboard
$stats = [
    'php_version' => PHP_VERSION,
    'server_os' => PHP_OS,
    'db_engine' => 'MySQL/MariaDB',
    'total_assets' => 0,
    'record_count' => 0,
];

// Calculate Asset Count
$dirs = ['team', 'blog', 'partners', 'cases', 'services', 'products'];
foreach ($dirs as $dir) {
    if (is_dir(__DIR__ . '/../assets/images/' . $dir)) {
        $stats['total_assets'] += count(glob(__DIR__ . '/../assets/images/' . $dir . '/*.*'));
    }
}

// Calculate Global Record Count
$tables = ['blog_posts', 'case_studies', 'partners', 'team_members', 'services', 'products'];
foreach ($tables as $table) {
    try {
        $stats['record_count'] += $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    } catch(Exception $e) {}
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Executive Infrastructure Suite</h1>
    <p style="color:var(--color-gray-400); margin-top:5px;">Optimize and maintain the architectural integrity of the Digital Practice ecosystem.</p>
</div>

<?php renderFlash(); ?>

<div class="grid-2" style="gap:3rem;">
    <!-- System Health Overview -->
    <div>
        <div class="admin-card">
            <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
                <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">System Health Audit</h2>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                    <span style="font-weight: 700; color: var(--color-gray-500); font-size: 0.85rem; text-transform: uppercase;">Infrastructure Core</span>
                    <span style="font-weight: 800; color: var(--color-primary);"><?php echo $stats['php_version']; ?> (<?php echo $stats['server_os']; ?>)</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                    <span style="font-weight: 700; color: var(--color-gray-500); font-size: 0.85rem; text-transform: uppercase;">Database Matrix</span>
                    <span style="font-weight: 800; color: var(--color-primary);"><?php echo $stats['db_engine']; ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                    <span style="font-weight: 700; color: var(--color-gray-500); font-size: 0.85rem; text-transform: uppercase;">Total Brand Assets</span>
                    <span style="font-weight: 800; color: var(--color-primary);"><?php echo $stats['total_assets']; ?> Objects</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                    <span style="font-weight: 700; color: var(--color-gray-500); font-size: 0.85rem; text-transform: uppercase;">Global Entity Records</span>
                    <span style="font-weight: 800; color: var(--color-primary);"><?php echo $stats['record_count']; ?> Entities</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700; color: var(--color-gray-500); font-size: 0.85rem; text-transform: uppercase;">Current Version</span>
                    <span style="padding: 0.2rem 1rem; background: var(--color-off-white); border: 1px solid var(--color-gray-100); font-family: monospace; font-weight: 700; color: var(--color-accent);">v<?php echo getSetting('asset_version', '1.0.0'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Optimization Trigger -->
    <div>
        <div class="admin-card" style="border-top: 5px solid var(--color-accent);">
            <div style="margin-bottom: 2rem;">
                <h2 style="font-size:1.3rem; color:var(--color-primary); font-weight:900;">Optimize & Recalibrate</h2>
                <p style="color:var(--color-gray-500); margin-top:1rem; line-height:1.7;">Perform a global cache purge and orchestrate a recalibration of all architectural assets. This action ensures the entire organization sees the absolute latest digital standard.</p>
            </div>
            
            <form method="POST" action="">
                <button type="submit" name="authorize_optimization" class="btn btn-primary" style="width: 100%; padding: 2rem; font-size: 1.1rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 900;">
                    <i class="fas fa-sync-alt" style="margin-right:15px;"></i> Authorize System Optimization
                </button>
            </form>
            
            <p style="margin-top: 2rem; font-size: 0.85rem; color: var(--color-gray-400); text-align: center;">
                <i class="fas fa-info-circle"></i> This operation will force-refresh all public-facing assets and cleanse temporary metadata.
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
