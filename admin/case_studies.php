<?php
/**
 * Digital Practice - Admin Case Studies CRUD
 */
$admin_title = 'Manage Case Studies';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle actions
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM case_studies WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Case study deleted.');
    redirect(SITE_URL . '/admin/case_studies');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = sanitize($_POST['title']);
    $slug = generateSlug($title);
    $client = sanitize($_POST['client_name']);
    $problem = $_POST['problem'];
    $solution = $_POST['solution'];
    $results = $_POST['results'];
    $stat_label = sanitize($_POST['stat_label']);
    $stat_value = sanitize($_POST['stat_value']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    // Image Upload
    $image = $_POST['current_image'] ?? null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadImage($_FILES['image'], 'cases');
    }

    if ($id) {
        $stmt = $pdo->prepare('UPDATE case_studies SET title=?, slug=?, client_name=?, problem=?, solution=?, results=?, stat_label=?, stat_value=?, image=?, is_published=? WHERE id=?');
        $stmt->execute([$title, $slug, $client, $problem, $solution, $results, $stat_label, $stat_value, $image, $is_published, $id]);
        setFlash('success', 'Case study updated.');
    } else {
        $stmt = $pdo->prepare('INSERT INTO case_studies (title, slug, client_name, problem, solution, results, stat_label, stat_value, image, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$title, $slug, $client, $problem, $solution, $results, $stat_label, $stat_value, $image, $is_published]);
        setFlash('success', 'Case study created.');
    }
    redirect(SITE_URL . '/admin/case_studies');
}

$editCase = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM case_studies WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editCase = $stmt->fetch();
}

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE title LIKE ? OR client_name LIKE ? OR problem LIKE ? OR solution LIKE ? OR results LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM case_studies $where_clause");
$stmt_count->execute($params);
$total_cases = $stmt_count->fetchColumn();
$total_pages = ceil($total_cases / $limit);

