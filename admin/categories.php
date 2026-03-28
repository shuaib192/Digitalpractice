<?php
/**
 * Digital Practice - Administrative Category Registry
 */
$admin_title = 'Global Intelligence Categories';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Operation Handle: Strategic Category Deployment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = sanitize($_POST['name'] ?? '');
    $slug = sanitize($_POST['slug'] ?: generateSlug($name));

    if (!empty($name)) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE blog_categories SET name = ?, slug = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $id]);
                setFlash('success', 'Strategic Category recalibrated successfully.');
            } else {
                $stmt = $pdo->prepare("INSERT INTO blog_categories (name, slug) VALUES (?, ?)");
                $stmt->execute([$name, $slug]);
                setFlash('success', 'New Intelligence Category deployed successfully.');
            }
        } catch (Exception $e) {
            setFlash('error', 'Architectural conflict: Category with this slug may already exist.');
        }
    } else {
        setFlash('error', 'Corporate entity name is mandatory.');
    }
    redirect(SITE_URL . '/admin/categories');
}

// Global Category removal protocol
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM blog_categories WHERE id = ?")->execute([$_GET['delete']]);
    setFlash('success', 'Strategic Category decommissioned.');
    redirect(SITE_URL . '/admin/categories');
}

// Fetch edit target
$editCat = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM blog_categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCat = $stmt->fetch();
}

$categories = $pdo->query("SELECT * FROM blog_categories ORDER BY name ASC")->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Strategic Intelligence Categories</h1>
    <p style="color:var(--color-gray-400); margin-top:5px;">Orchestrate the global metadata for all corporate thought leadership assets.</p>
</div>

<?php renderFlash(); ?>

<div class="grid-2" style="align-items:start; gap:3rem;">
    <!-- Deployment Matrix: Category Manager -->
    <div>
        <div class="admin-card">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                <h2 style="font-size:1.1rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
                    <?php echo $editCat ? 'Modify Category Identity' : 'Enroll Strategic Category'; ?>
                </h2>
            </div>
            
            <form method="POST" action="">
                <?php if ($editCat): ?>
                    <input type="hidden" name="id" value="<?php echo $editCat['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Category Label (Corporate Branding)</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $editCat ? $editCat['name'] : ''; ?>" required placeholder="e.g. Strategic Transformation">
                </div>
                
                <div class="form-group">
                    <label>Slug Index (Architectural Identifier)</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo $editCat ? $editCat['slug'] : ''; ?>" placeholder="Leave blank for auto-generation">
                </div>
                
                <div style="margin-top:2.5rem;">
                    <button type="submit" class="btn btn-primary" style="width:100%; padding:1rem;">
                        <i class="fas fa-shield-alt" style="margin-right:10px;"></i>
                        <?php echo $editCat ? 'Authorize Re-calibration' : 'Authorize Deployment'; ?>
                    </button>
                    <?php if ($editCat): ?>
                        <a href="<?php echo SITE_URL; ?>/admin/categories" class="btn btn-outline" style="width:100%; margin-top:1rem; border:1px solid var(--color-gray-100); color:var(--color-gray-400);">De-authorize Operation</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Registry Archive: Active Categories -->
    <div>
        <div class="admin-card">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                <h2 style="font-size:1.1rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Category Registry</h2>
            </div>
            
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Strategic Identifier</th>
                            <th>Identity Label</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td><code style="background:var(--color-gray-50); padding:3px 8px; color:var(--color-accent); font-size:0.8rem;"><?php echo $cat['slug']; ?></code></td>
                            <td style="font-weight:700; color:var(--color-primary);"><?php echo $cat['name']; ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="<?php echo SITE_URL; ?>/admin/categories?edit=<?php echo $cat['id']; ?>" class="edit-btn"><i class="fas fa-cog"></i></a>
                                    <a href="<?php echo SITE_URL; ?>/admin/categories?delete=<?php echo $cat['id']; ?>" class="delete-btn" onclick="return confirm('Permanently decommission this strategic category?')"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($categories)): ?>
                            <tr><td colspan="3" style="text-align:center; padding:3rem; color:var(--color-gray-300);">Intelligence Archive is Currently Vacant.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
