<?php
/**
 * Digital Practice - Admin Team CRUD
 */
$admin_title = 'Manage Team';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM team_members WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Team member removed successfully.');
    redirect(SITE_URL . '/admin/team');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $pdo->prepare('UPDATE team_members SET is_active = NOT is_active WHERE id = ?')->execute([$_GET['toggle']]);
    setFlash('success', 'Member status updated.');
    redirect(SITE_URL . '/admin/team');
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = sanitize($_POST['name'] ?? '');
    $position = sanitize($_POST['position'] ?? '');
    $category = $_POST['category'] ?? 'team';
    $bio = $_POST['bio'] ?? '';
    $linkedin = sanitize($_POST['linkedin'] ?? '');
    $twitter = sanitize($_POST['twitter'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    
    // Handle photo upload
    $photo = $_POST['current_photo'] ?? null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = uploadImage($_FILES['photo'], 'team');
    }

    if ($name && $position) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE team_members SET name=?, position=?, category=?, bio=?, photo=?, linkedin=?, twitter=?, sort_order=? WHERE id=?');
            $stmt->execute([$name, $position, $category, $bio, $photo, $linkedin, $twitter, $sort_order, $id]);
            setFlash('success', 'Team member updated successfully.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO team_members (name, position, category, bio, photo, linkedin, twitter, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $position, $category, $bio, $photo, $linkedin, $twitter, $sort_order]);
            setFlash('success', 'Team member added successfully.');
        }
        redirect(SITE_URL . '/admin/team');
    } else {
        setFlash('error', 'Name and Position are required.');
    }
}

// Fetch edit data
$editMember = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM team_members WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editMember = $stmt->fetch();
}

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE name LIKE ? OR position LIKE ? OR bio LIKE ? OR category LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM team_members $where_clause");
$stmt_count->execute($params);
$total_members = $stmt_count->fetchColumn();
$total_pages = ceil($total_members / $limit);

// Fetch all members (Paginated)
$stmt = $pdo->prepare("SELECT * FROM team_members $where_clause ORDER BY category, sort_order ASC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$members = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Personnel Registry</h1>
</div>

<?php renderFlash(); ?>

<!-- Create/Edit Form: Elite Precision -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
            <?php echo $editMember ? 'Update Personnel Profile' : 'Enlist New Member'; ?>
        </h2>
    </div>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($editMember): ?>
            <input type="hidden" name="id" value="<?php echo $editMember['id']; ?>">
            <input type="hidden" name="current_photo" value="<?php echo $editMember['photo']; ?>">
        <?php endif; ?>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="name">Legal Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $editMember ? sanitize($editMember['name']) : ''; ?>" required placeholder="e.g. Dr. Olumide Roberts">
            </div>
            <div class="form-group">
                <label for="position">Corporate Designation *</label>
                <input type="text" id="position" name="position" class="form-control" value="<?php echo $editMember ? sanitize($editMember['position']) : ''; ?>" required placeholder="e.g. Executive Director, Strategy">
            </div>
        </div>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="category">Organizational Tier</label>
                <select id="category" name="category" class="form-control">
                    <option value="board" <?php echo ($editMember && $editMember['category'] === 'board') ? 'selected' : ''; ?>>Board of Directors</option>
                    <option value="executive" <?php echo ($editMember && $editMember['category'] === 'executive') ? 'selected' : ''; ?>>Executive Leadership</option>
                    <option value="team" <?php echo (!$editMember || $editMember['category'] === 'team') ? 'selected' : ''; ?>>Management & Operations</option>
                </select>
            </div>
            <div class="form-group">
                <label for="photo">Professional Headshot</label>
                <div style="display:flex; gap:1.5rem; align-items:center;">
                    <?php if ($editMember && $editMember['photo']): ?>
                        <div style="width:50px; height:50px; border:1px solid var(--color-gray-200);">
                            <img src="<?php echo SITE_URL; ?>/assets/images/team/<?php echo $editMember['photo']; ?>" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <div class="grid-2" style="gap: 2rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label for="linkedin">LinkedIn Professional Profile</label>
                <input type="text" id="linkedin" name="linkedin" class="form-control" value="<?php echo $editMember ? sanitize($editMember['linkedin']) : ''; ?>" placeholder="https://linkedin.com/in/...">
            </div>
            <div class="form-group">
                <label for="sort_order">Display Priority (Sort)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $editMember ? $editMember['sort_order'] : 0; ?>" min="0">
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Executive Narrative / Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="4" placeholder="Brief professional background and core competencies..."><?php echo $editMember ? sanitize($editMember['bio']) : ''; ?></textarea>
        </div>

        <div style="display: flex; gap: 1.5rem; margin-top: 1.5rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1.2rem 3rem;">
                <i class="fas fa-save" style="margin-right:10px;"></i> Commit Changes
            </button>
            <?php if ($editMember): ?>
                <a href="<?php echo SITE_URL; ?>/admin/team" class="btn btn-outline" style="border:1px solid var(--color-gray-200); color:var(--color-gray-500);">Cancel Operation</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Team Table: Elite Data Grid -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Personnel Database</h2>
        
        <!-- Tactical Search Interface -->
        <form method="GET" action="" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
            <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search Personnel..." style="padding: 0.6rem 1rem; border: none; font-size: 0.85rem; outline: none; width: 250px;">
            <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1rem; cursor: pointer;"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if (!empty($search)): ?>
        <div style="margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--color-gray-400); font-weight: 700;">
            DATABASE FILTERED BY: <span style="color:var(--color-accent);">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_members; ?> Matched)
            <a href="team.php" style="margin-left: 10px; color: var(--color-primary); text-decoration: underline;">Reset Filters</a>
        </div>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Portrait</th>
                    <th>Full Name</th>
                    <th>Position / Designation</th>
                    <th>Organizational Tier</th>
                    <th>Status</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m): ?>
                <tr>
                    <td>
                        <div style="width:48px; height:48px; background:var(--color-gray-50); border:1px solid var(--color-gray-200); position:relative; overflow:hidden;">
                            <?php if ($m['photo']): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/team/<?php echo $m['photo']; ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:var(--color-gray-300);">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><strong><?php echo sanitize($m['name']); ?></strong></td>
                    <td style="color:var(--color-gray-600);"><?php echo sanitize($m['position']); ?></td>
                    <td><span class="badge badge-inactive"><?php echo strtoupper($m['category']); ?></span></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/team?toggle=<?php echo $m['id']; ?>" style="text-decoration:none;">
                            <?php if ($m['is_active']): ?>
                                <span class="badge badge-active">Active</span>
                            <?php else: ?>
                                <span class="badge badge-inactive" style="opacity:0.5;">Hidden</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo SITE_URL; ?>/admin/team?edit=<?php echo $m['id']; ?>" class="edit-btn" title="Edit Profile"><i class="fas fa-pen-nib"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/team?delete=<?php echo $m['id']; ?>" class="delete-btn" title="Remove Entry" onclick="return confirm('Permanently remove this entry?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Personnel Pagination -->
    <?php 
        $base_url = SITE_URL . '/admin/team';
        if (!empty($search)) $base_url .= '?s=' . urlencode($search);
        echo renderPagination($page, $total_pages, $base_url); 
    ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
