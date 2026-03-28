<?php
/**
 * Digital Practice - Global Settings Management
 */
$admin_title = 'Site Settings';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => $_POST['site_name'] ?? '',
        'site_tagline' => $_POST['site_tagline'] ?? '',
        'site_email' => $_POST['site_email'] ?? '',
        'site_phone' => $_POST['site_phone'] ?? '',
        'site_address' => $_POST['site_address'] ?? '',
        'social_linkedin' => $_POST['social_linkedin'] ?? '',
        'social_twitter' => $_POST['social_twitter'] ?? '',
        'social_facebook' => $_POST['social_facebook'] ?? '',
        'social_instagram' => $_POST['social_instagram'] ?? '',
    ];

    foreach ($settings as $key => $value) {
        $stmt = $pdo->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) 
                               ON DUPLICATE KEY UPDATE setting_value = ?');
        $stmt->execute([$key, $value, $value]);
    }

    setFlash('success', 'Configuration saved successfully.');
    redirect(SITE_URL . '/admin/settings');
}

include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Global Configuration</h1>
    <p style="color:var(--color-gray-400); font-size:0.9rem;">Manage your public-facing contact information and site metadata.</p>
</div>

<?php renderFlash(); ?>

<form method="POST" action="">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        <!-- General Settings -->
        <div class="admin-card">
            <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:2rem; border-bottom:1px solid var(--color-gray-100); padding-bottom:1rem;">Company Information</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="site_name">Enterprise Name</label>
                    <input type="text" id="site_name" name="site_name" class="form-control" value="<?php echo getSetting('site_name', SITE_NAME); ?>" placeholder="Digital Practice">
                </div>
                <div class="form-group">
                    <label for="site_tagline">Tagline / Mission</label>
                    <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="<?php echo getSetting('site_tagline', SITE_TAGLINE); ?>" placeholder="Powering Africa's Digital Future">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="site_email">Contact Email (Public)</label>
                    <input type="email" id="site_email" name="site_email" class="form-control" value="<?php echo getSetting('site_email', SITE_EMAIL); ?>" placeholder="info@digitalpractice.org">
                </div>
                <div class="form-group">
                    <label for="site_phone">Phone Number</label>
                    <input type="text" id="site_phone" name="site_phone" class="form-control" value="<?php echo getSetting('site_phone', SITE_PHONE); ?>" placeholder="+234 800 000 0000">
                </div>
            </div>

            <div class="form-group">
                <label for="site_address">Mailing Address</label>
                <textarea id="site_address" name="site_address" class="form-control" rows="3" placeholder="Lagos, Nigeria"><?php echo getSetting('site_address', SITE_ADDRESS); ?></textarea>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="background:var(--color-accent); border:none; border-radius:0; padding: 1.2rem 3rem; font-weight:800; text-transform:uppercase;">
                    <i class="fas fa-save" style="margin-right:0.5rem;"></i> Update Configuration
                </button>
            </div>
        </div>

        <!-- Social Settings -->
        <div class="admin-card">
            <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:2rem; border-bottom:1px solid var(--color-gray-100); padding-bottom:1rem;">Social Media Presence</h2>
            
            <div class="form-group">
                <label><i class="fab fa-linkedin" style="margin-right:8px; color:#0077b5;"></i> LinkedIn URL</label>
                <input type="text" name="social_linkedin" class="form-control" value="<?php echo getSetting('social_linkedin'); ?>" placeholder="https://linkedin.com/company/...">
            </div>

            <div class="form-group">
                <label><i class="fab fa-x-twitter" style="margin-right:8px; color:#000;"></i> X/Twitter URL</label>
                <input type="text" name="social_twitter" class="form-control" value="<?php echo getSetting('social_twitter'); ?>" placeholder="https://x.com/...">
            </div>

            <div class="form-group">
                <label><i class="fab fa-facebook" style="margin-right:8px; color:#1877f2;"></i> Facebook Page</label>
                <input type="text" name="social_facebook" class="form-control" value="<?php echo getSetting('social_facebook'); ?>" placeholder="https://facebook.com/...">
            </div>

            <div class="form-group">
                <label><i class="fab fa-instagram" style="margin-right:8px; color:#e4405f;"></i> Instagram Handle</label>
                <input type="text" name="social_instagram" class="form-control" value="<?php echo getSetting('social_instagram'); ?>" placeholder="https://instagram.com/...">
            </div>
        </div>

    </div>
</form>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
