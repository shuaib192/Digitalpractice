<?php
/**
 * Digital Practice - Administrative Profile Management
 */
$admin_title = 'My Profile';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$admin_id = $_SESSION['admin_id'];

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    
    if ($full_name && $email) {
        $stmt = $pdo->prepare('UPDATE admins SET full_name = ?, email = ? WHERE id = ?');
        $stmt->execute([$full_name, $email, $admin_id]);
        
        $_SESSION['admin_name'] = $full_name;
        $_SESSION['admin_email'] = $email;
        
        setFlash('success', 'Profile updated successfully.');
        redirect(SITE_URL . '/admin/profile');
    } else {
        setFlash('error', 'Full name and email are required.');
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_pwd = $_POST['current_password'];
    $new_pwd = $_POST['new_password'];
    $confirm_pwd = $_POST['confirm_password'];
    
    // Fetch current hash
    $stmt = $pdo->prepare('SELECT password FROM admins WHERE id = ?');
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();
    
    if (password_verify($current_pwd, $admin['password'])) {
        if ($new_pwd === $confirm_pwd && !empty($new_pwd)) {
            $new_hash = password_hash($new_pwd, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE admins SET password = ? WHERE id = ?');
            $stmt->execute([$new_hash, $admin_id]);
            setFlash('success', 'Password changed successfully.');
        } else {
            setFlash('error', 'New passwords do not match.');
        }
    } else {
        setFlash('error', 'Current password is incorrect.');
    }
    redirect(SITE_URL . '/admin/profile');
}

// Fetch current data
$stmt = $pdo->prepare('SELECT * FROM admins WHERE id = ?');
$stmt->execute([$admin_id]);
$current_admin = $stmt->fetch();

include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Identity & Executive Security</h1>
    <p style="color:var(--color-gray-400); font-size:0.9rem; margin-top:0.5rem;">Orchestrate your administrative credentials and secure access parameters.</p>
</div>

<?php renderFlash(); ?>

<div class="grid-2" style="gap: 4rem;">
    
    <!-- Profile Info: Strategic Identity -->
    <div class="admin-card">
        <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
            <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Strategic Identity</h2>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="full_name">Legal Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo sanitize($current_admin['full_name']); ?>" required placeholder="e.g. Alexander Roberts">
            </div>

            <div class="form-group">
                <label for="email">Administrative Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo sanitize($current_admin['email']); ?>" required placeholder="admin@digitalpractice.org">
            </div>

            <div class="form-group">
                <label>Operational Access Tier</label>
                <div style="background:var(--color-off-white); border:1px solid var(--color-gray-100); padding:1rem 1.5rem; font-weight:800; color:var(--color-primary); letter-spacing:1px;">
                    <?php echo strtoupper($current_admin['role']); ?>
                </div>
                <small style="color:var(--color-gray-400); margin-top:1rem; display:block; font-style:italic;">Access tiers are managed by the Chief Systems Architect.</small>
            </div>

            <div style="margin-top: 3.5rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
                <button type="submit" name="update_profile" class="btn btn-primary" style="padding: 1.2rem 3rem;">
                    <i class="fas fa-user-shield" style="margin-right:10px;"></i> Commit Identity Updates
                </button>
            </div>
        </form>
    </div>

    <!-- Password Change: Credential Rotation -->
    <div class="admin-card">
        <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
            <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Credential Rotation</h2>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="current_password">Existing Security Key</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="••••••••••••">
            </div>

            <div style="margin-top:3rem; padding-top:2.5rem; border-top:1px dashed var(--color-gray-100);">
                <div class="form-group">
                    <label for="new_password">New Strategic Credential</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required placeholder="Generate a high-entropy key">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Verify New Credential</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Confirm high-entropy key">
                </div>
            </div>

            <div style="margin-top: 3.5rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
                <button type="submit" name="change_password" class="btn btn-outline" style="width:100%; border:1px solid var(--color-accent); color:var(--color-accent); padding:1.2rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
                    <i class="fas fa-sync-alt" style="margin-right:10px;"></i> Authorize Key Rotation
                </button>
            </div>
        </form>
    </div>

</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
