<?php
/**
 * Digital Practice - Admin Blog CRUD (Rich Text with QuillJS)
 */
$admin_title = 'Manage Blog & Insights';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$_GET['delete']]);
    setFlash('success', 'Blog post deleted.');
    redirect(SITE_URL . '/admin/blog');
}

// Handle toggle publish
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $pdo->prepare('UPDATE blog_posts SET is_published = NOT is_published, published_at = IF(is_published, NOW(), published_at) WHERE id = ?')->execute([$_GET['toggle']]);
    setFlash('success', 'Publication status updated.');
    redirect(SITE_URL . '/admin/blog');
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = sanitize($_POST['title'] ?? '');
    $excerpt = sanitize($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? ''; // From QuillJS hidden input
    $category = $_POST['category'] ?? 'General';
    $slug = generateSlug($title);
    
    // Handle image upload
    $featured_image = $_POST['current_image'] ?? null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $featured_image = uploadImage($_FILES['featured_image'], 'blog');
    }

    if ($title && $content) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE blog_posts SET title=?, slug=?, excerpt=?, content=?, featured_image=?, category=? WHERE id=?');
            $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category, $id]);
            setFlash('success', 'Post updated successfully.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, category, author_id, is_published, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())');
            $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category, $_SESSION['admin_id']]);
            setFlash('success', 'Post published successfully.');
        }
        redirect(SITE_URL . '/admin/blog');
    } else {
        setFlash('error', 'Title and Content are required.');
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
    $where_clause = "WHERE title LIKE ? OR excerpt LIKE ? OR category LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM blog_posts $where_clause");
$stmt_count->execute($params);
$total_posts = $stmt_count->fetchColumn();
$total_pages = ceil($total_posts / $limit);

