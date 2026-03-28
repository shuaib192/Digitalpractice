<?php
/**
 * Digital Practice - Admin Contact Submission Management
 */
$admin_title = 'Contact Submissions';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle Mark as Read/Unread
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $pdo->prepare('UPDATE contact_submissions SET is_read = 1 WHERE id = ?')->execute([$_GET['mark_read']]);
    setFlash('success', 'Submission marked as read.');
    redirect(SITE_URL . '/admin/contact');
}
if (isset($_GET['mark_unread']) && is_numeric($_GET['mark_unread'])) {
    $pdo->prepare('UPDATE contact_submissions SET is_read = 0 WHERE id = ?')->execute([$_GET['mark_unread']]);
    setFlash('success', 'Submission marked as unread.');
    redirect(SITE_URL . '/admin/contact');
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM contact_submissions WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Submission deleted successfully.');
    redirect(SITE_URL . '/admin/contact');
}

// Fetch single submission details
$viewSub = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $stmt = $pdo->prepare('SELECT * FROM contact_submissions WHERE id = ?');
    $stmt->execute([$_GET['view']]);
    $viewSub = $stmt->fetch();
    
    // Automatically mark as read when viewed
    if ($viewSub && !$viewSub['is_read']) {
        $pdo->prepare('UPDATE contact_submissions SET is_read = 1 WHERE id = ?')->execute([$viewSub['id']]);
    }
}

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR company LIKE ? OR message LIKE ? OR service_interest LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM contact_submissions $where_clause");
$stmt_count->execute($params);
$total_subs = $stmt_count->fetchColumn();
$total_pages = ceil($total_subs / $limit);