// Fetch all cases (Paginated)
$stmt = $pdo->prepare("SELECT * FROM case_studies $where_clause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$cases = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Strategic Triumph Documentation</h1>
</div>

<?php renderFlash(); ?>

<!-- Editor Form: Strategic Triumph Documentation -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
            <?php echo $editCase ? 'Update Case Narrative' : 'Document Strategic Triumph'; ?>
        </h2>
    </div>
    
    <form method="POST" enctype="multipart/form-data">
        <?php if ($editCase): ?>
            <input type="hidden" name="id" value="<?php echo $editCase['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo $editCase['image']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Case Study Intellectual Title *</label>
            <input type="text" name="title" class="form-control" value="<?php echo $editCase ? sanitize($editCase['title']) : ''; ?>" required placeholder="e.g. Scaling Enterprise AI for Global FinTech">
        </div>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label>Corporate Client Entity</label>
                <input type="text" name="client_name" class="form-control" value="<?php echo $editCase ? sanitize($editCase['client_name']) : ''; ?>" placeholder="e.g. Sterling Bank Global">
            </div>
            <div class="form-group">
                <label>Primary Case Imagery</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if ($editCase && $editCase['image']): ?>
                    <small style="color:var(--color-accent); font-size:0.7rem; font-weight:700;">Asset: <?php echo $editCase['image']; ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label>Strategic Metric Label (e.g. Operational Gain)</label>
                <input type="text" name="stat_label" class="form-control" value="<?php echo $editCase ? sanitize($editCase['stat_label']) : ''; ?>" placeholder="e.g. increase in productivity">
            </div>
            <div class="form-group">
                <label>Quantifiable Value (e.g. 85%)</label>
                <input type="text" name="stat_value" class="form-control" value="<?php echo $editCase ? sanitize($editCase['stat_value']) : ''; ?>" placeholder="e.g. 85%">
            </div>
        </div>

        <div class="form-group">
            <label>The Architectural Problem</label>
            <textarea name="problem" class="form-control" rows="3" placeholder="Contextualize the business challenge..."><?php echo $editCase ? sanitize($editCase['problem']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>The Orchestrated Solution</label>
            <textarea name="solution" class="form-control" rows="3" placeholder="Describe the implemented strategy and technology..."><?php echo $editCase ? sanitize($editCase['solution']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>The Measurable Results</label>
            <textarea name="results" class="form-control" rows="3" placeholder="Summarize the strategic outcomes and ROI..."><?php echo $editCase ? sanitize($editCase['results']) : ''; ?></textarea>
        </div>

        <div class="form-group" style="display:flex; align-items:center; gap:12px; margin: 2rem 0; padding:1.5rem; background:var(--color-off-white); border:1px solid var(--color-gray-100);">
            <div style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_published" id="pub" <?php echo (!$editCase || $editCase['is_published']) ? 'checked' : ''; ?> style="width:18px; height:18px; cursor:pointer;">
                <label for="pub" style="font-weight:700; color:var(--color-primary); margin:0; cursor:pointer;">Authorize for Public Presentation</label>
            </div>
        </div>

        <div style="display: flex; gap: 1.5rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1.2rem 3rem;">
                <i class="fas fa-save" style="margin-right:10px;"></i> Commit Narrative
            </button>
            <?php if ($editCase): ?>
                <a href="<?php echo SITE_URL; ?>/admin/case_studies" class="btn btn-outline" style="border:1px solid var(--color-gray-200); color:var(--color-gray-500); display:flex; align-items:center; justify-content:center; padding:0 2.5rem;">Cancel Operation</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Registry Grid: Elite Case Archive -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Strategic Triumph Registry</h2>
        
        <!-- Tactical Search Interface -->
        <form method="GET" action="" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
            <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search Triumphs..." style="padding: 0.6rem 1rem; border: none; font-size: 0.85rem; outline: none; width: 250px;">
            <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1rem; cursor: pointer;"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if (!empty($search)): ?>
        <div style="margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--color-gray-400); font-weight: 700;">
            ARCHIVE FILTERED BY: <span style="color:var(--color-accent);">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_cases; ?> Matched)
            <a href="case_studies.php" style="margin-left: 10px; color: var(--color-primary); text-decoration: underline;">Reset Filters</a>
        </div>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Case Identifier</th>
                    <th>Corporate Client</th>
                    <th>Measured Impact</th>
                    <th>Status</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cases as $c): ?>
                <tr>
                    <td>
                        <div style="font-weight:850; color:var(--color-primary); line-height:1.4;"><?php echo sanitize($c['title']); ?></div>
                        <div style="font-size:0.75rem; color:var(--color-gray-400); margin-top:4px;">Archived <?php echo date('M Y', strtotime($c['created_at'])); ?></div>
                    </td>
                    <td><strong><?php echo sanitize($c['client_name']); ?></strong></td>
                    <td>
                        <div style="display:flex; align-items:baseline; gap:6px;">
                            <span style="color:var(--color-accent); font-weight:900; font-size:1.1rem;"><?php echo sanitize($c['stat_value']); ?></span>
                            <span style="font-size:0.7rem; color:var(--color-gray-400); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;"><?php echo sanitize($c['stat_label']); ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?php echo $c['is_published'] ? 'badge-active' : 'badge-inactive'; ?>">
                            <?php echo $c['is_published'] ? 'Operational' : 'Draft / Private'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo SITE_URL; ?>/admin/case_studies?edit=<?php echo $c['id']; ?>" class="edit-btn" title="Edit Narrative"><i class="fas fa-edit"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/case_studies?delete=<?php echo $c['id']; ?>" class="delete-btn" title="Decommission" onclick="return confirm('Permanently erase this triumph from history?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Archival Pagination -->
    <?php 
        $base_url = SITE_URL . '/admin/case_studies';
        if (!empty($search)) $base_url .= '?s=' . urlencode($search);
        echo renderPagination($page, $total_pages, $base_url); 
    ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
