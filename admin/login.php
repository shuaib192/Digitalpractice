<?php
/**
 * Digital Practice - Admin Login
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    redirect(SITE_URL . '/admin/dashboard');
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];
            redirect(SITE_URL . '/admin/dashboard');
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Authentication | <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>/index.css?v=<?php echo ASSET_VERSION; ?>">
<style>
    body { 
        background-color: var(--color-off-white); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        min-height: 100vh; 
        margin: 0; 
        padding: 2rem;
    }
    .admin-login-card { 
        background: var(--color-white); 
        width: 100%;
        max-width: 480px;
        padding: 4rem; 
        box-shadow: 0 40px 100px -20px rgba(0,0,0,0.15);
        border: 1px solid var(--color-gray-100);
    }
    .login-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: var(--font-heading);
        font-size: 1.4rem;
        font-weight: 900;
        color: var(--color-primary);
        margin-bottom: 4rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .login-brand i { color: var(--color-accent); font-size: 1.6rem; }
    .login-brand span { color: var(--color-accent); }

    .login-header h1 { 
        font-size: 2.2rem; 
        color: var(--color-primary); 
        margin-bottom: 0.8rem; 
        font-weight: 900;
        letter-spacing: -0.5px;
    }
    .login-header p { 
        color: var(--color-gray-400); 
        font-size: 1rem; 
        margin-bottom: 3.5rem; 
        font-weight: 500;
    }

    .login-footer {
        margin-top: 4rem;
        text-align: center;
        border-top: 1px solid var(--color-gray-50);
        padding-top: 2rem;
    }
    .login-footer a {
        color: var(--color-gray-400);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: color 0.2s;
    }
    .login-footer a:hover { color: var(--color-accent); }

    @media (max-width: 600px) {
        .admin-login-card { padding: 3rem 2rem; }
    }
</style>
</head>
<body>

<div class="admin-login-card">
    <div class="login-brand">
        <i class="fas fa-bolt"></i> Digital<span>Practice</span>
    </div>

    <div class="login-header">
        <h1>Executive Access</h1>
        <p>Authorize your administrative credentials.</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error" style="margin-bottom: 2.5rem; border-radius: 0;">
            <i class="fas fa-shield-alt" style="margin-right:10px;"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Administrative Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Entrust identity" required autofocus>
        </div>
        <div class="form-group" style="margin-bottom: 3rem;">
            <label for="password">Security Passport (Password)</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Entrust security key" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem; font-size: 1rem; letter-spacing: 2px;">
            Authorize Access <i class="fas fa-chevron-right" style="margin-left:10px;"></i>
        </button>
    </form>

    <div class="login-footer">
        <a href="<?php echo SITE_URL; ?>">
            <i class="fas fa-arrow-left" style="margin-right:8px;"></i> De-authorize & Return
        </a>
    </div>
</div>

</body>
</html>
