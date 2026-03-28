<?php
/**
 * Digital Practice - Digital Products Storefront
 */
$page_title = 'Digital Products';
$page_description = 'Discover Digital Practice\'s suite of enterprise-grade software products.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$products = [];
if (isset($pdo)) {
    try {
        $params = [1]; // is_active = 1
        $where_clause = "WHERE is_active = ?";
        if (!empty($search)) {
            $where_clause .= " AND (name LIKE ? OR tagline LIKE ? OR description LIKE ? OR subdomain LIKE ?)";
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

        // Fetch products (Paginated)
        $stmt = $pdo->prepare("SELECT * FROM products $where_clause ORDER BY sort_order ASC LIMIT $limit OFFSET $offset");
        $stmt->execute($params);
        $products = $stmt->fetchAll();
    } catch(Exception $e) {}
}

include __DIR__ . '/includes/header.php';
?>

<!-- Storefront Header -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 5rem) 0 4rem;">
    <div class="container text-center animate-on-scroll">
        <h1 style="color: var(--color-white); font-size: 3.5rem; margin-bottom: 1rem;">Digital Products.</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; font-size: 1.15rem; margin-bottom: 3rem;">Bespoke, enterprise-ready software platforms built to scale. Seamlessly integrated into your organizational workflow.</p>
        
        <!-- Ecosystem Search Interface -->
        <div style="max-width: 600px; margin: 0 auto; position: relative;">
            <form method="GET" action="" style="display: flex; background: white; padding: 5px;">
                <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Discover technology assets..." style="flex: 1; border: none; padding: 1rem 1.5rem; font-size: 1rem; outline: none; font-family: var(--font-body);">
                <button type="submit" class="btn btn-primary" style="padding: 0 2rem; border: none; background: var(--color-primary);">SEARCH</button>
            </form>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="section">
    <div class="container">
        
        <?php if (empty($products)): ?>
            <div style="text-align: center; padding: 4rem; background: var(--color-gray-50); border: 1px dashed var(--color-gray-300);">
                <p style="color: var(--color-gray-500); font-size: 1.1rem;">No digital products are currently available in the storefront. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="grid-3" style="gap: 2.5rem;">
                <?php foreach ($products as $prod): 
                    // Format the link
                    $link = '#';
                    $btn_text = 'Learn More';
                    if (!empty($prod['subdomain'])) {
                        // Ensure subdomain has a protocol for the href
                        $link = strpos($prod['subdomain'], 'http') === 0 ? sanitize($prod['subdomain']) : 'https://' . sanitize($prod['subdomain']);
                        $btn_text = 'Launch Platform';
                    } elseif (!empty($prod['url'])) {
                        $link = sanitize($prod['url']);
                    }
                ?>
                <div class="product-card card-360 animate-on-scroll" style="background: var(--color-white); border: 1px solid var(--color-gray-200); position: relative; display: flex; flex-direction: column;">
                    
                    <div style="height: 200px; background-color: var(--color-gray-100); display: flex; align-items: center; justify-content: center; position: relative; border-bottom: 3px solid var(--color-accent);">
                        <?php if (!empty($prod['image'])): ?>
                            <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo sanitize($prod['image']); ?>" alt="<?php echo sanitize($prod['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <!-- Fallback solid colored box with icon -->
                            <div style="position: absolute; inset: 0; background: var(--color-primary); opacity: 0.05;"></div>
                            <i class="<?php echo sanitize($prod['icon'] ?? 'fas fa-cube'); ?>" style="font-size: 4rem; color: var(--color-accent); z-index: 1;"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div style="padding: 2.5rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="font-size: 1.5rem; color: var(--color-primary); margin-bottom: 0.5rem;"><?php echo sanitize($prod['name']); ?></h3>
                        <?php if (!empty($prod['tagline'])): ?>
                            <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-accent); margin-bottom: 1.5rem; font-weight: 600;">
                                <?php echo sanitize($prod['tagline']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <p style="color: var(--color-gray-600); line-height: 1.7; margin-bottom: 2rem; flex: 1;">
                            <?php echo sanitize($prod['description'] ?? 'An enterprise-grade software product from Digital Practice.'); ?>
                        </p>
                        
                        <a href="<?php echo $link; ?>" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--color-gray-100); padding-top: 1.5rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; text-decoration: none;">
                            <span><?php echo $btn_text; ?></span>
                            <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--color-gray-50); display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                                <i class="fas fa-external-link-alt" style="color: var(--color-accent); font-size: 0.8rem;"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Ecosystem Pagination -->
            <div style="margin-top: 4rem;">
                <?php 
                    $base_url = SITE_URL . '/products';
                    if (!empty($search)) $base_url .= '?s=' . urlencode($search);
                    echo renderPagination($page, $total_pages, $base_url); 
                ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background-color: var(--color-gray-50); text-align: center; border-top: 1px solid var(--color-gray-100);">
    <div class="container animate-on-scroll">
        <h2 class="title-bold" style="margin-bottom: 1rem; font-size: 2.5rem;">Need a custom product built?</h2>
        <p style="color: var(--color-gray-600); margin-bottom: 2rem; font-size: 1.1rem;">Our engineering teams specialize in building bespoke proprietary systems.</p>
        <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary" style="padding: 1.2rem 3rem;">Discuss Your Requirements</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
