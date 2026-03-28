<?php
/**
 * Digital Practice - Admin Partners CRUD
 */
$admin_title = 'Manage Partners';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM partners WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Partner removed successfully.');
    redirect(SITE_URL . '/admin/partners');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $pdo->prepare('UPDATE partners SET is_active = NOT is_active WHERE id = ?')->execute([$_GET['toggle']]);
    setFlash('success', 'Partner status updated.');
    redirect(SITE_URL . '/admin/partners');
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = sanitize($_POST['name'] ?? '');
    $url = sanitize($_POST['url'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    
    // Handle logo upload
    $logo = $_POST['current_logo'] ?? null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = uploadImage($_FILES['logo'], 'partners');
    }

    if ($name) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE partners SET name=?, url=?, logo=?, sort_order=? WHERE id=?');
            $stmt->execute([$name, $url, $logo, $sort_order, $id]);
            setFlash('success', 'Partner updated successfully.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO partners (name, url, logo, sort_order) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $url, $logo, $sort_order]);
            setFlash('success', 'Partner added successfully.');
        }
        redirect(SITE_URL . '/admin/partners');
    } else {
        setFlash('error', 'Partner name is required.');
    }
}

// Fetch edit data
$editPartner = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM partners WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editPartner = $stmt->fetch();
}

// Fetch all partners
$partners = $pdo->query('SELECT * FROM partners ORDER BY sort_order ASC')->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<<div class="admin-header">
    <h1>Strategic Partnerships</h1>
</div>

<?php renderFlash(); ?>

<!-- Registry Deployment: Strategic Enrollment -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
            <?php echo $editPartner ? 'Modify Strategic Alliance' : 'Enroll New Global Partner'; ?>
        </h2>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($editPartner): ?>
            <input type="hidden" name="id" value="<?php echo $editPartner['id']; ?>">
            <input type="hidden" name="current_logo" value="<?php echo $editPartner['logo']; ?>">
        <?php endif; ?>

        <div class="grid-2" style="gap: 2.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="name">Corporate Legal Entity *</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $editPartner ? sanitize($editPartner['name']) : ''; ?>" required placeholder="e.g. Heirs Technologies">
            </div>

            <div class="form-group">
                <label for="url">Official Global URL</label>
                <input type="text" id="url" name="url" class="form-control" value="<?php echo $editPartner ? sanitize($editPartner['url']) : ''; ?>" placeholder="https://heirstechnologies.com">
            </div>
        </div>

        <div class="grid-2" style="gap: 2.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="logo">Corporate Brand Asset (Logo)</label>
                <div style="display:flex; gap:2rem; align-items:center;">
                    <?php if ($editPartner && $editPartner['logo']): ?>
                        <div style="width:120px; height:60px; background:var(--color-off-white); border:1px solid var(--color-gray-100); padding:10px; display:flex; align-items:center; justify-content:center;">
                            <img src="<?php echo SITE_URL; ?>/assets/images/partners/<?php echo $editPartner['logo']; ?>" style="max-width:100%; max-height:100%;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="logo" name="logo" class="form-control" accept="image/*" style="flex:1;">
                </div>
            </div>

            <div class="form-group">
                <label for="sort_order">Registry Priority Index</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $editPartner ? $editPartner['sort_order'] : 0; ?>" min="0">
            </div>
        </div>

        <div style="display: flex; gap: 1.5rem; margin-top: 2.5rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1.2rem 3.5rem;">
                <i class="fas fa-shield-alt" style="margin-right:10px;"></i> <?php echo $editPartner ? 'Authorize Global Updates' : 'Authorize Strategic Alliance'; ?>
            </button>
            <?php if ($editPartner): ?>
                <a href="<?php echo SITE_URL; ?>/admin/partners" class="btn btn-outline" style="border:1px solid var(--color-gray-200); color:var(--color-gray-500); display:flex; align-items:center; justify-content:center; padding:0 2.5rem;">De-authorize Operation</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Registry Archive: Active Strategic Partners -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Global Partnership Registry</h2>
    </div>
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Brand Asset</th>
                    <th>Legal Entity</th>
                    <th>Sub-URL</th>
                    <th>Status</th>
                    <th>Orchestration</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partners as $p): ?>
                <tr>
                    <td>
                        <div style="width:100px; height:50px; background:var(--color-off-white); border:1px solid var(--color-gray-50); display:flex; align-items:center; justify-content:center; padding:8px;">
                            <?php if ($p['logo']): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/partners/<?php echo $p['logo']; ?>" style="max-width:100%; max-height:100%; filter:grayscale(100%) opacity(0.8);">
                            <?php else: ?>
                                <span style="color:var(--color-gray-300); font-size:0.6rem; font-weight:900; text-transform:uppercase;">N/A</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:900; color:var(--color-primary);"><?php echo sanitize($p['name']); ?></div>
                        <div style="font-size:0.75rem; color:var(--color-gray-300); margin-top:4px;">Priority Index: L<?php echo $p['sort_order']; ?></div>
                    </td>
                    <td><code style="background:var(--color-gray-50); padding:2px 8px; color:var(--color-accent); font-size:0.8rem; border:1px solid var(--color-gray-100);"><?php echo truncateText($p['url'], 30); ?></code></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/partners?toggle=<?php echo $p['id']; ?>" style="text-decoration:none;">
                            <?php if ($p['is_active']): ?>
                                <span class="badge badge-active">Operational</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Paused / Inactive</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo SITE_URL; ?>/admin/partners?edit=<?php echo $p['id']; ?>" class="edit-btn" title="Edit Entity"><i class="fas fa-cog"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/partners?delete=<?php echo $p['id']; ?>" class="delete-btn" title="Remove Alliance" onclick="return confirm('Permanently remove this strategic partner from the registry?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
