<?php
/**
 * Digital Practice - Corporate Blog & Thought Leadership
 */
$page_title = 'The Digital Journal';
$page_description = 'Elite perspectives on African enterprise technology, strategic engineering, and digital transformation.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Fetch published posts with dynamic filtering
$posts = [];
$total_posts = 0;
if (isset($pdo)) {
    try {
        $params = [1];
        $where_clause = "WHERE b.is_published = ?";
        
        if (!empty($search)) {
            $where_clause .= " AND (b.title LIKE ? OR b.content LIKE ? OR b.category LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        // Count total for pagination
        $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM blog_posts b $where_clause");
        $stmt_count->execute($params);
        $total_posts = $stmt_count->fetchColumn();
        $total_pages = ceil($total_posts / $limit);

        // Fetch paginated results
        $stmt = $pdo->prepare("SELECT b.*, a.full_name as author_name 
                             FROM blog_posts b 
                             LEFT JOIN admins a ON b.author_id = a.id 
                             $where_clause
                             ORDER BY b.published_at DESC 
                             LIMIT $limit OFFSET $offset");
        $stmt->execute($params);
        $posts = $stmt->fetchAll();
    } catch(Exception $e) {
        error_log("Blog Intel Fetch Error: " . $e->getMessage());
    }
}

include __DIR__ . '/includes/header.php';
?>

<style>
    @media (max-width: 768px) {
        .blog-hero-title { font-size: 2.5rem !important; }
        .blog-section { padding: 4rem 0 !important; }
        .subscribe-form { flex-direction: column; gap: 1rem; }
        .subscribe-form input { border-right: 1px solid var(--color-gray-200) !important; }
        .subscribe-form button { padding: 1.2rem !important; width: 100%; }
    }
</style>

<!-- Elite Page Header: The Digital Journal -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 6rem) 0 5rem;">
    <div class="container text-center animate-on-scroll">
        <span style="color: var(--color-accent); font-weight: 800; text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; display: block; margin-bottom: 1.5rem;">GLOBAL INTELLIGENCE</span>
        <h1 class="blog-hero-title" style="color: var(--color-white); font-size: 4rem; margin-bottom: 1.5rem; line-height: 1.1;">The Digital <br>Journal.</h1>
        <p style="color: rgba(255,255,255,0.6); max-width: 650px; margin: 0 auto; font-size: 1.25rem;">Strategic insights, architectural blueprints, and executive reports on the future of African enterprise.</p>
    </div>
</section>

<!-- Blog Ecosystem -->
<section class="section blog-section" style="padding: 6rem 0; background: var(--color-white);">
    <div class="container">
        
        <!-- Search Intelligence Bar -->
        <div style="margin-bottom: 4rem; display: flex; justify-content: flex-end;">
            <form method="GET" action="" style="display: flex; width: 100%; max-width: 400px; border: 1px solid var(--color-gray-100);">
                <input type="text" name="s" value="<?php echo $search; ?>" placeholder="Search Intelligence..." style="flex: 1; padding: 1rem; border: none; font-family: var(--font-body); outline: none;">
                <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1.5rem; cursor: pointer;"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if (!empty($search)): ?>
            <div style="margin-bottom: 2rem; color: var(--color-gray-400); font-weight: 700;">
                RESULTS FOR OPERATIONAL TERM: <span style="color: var(--color-accent); text-transform: uppercase;">"<?php echo sanitize($search); ?>"</span> (<?php echo $total_posts; ?> Assets)
            </div>
        <?php endif; ?>
        
        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 6rem 0; border: 2px dashed var(--color-gray-50); background: var(--color-off-white);">
                <i class="fas fa-search" style="font-size: 3rem; color: var(--color-gray-100); margin-bottom: 2rem;"></i>
                <h2 style="font-size: 1.5rem; color: var(--color-primary); margin-bottom: 1rem;">No Intelligence Found</h2>
                <p style="color: var(--color-gray-400); font-size: 1.1rem;">The system was unable to locate artifacts matching your query. Re-calibrate and try a different search parameter.</p>
                <div style="margin-top: 3rem;">
                    <a href="<?php echo SITE_URL; ?>/blog" class="btn btn-outline" style="border: 1px solid var(--color-gray-200); color: var(--color-gray-500);">Reset Search Command</a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid-3" style="gap: 3.5rem;">
                <?php foreach ($posts as $post): ?>
                <article class="animate-on-scroll" style="display: flex; flex-direction: column; group">
                    <div style="height: 280px; background-color: var(--color-gray-50); position: relative; overflow: hidden; margin-bottom: 2rem; border-left: 1px solid var(--color-gray-100);">
                        <?php if (!empty($post['featured_image'])): ?>
                            <img src="<?php echo SITE_URL; ?>/assets/images/blog/<?php echo sanitize($post['featured_image']); ?>" alt="<?php echo sanitize($post['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                        <?php else: ?>
                            <div style="position: absolute; inset: 0; background: var(--color-primary); opacity: 0.05;"></div>
                            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: var(--color-accent); font-size: 2.5rem; opacity: 0.3;">
                                <i class="fas fa-microchip"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div style="position: absolute; bottom: 0; left: 0; background: var(--color-accent); color: white; padding: 0.5rem 1.2rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">
                            <?php echo sanitize(str_replace('_', ' ', $post['category'])); ?>
                        </div>
                    </div>
                    
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <h3 style="font-size: 1.6rem; color: var(--color-primary); margin-bottom: 1.2rem; line-height: 1.3; font-weight: 800;">
                            <a href="<?php echo SITE_URL; ?>/insights/<?php echo $post['slug']; ?>" style="text-decoration: none; color: inherit;"><?php echo sanitize($post['title']); ?></a>
                        </h3>
                        <p style="color: var(--color-gray-500); line-height: 1.8; margin-bottom: 2.5rem; flex: 1; font-size: 1rem;">
                            <?php echo sanitize($post['excerpt'] ?: (strlen(strip_tags($post['content'])) > 140 ? substr(strip_tags($post['content']), 0, 140) . '...' : strip_tags($post['content']))); ?>
                        </p>
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--color-gray-50); padding-top: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; background: var(--color-accent); color: white; border-radius: 0; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 900;">
                                    <?php echo strtoupper(substr($post['author_name'], 0, 1)); ?>
                                </div>
                                <span style="font-size: 0.75rem; color: var(--color-gray-400); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo date('M d, Y', strtotime($post['published_at'] ?: $post['created_at'])); ?></span>
                            </div>
                            <a href="<?php echo SITE_URL; ?>/insights/<?php echo $post['slug']; ?>" style="font-weight: 800; color: var(--color-accent); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-bottom: 2px solid transparent; transition: border 0.3s;" onmouseover="this.style.borderColor='var(--color-accent)';" onmouseout="this.style.borderColor='transparent';">Details <i class="fas fa-chevron-right" style="font-size:0.6rem; margin-left:5px;"></i></a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Global Pagination Recalibration -->
            <?php 
                $base_url = SITE_URL . '/blog';
                if (!empty($search)) $base_url .= '?s=' . urlencode($search);
                echo renderPagination($page, $total_pages, $base_url); 
            ?>
        <?php endif; ?>
        
    </div>
</section>

<!-- Subscription Nexus -->
<section class="section bg-light blog-section" style="background-color: var(--color-gray-50); padding: 7rem 0;">
    <div class="container text-center animate-on-scroll">
        <h2 style="font-size: 2.5rem; color: var(--color-primary); margin-bottom: 1.5rem; font-weight: 900;">Join the Inner Circle.</h2>
        <p style="color: var(--color-gray-500); margin-bottom: 3rem; max-width: 550px; margin-left: auto; margin-right: auto; line-height: 1.7;">Receive the latest architectural blueprints and market signals directly in your executive inbox.</p>
        
        <form class="subscribe-form" style="max-width: 500px; margin: 0 auto; display: flex; gap: 0;">
            <input type="email" placeholder="Corporate Email Address" style="flex: 1; padding: 1.2rem; border: 1px solid var(--color-gray-200); font-family: var(--font-body); font-size: 1rem; outline: none;">
            <button type="submit" class="btn btn-primary" style="padding: 0 2.5rem; white-space: nowrap;">Authorize Access</button>
        </form>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