// Fetch paginated
$stmt = $pdo->prepare("SELECT * FROM blog_posts $where_clause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Fetch Categories for dropdown
$categories_registry = getBlogCategories();

// Fetch edit data
$editPost = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editPost = $stmt->fetch();
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Insights & Intelligence</h1>
    <?php if ($editPost): ?>
        <a href="<?php echo SITE_URL; ?>/admin/blog" class="btn btn-outline" style="border:1px solid var(--color-gray-200); color:var(--color-gray-600);">Enlist New Perspective</a>
    <?php endif; ?>
</div>

<?php renderFlash(); ?>

<!-- Editor Form: Elite Precision Content Engine -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
            <?php echo $editPost ? 'Update Published Insight' : 'Draft New Intelligence Post'; ?>
        </h2>
    </div>
    
    <form id="blog-form" method="POST" action="" enctype="multipart/form-data">
        <?php if ($editPost): ?>
            <input type="hidden" name="id" value="<?php echo $editPost['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo $editPost['featured_image']; ?>">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem;">
            <!-- Main Content Area -->
            <div>
                <div class="form-group">
                    <label for="title">Intelligence Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo $editPost ? sanitize($editPost['title']) : ''; ?>" required placeholder="Enter a high-impact headline">
                </div>

                <div class="form-group">
                    <label>In-depth Narrative Content *</label>
                    <!-- Quill Toolbar Container -->
                    <div id="editor-container" style="height: 500px; background: white; border: 1px solid var(--color-gray-200); font-family: var(--font-body);">
                        <?php echo $editPost ? $editPost['content'] : ''; ?>
                    </div>
                    <!-- Hidden input to store Quill HTML -->
                    <input type="hidden" name="content" id="quill-content">
                </div>

                <div class="form-group" style="margin-top: 4rem;">
                    <label for="excerpt">Executive Summary (SEO Excerpt)</label>
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="3" placeholder="Condensed summary for archival and search discovery..."><?php echo $editPost ? sanitize($editPost['excerpt']) : ''; ?></textarea>
                </div>
            </div>

            <!-- Management Sidebar -->
            <div>
                <div class="form-group">
                    <label for="category">Insight Classification</label>
                    <select id="category" name="category" class="form-control">
                        <option value="General" <?php echo ($editPost && $editPost['category'] === 'General') ? 'selected' : ''; ?>>General Intelligence</option>
                        <?php foreach($categories_registry as $cat): ?>
                            <option value="<?php echo sanitize($cat['name']); ?>" <?php echo ($editPost && $editPost['category'] === $cat['name']) ? 'selected' : ''; ?>>
                                <?php echo sanitize($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="featured_image">Primary Brand Imagery</label>
                    <div style="border:1px solid var(--color-gray-200); padding:2rem; background:var(--color-off-white); text-align:center;">
                        <?php if ($editPost && $editPost['featured_image']): ?>
                            <div style="margin-bottom:1.5rem; border:1px solid var(--color-gray-200); line-height:0;">
                                <img src="<?php echo SITE_URL; ?>/assets/images/blog/<?php echo $editPost['featured_image']; ?>" style="max-width:100%; height:auto;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                        <small style="color:var(--color-gray-400); margin-top:1rem; display:block; font-size:0.7rem; text-transform:uppercase; letter-spacing:1px; font-weight:700;">Optimal Dimensions: 1.91:1 ratio</small>
                    </div>
                </div>

                <div style="margin-top: 4rem; border-top: 1px solid var(--color-gray-50); padding-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width:100%; padding: 1.5rem;">
                        <i class="fas fa-paper-plane" style="margin-right:10px;"></i> <?php echo $editPost ? 'Execute Update' : 'Authorize Publication'; ?>
                    </button>
                    <?php if ($editPost): ?>
                        <a href="<?php echo SITE_URL; ?>/admin/blog" class="btn btn-outline" style="width:100%; border:1px solid var(--color-gray-200); color:var(--color-gray-500); margin-top: 1rem; text-align:center; display:block; text-decoration:none;">Abort Changes</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Published Archives: Elite Data Grid -->
<div class="admin-card">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Intelligence Archive</h2>
        
        <!-- Tactical Search Interface -->
        <form method="GET" action="" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
            <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Filter Archive..." style="padding: 0.6rem 1rem; border: none; font-size: 0.85rem; outline: none; width: 250px;">
            <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1rem; cursor: pointer;"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if (!empty($search)): ?>
        <div style="margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--color-gray-400); font-weight: 700;">
            ARCHIVE FILTERED BY: <span style="color:var(--color-accent);">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_posts; ?> Matched)
            <a href="blog.php" style="margin-left: 10px; color: var(--color-primary); text-decoration: underline;">Reset Filters</a>
        </div>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Lead Asset</th>
                    <th>Intelligence Narrative</th>
                    <th>Classification</th>
                    <th>Engagement Metrics</th>
                    <th>Status</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td>
                        <div style="width:100px; height:56px; background:var(--color-gray-50); border:1px solid var(--color-gray-200); position:relative; overflow:hidden;">
                            <?php if ($post['featured_image']): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/blog/<?php echo $post['featured_image']; ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:var(--color-gray-300);"><i class="fas fa-file-alt"></i></div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:700; color:var(--color-primary); line-height:1.4;"><?php echo sanitize($post['title']); ?></div>
                        <div style="font-size:0.75rem; color:var(--color-gray-400); margin-top:4px;">Published <?php echo timeAgo($post['created_at']); ?></div>
                    </td>
                    <td><span class="badge badge-inactive"><?php echo strtoupper(str_replace('_', ' ', $post['category'])); ?></span></td>
                    <td><span style="font-weight:800; color:var(--color-accent); font-size:0.85rem;"><?php echo number_format($post['views']); ?></span> <span style="font-size:0.7rem; color:var(--color-gray-400); text-transform:uppercase; font-weight:700;">Impressions</span></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>/admin/blog?toggle=<?php echo $post['id']; ?>" style="text-decoration:none;">
                            <?php if ($post['is_published']): ?>
                                <span class="badge badge-active">Active</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Staged / Draft</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo SITE_URL; ?>/admin/blog?edit=<?php echo $post['id']; ?>" class="edit-btn"><i class="fas fa-pen-nib"></i></a>
                            <a href="<?php echo SITE_URL; ?>/admin/blog?delete=<?php echo $post['id']; ?>" class="delete-btn" onclick="return confirm('Permanently decommission this post?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Archival Pagination -->
    <?php 
        $base_url = SITE_URL . '/admin/blog';
        if (!empty($search)) $base_url .= '?s=' . urlencode($search);
        echo renderPagination($page, $total_pages, $base_url); 
    ?>
</div>

<!-- QuillJS Core JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Compose your deep-dive insights...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    var form = document.getElementById('blog-form');
    form.onsubmit = function() {
        // Sync quill html back to the hidden input
        var content = document.querySelector('#quill-content');
        content.value = quill.root.innerHTML;
        
        if (quill.getText().trim().length === 0 && quill.root.innerHTML.indexOf('<img') === -1) {
            alert('Content cannot be empty.');
            return false;
        }
        return true;
    };
});
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