// Fetch all submissions (Paginated)
$stmt = $pdo->prepare("SELECT * FROM contact_submissions $where_clause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$submissions = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $viewSub ? 'Inbound Strategic Inquiry' : 'Communications Inbox'; ?></h1>
</div>

<?php renderFlash(); ?>

<?php if ($viewSub): ?>
    <!-- Submission Details View: Elite Intel Presentation -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">
        <!-- Left Sidebar: Contact Dossier -->
        <div>
            <div class="admin-card">
                <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
                    <h2 style="font-size:1rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Strategic Dossier</h2>
                </div>
                
                <div style="margin-bottom:2rem;">
                    <label style="display:block; font-size:0.7rem; color:var(--color-gray-400); text-transform:uppercase; font-weight:800; letter-spacing:0.5px; margin-bottom:8px;">Legal Full Name</label>
                    <div style="font-size:1.25rem; font-weight:900; color:var(--color-primary);"><?php echo sanitize($viewSub['first_name'] . ' ' . $viewSub['last_name']); ?></div>
                </div>

                <div style="margin-bottom:2rem;">
                    <label style="display:block; font-size:0.7rem; color:var(--color-gray-400); text-transform:uppercase; font-weight:800; letter-spacing:0.5px; margin-bottom:8px;">Electronic Mail</label>
                    <a href="mailto:<?php echo sanitize($viewSub['email']); ?>" style="color:var(--color-accent); font-weight:700; font-size:1.05rem; text-decoration:none;"><?php echo sanitize($viewSub['email']); ?></a>
                </div>

                <div style="margin-bottom:2rem;">
                    <label style="display:block; font-size:0.7rem; color:var(--color-gray-400); text-transform:uppercase; font-weight:800; letter-spacing:0.5px; margin-bottom:8px;">Telephonic Link</label>
                    <div style="font-weight:700; color:var(--color-primary);"><?php echo sanitize($viewSub['phone'] ?: 'No Record'); ?></div>
                </div>

                <div style="margin-bottom:2rem;">
                    <label style="display:block; font-size:0.7rem; color:var(--color-gray-400); text-transform:uppercase; font-weight:800; letter-spacing:0.5px; margin-bottom:8px;">Corporate Affiliation</label>
                    <div style="font-weight:700; color:var(--color-primary);"><?php echo sanitize($viewSub['company'] ?: 'Independent'); ?> <span style="color:var(--color-gray-300); margin:0 5px;">|</span> <?php echo sanitize($viewSub['job_title'] ?: 'Not Specified'); ?></div>
                </div>

                <div style="margin-top:4rem; padding-top:2rem; border-top:1px solid var(--color-gray-50);">
                    <a href="<?php echo SITE_URL; ?>/admin/contact?delete=<?php echo $viewSub['id']; ?>" class="btn btn-outline" style="width:100%; text-align:center; color:#EF4444; border-color:#FEE2E2; display:block; margin-bottom:1rem; text-decoration:none;" onclick="return confirm('Permanently decommission this record?')"><i class="fas fa-trash-alt"></i> Purge Record</a>
                    <a href="<?php echo SITE_URL; ?>/admin/contact" class="btn btn-outline" style="width:100%; text-align:center; display:block; text-decoration:none; color:var(--color-gray-500);"><i class="fas fa-arrow-left"></i> Return to Inbox</a>
                </div>
            </div>
        </div>

        <!-- Right: Intel Narrative -->
        <div>
            <div class="admin-card" style="min-height: 100%;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:3rem; border-bottom:1px solid var(--color-gray-50); padding-bottom:2rem;">
                    <div>
                        <h2 style="font-size:1.2rem; font-weight:900; color:var(--color-primary); margin-bottom:0.8rem; text-transform:uppercase; letter-spacing:1px;">Subject: <?php echo sanitize($viewSub['service_interest'] ?: 'General Strategic Inquiry'); ?></h2>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="badge badge-active" style="background:var(--color-off-white); color:var(--color-primary); border:1px solid var(--color-gray-100);">Operational Log</span>
                            <span style="color:var(--color-gray-400); font-size:0.8rem; font-weight:600;"><?php echo date('D, M j, Y \a\t g:i A', strtotime($viewSub['created_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <div style="background:var(--color-off-white); border:1px solid var(--color-gray-100); padding:3.5rem; border-left:6px solid var(--color-accent); font-size:1.15rem; line-height:1.8; color:var(--color-primary); white-space:pre-wrap; font-family:var(--font-body);"><?php echo sanitize($viewSub['message']); ?></div>

                <div style="margin-top:5rem; padding-top:3rem; border-top:1px solid var(--color-gray-50);">
                    <h3 style="font-size:0.9rem; font-weight:850; text-transform:uppercase; letter-spacing:2px; color:var(--color-gray-400); margin-bottom:1.5rem;">Strategic Response Protocol</h3>
                    <p style="color:var(--color-gray-600); line-height:1.6; max-width:600px;">Internal advisory suggests immediate executive follow-up via corporate communication channels. Click the dossier link to initiate an external electronic mail draft.</p>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Inbox View: Elite Communications Registry -->
    <div class="admin-card">
        <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
            <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Active Inbound Intel Pool</h2>
            
            <!-- Tactical Search Interface -->
            <form method="GET" action="" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
                <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search Inbox..." style="padding: 0.6rem 1rem; border: none; font-size: 0.85rem; outline: none; width: 250px;">
                <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1rem; cursor: pointer;"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if (!empty($search)): ?>
            <div style="margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--color-gray-400); font-weight: 700;">
                INBOX FILTERED BY: <span style="color:var(--color-accent);">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_subs; ?> Matched)
                <a href="contact.php" style="margin-left: 10px; color: var(--color-primary); text-decoration: underline;">Reset Filters</a>
            </div>
        <?php endif; ?>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Temporal Log</th>
                        <th>Strategic Lead</th>
                        <th>Interest Vector</th>
                        <th>Corporate Entity</th>
                        <th>Classification</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($submissions)): ?>
                        <tr><td colspan="6" style="text-align:center; padding:5rem; color:var(--color-gray-300); text-transform:uppercase; letter-spacing:2px; font-weight:800;">No Strategic Inquiries Detected</td></tr>
                    <?php endif; ?>
                    <?php foreach ($submissions as $s): ?>
                    <tr style="<?php echo !$s['is_read'] ? 'background-color:rgba(0, 91, 234, 0.03);' : ''; ?>">
                        <td><strong><?php echo date('M j, Y', strtotime($s['created_at'])); ?></strong><br><small style="color:var(--color-gray-400);"><?php echo date('H:i', strtotime($s['created_at'])); ?></small></td>
                        <td>
                            <div style="font-weight:900; color:var(--color-primary);"><?php echo sanitize($s['first_name'] . ' ' . $s['last_name']); ?></div>
                            <div style="font-size:0.75rem; color:var(--color-accent); font-weight:700;"><?php echo sanitize($s['email']); ?></div>
                        </td>
                        <td><span style="font-weight:800; color:var(--color-primary); font-size:0.85rem;"><?php echo sanitize($s['service_interest'] ?: 'General'); ?></span></td>
                        <td><span style="color:var(--color-gray-500); font-weight:600;"><?php echo sanitize($s['company'] ?: 'Independent'); ?></span></td>
                        <td>
                            <?php if ($s['is_read']): ?>
                                <a href="<?php echo SITE_URL; ?>/admin/contact?mark_unread=<?php echo $s['id']; ?>" style="text-decoration:none;"><span class="badge badge-inactive" style="opacity:0.6;">Archived / Read</span></a>
                            <?php else: ?>
                                <span class="badge badge-active">Critical / New</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="<?php echo SITE_URL; ?>/admin/contact?view=<?php echo $s['id']; ?>" class="edit-btn" title="Open Narrative Dossier"><i class="fas fa-folder-open"></i></a>
                                <a href="<?php echo SITE_URL; ?>/admin/contact?delete=<?php echo $s['id']; ?>" class="delete-btn" title="Purge Record" onclick="return confirm('Permanently erase this inquiry?')"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Intel Pagination -->
        <?php 
            $base_url = SITE_URL . '/admin/contact';
            if (!empty($search)) $base_url .= '?s=' . urlencode($search);
            echo renderPagination($page, $total_pages, $base_url); 
        ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
