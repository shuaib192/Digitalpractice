<?php
/**
 * Digital Practice - Admin Services CRUD
 */
$admin_title = 'Manage Services';

// Handle actions before header output
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM services WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Service deleted successfully.');
    redirect(SITE_URL . '/admin/services');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $pdo->prepare('UPDATE services SET is_active = NOT is_active WHERE id = ?')->execute([$_GET['toggle']]);
    setFlash('success', 'Service status updated.');
    redirect(SITE_URL . '/admin/services');
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = sanitize($_POST['title'] ?? '');
    $short_desc = sanitize($_POST['short_desc'] ?? '');
    $full_desc = sanitize($_POST['full_desc'] ?? '');
    $icon = sanitize($_POST['icon'] ?? 'fas fa-cogs');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $slug = generateSlug($title);

    if ($title && $short_desc) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE services SET title=?, slug=?, short_desc=?, full_desc=?, icon=?, sort_order=? WHERE id=?');
            $stmt->execute([$title, $slug, $short_desc, $full_desc, $icon, $sort_order, $id]);
            setFlash('success', 'Service updated successfully.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO services (title, slug, short_desc, full_desc, icon, sort_order) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $short_desc, $full_desc, $icon, $sort_order]);
            setFlash('success', 'Service created successfully.');
        }
        redirect(SITE_URL . '/admin/services');
    } else {
        setFlash('error', 'Title and Short Description are required.');
    }
}

// Fetch edit data
$editService = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editService = $stmt->fetch();
}

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE title LIKE ? OR short_desc LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM services $where_clause");
$stmt_count->execute($params);
$total_services = $stmt_count->fetchColumn();
$total_pages = ceil($total_services / $limit);

// Fetch all services (Paginated)
$stmt = $pdo->prepare("SELECT * FROM services $where_clause ORDER BY sort_order ASC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$services = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Service Portfolio</h1>
</div>

<?php renderFlash(); ?>

<!-- Create/Edit Form: Elite Precision Provisioning -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
            <?php echo $editService ? 'Update Service Definition' : 'Provision New Service Capability'; ?>
        </h2>
    </div>
    
    <form method="POST" action="">
        <?php if ($editService): ?>
            <input type="hidden" name="id" value="<?php echo $editService['id']; ?>">
        <?php endif; ?>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="title">Service Designation *</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo $editService ? sanitize($editService['title']) : ''; ?>" required placeholder="e.g. Artificial Intelligence Mastery">
            </div>
            <div class="form-group">
                <label for="icon">Architectural Icon Class (Font Awesome)</label>
                <input type="text" id="icon" name="icon" class="form-control" value="<?php echo $editService ? sanitize($editService['icon']) : 'fas fa-cogs'; ?>" placeholder="fas fa-brain">
            </div>
        </div>

        <div class="form-group">
            <label for="short_desc">Executive One-Liner (Short Description) *</label>
            <input type="text" id="short_desc" name="short_desc" class="form-control" value="<?php echo $editService ? sanitize($editService['short_desc']) : ''; ?>" required placeholder="A high-impact summary of this capability">
        </div>

        <div class="form-group">
            <label for="full_desc">In-depth Capability Narrative</label>
            <textarea id="full_desc" name="full_desc" class="form-control" rows="5" placeholder="Detailed description of the service's methodology and value proposition..."><?php echo $editService ? sanitize($editService['full_desc']) : ''; ?></textarea>
        </div>

        <div class="grid-2" style="gap: 2rem; align-items: flex-end; margin-top: 1rem;">
            <div class="form-group">
                <label for="sort_order">Registry Priority (Sort Order)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $editService ? $editService['sort_order'] : 0; ?>" min="0">
            </div>
            <div style="display: flex; gap: 1.5rem; padding-bottom: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex:1; padding: 1.2rem;">
                    <i class="fas fa-save" style="margin-right:10px;"></i> Commit Specification
                </button>
                <?php if ($editService): ?>
                    <a href="<?php echo SITE_URL; ?>/admin/services" class="btn btn-outline" style="border:1px solid var(--color-gray-200); color:var(--color-gray-500); display:flex; align-items:center; justify-content:center; padding: 0 2rem;">Cancel</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Services Table: Elite Portfolio Grid -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Active Service Registry</h2>
        
        <!-- Tactical Search Interface -->
        <form method="GET" action="" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
            <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search Services..." style="padding: 0.6rem 1rem; border: none; font-size: 0.85rem; outline: none; width: 250px;">
            <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1rem; cursor: pointer;"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if (!empty($search)): ?>
        <div style="margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--color-gray-400); font-weight: 700;">
            PORTFOLIO FILTERED BY: <span style="color:var(--color-accent);">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_services; ?> Matched)
            <a href="services.php" style="margin-left: 10px; color: var(--color-primary); text-decoration: underline;">Reset Filters</a>
        </div>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Priority</th>
                    <th>Identifier</th>
                    <th>Service Capability</th>
                    <th>Executive Summary</th>
                    <th>Status</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                <tr>
                    <td><span style="font-weight:900; color:var(--color-gray-200); font-size:1.2rem;">#<?php echo $s['sort_order']; ?></span></td>
                    <td>
                        <div style="width:40px; height:40px; background:var(--color-off-white); border:1px solid var(--color-gray-100); display:flex; align-items:center; justify-content:center; color:var(--color-accent); font-size:1.1rem;">
                            <i class="<?php echo sanitize($s['icon']); ?>"></i>
                        </div>
                    </td>
                    <td><strong style="color:var(--color-primary);"><?php echo sanitize($s['title']); ?></strong></td>
                    <td style="color:var(--color-gray-500); font-size:0.85rem; max-width:300px;"><?php echo truncateText(sanitize($s['short_desc']), 80); ?></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/services?toggle=<?php echo $s['id']; ?>" style="text-decoration:none;">
                            <?php if ($s['is_active']): ?>
                                <span class="badge badge-active">Operational</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Decommissioned</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo SITE_URL; ?>/admin/services?edit=<?php echo $s['id']; ?>" class="edit-btn" title="Edit Specification"><i class="fas fa-sliders-h"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/services?delete=<?php echo $s['id']; ?>" class="delete-btn" title="Delete Permanent" onclick="return confirm('Permanently decommission this service?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Portfolio Pagination -->
    <?php 
        $base_url = SITE_URL . '/admin/services';
        if (!empty($search)) $base_url .= '?s=' . urlencode($search);
        echo renderPagination($page, $total_pages, $base_url); 
    ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
