<?php
/**
 * Digital Practice - Admin Products CRUD
 */
$admin_title = 'Manage Products';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Product deleted successfully.');
    redirect(SITE_URL . '/admin/products');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $pdo->prepare('UPDATE products SET is_active = NOT is_active WHERE id = ?')->execute([$_GET['toggle']]);
    setFlash('success', 'Product status updated.');
    redirect(SITE_URL . '/admin/products');
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = sanitize($_POST['name'] ?? '');
    $tagline = sanitize($_POST['tagline'] ?? '');
    $description = $_POST['description'] ?? '';
    $subdomain = sanitize($_POST['subdomain'] ?? '');
    $icon = sanitize($_POST['icon'] ?? 'fas fa-box');
    $url = sanitize($_POST['url'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $slug = generateSlug($name);

    if ($name) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE products SET name=?, slug=?, tagline=?, description=?, subdomain=?, icon=?, url=?, sort_order=? WHERE id=?');
            $stmt->execute([$name, $slug, $tagline, $description, $subdomain, $icon, $url, $sort_order, $id]);
            setFlash('success', 'Product updated successfully.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (name, slug, tagline, description, subdomain, icon, url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $slug, $tagline, $description, $subdomain, $icon, $url, $sort_order]);
            setFlash('success', 'Product created successfully.');
        }
        redirect(SITE_URL . '/admin/products');
    } else {
        setFlash('error', 'Product name is required.');
    }
}

// Fetch edit data
$editProduct = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE name LIKE ? OR tagline LIKE ? OR description LIKE ? OR subdomain LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM products $where_clause");
$stmt_count->execute($params);
$total_products = $stmt_count->fetchColumn();
$total_pages = ceil($total_products / $limit);

// Fetch all products (Paginated)
$stmt = $pdo->prepare("SELECT * FROM products $where_clause ORDER BY sort_order ASC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Technology Ecosystem</h1>
</div>

<?php renderFlash(); ?>

<!-- Create/Edit Form: Elite Precision Deployment -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
            <?php echo $editProduct ? 'Modify Product Specifications' : 'Deploy New Ecosystem Asset'; ?>
        </h2>
    </div>
    
    <form method="POST" action="">
        <?php if ($editProduct): ?>
            <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
        <?php endif; ?>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="name">Product Entity Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $editProduct ? sanitize($editProduct['name']) : ''; ?>" required placeholder="e.g. DP Intelligence Matrix">
            </div>
            <div class="form-group">
                <label for="tagline">Corporate Tagline / Value Prop</label>
                <input type="text" id="tagline" name="tagline" class="form-control" value="<?php echo $editProduct ? sanitize($editProduct['tagline']) : ''; ?>" placeholder="e.g. Next-generation analytical engine">
            </div>
        </div>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="subdomain">Strategic Subdomain</label>
                <input type="text" id="subdomain" name="subdomain" class="form-control" value="<?php echo $editProduct ? sanitize($editProduct['subdomain']) : ''; ?>" placeholder="e.g. matrix.digitalpractice.org">
            </div>
            <div class="form-group">
                <label for="url">Custom Global URL (Optional)</label>
                <input type="text" id="url" name="url" class="form-control" value="<?php echo $editProduct ? sanitize($editProduct['url']) : ''; ?>" placeholder="https://matrix-engine.com">
            </div>
        </div>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="icon">Architectural Icon Class (FontAwesome)</label>
                <input type="text" id="icon" name="icon" class="form-control" value="<?php echo $editProduct ? sanitize($editProduct['icon']) : 'fas fa-box'; ?>" placeholder="fas fa-microchip">
            </div>
            <div class="form-group">
                <label for="sort_order">Ecosystem Priority (Sort Order)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $editProduct ? $editProduct['sort_order'] : 0; ?>" min="0">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Detailed Asset Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Comprehensive description of the product's architectural capabilities and enterprise utility..."><?php echo $editProduct ? sanitize($editProduct['description']) : ''; ?></textarea>
        </div>

        <div style="display: flex; gap: 1.5rem; margin-top: 2rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1.2rem 3rem;">
                <i class="fas fa-rocket" style="margin-right:10px;"></i> Commit Deployment
            </button>
            <?php if ($editProduct): ?>
                <a href="<?php echo SITE_URL; ?>/admin/products" class="btn btn-outline" style="border:1px solid var(--color-gray-200); color:var(--color-gray-500); display:flex; align-items:center; justify-content:center; padding: 0 2rem;">Abort Operation</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Products Table: Elite Ecosystem Grid -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Authorized Ecosystem Assets</h2>
        
        <!-- Tactical Search Interface -->
        <form method="GET" action="" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
            <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search Technology..." style="padding: 0.6rem 1rem; border: none; font-size: 0.85rem; outline: none; width: 250px;">
            <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1rem; cursor: pointer;"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if (!empty($search)): ?>
        <div style="margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--color-gray-400); font-weight: 700;">
            ECOSYSTEM FILTERED BY: <span style="color:var(--color-accent);">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_products; ?> Matched)
            <a href="products.php" style="margin-left: 10px; color: var(--color-primary); text-decoration: underline;">Reset Filters</a>
        </div>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Asset</th>
                    <th>Identity & Narrative</th>
                    <th>Strategic Location</th>
                    <th>Status</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><span style="font-weight:900; color:var(--color-gray-200); font-size:1.2rem;">L<?php echo $p['sort_order']; ?></span></td>
                    <td>
                        <div style="width:40px; height:40px; background:var(--color-off-white); border:1px solid var(--color-gray-100); display:flex; align-items:center; justify-content:center; color:var(--color-accent); font-size:1.1rem;">
                            <i class="<?php echo sanitize($p['icon']); ?>"></i>
                        </div>
                    </td>
                    <td>
                        <strong style="color:var(--color-primary);"><?php echo sanitize($p['name']); ?></strong><br>
                        <small style="color:var(--color-gray-400); font-size:0.7rem; text-transform:uppercase; letter-spacing:0.5px;"><?php echo sanitize($p['tagline']); ?></small>
                    </td>
                    <td><code style="background:var(--color-gray-50); padding:2px 8px; color:var(--color-accent); font-size:0.8rem; border:1px solid var(--color-gray-100);"><?php echo sanitize($p['subdomain'] ?: $p['url']); ?></code></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/products?toggle=<?php echo $p['id']; ?>" style="text-decoration:none;">
                            <?php if ($p['is_active']): ?>
                                <span class="badge badge-active">Operational</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Offline / Staged</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo SITE_URL; ?>/admin/products?edit=<?php echo $p['id']; ?>" class="edit-btn" title="Modify Asset"><i class="fas fa-cog"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/products?delete=<?php echo $p['id']; ?>" class="delete-btn" title="Decommission" onclick="return confirm('Permanently decommission this product?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Ecosystem Pagination -->
    <?php 
        $base_url = SITE_URL . '/admin/products';
        if (!empty($search)) $base_url .= '?s=' . urlencode($search);
        echo renderPagination($page, $total_pages, $base_url); 
    ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
