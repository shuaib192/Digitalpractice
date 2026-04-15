<?php
/**
 * Digital Practice - Services Page (Corporate Redesign)
 */
$page_title = 'Our Services';
$page_description = 'Explore Digital Practice\'s comprehensive suite of enterprise digital transformation services.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$params = [1]; // is_active = 1
$where_clause = "WHERE is_active = ?";
if (!empty($search)) {
    $where_clause .= " AND (title LIKE ? OR short_desc LIKE ? OR full_desc LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM services $where_clause");
$stmt_count->execute($params);
$total_services = $stmt_count->fetchColumn();
$total_pages = ceil($total_services / $limit);

// Fetch services (Paginated)
$stmt = $pdo->prepare("SELECT * FROM services $where_clause ORDER BY sort_order ASC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$services = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- Premium Page Header -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 5rem) 0 4rem;">
    <div class="container text-center animate-on-scroll">
        <h1 style="color: var(--color-white); font-size: 3.5rem; margin-bottom: 1rem;">Solutions & Services</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; font-size: 1.15rem; margin-bottom: 3rem;">End-to-end digital engineering and consulting designed to modernize operations and accelerate enterprise growth.</p>
        
        <!-- Strategy Search Interface -->
        <div style="max-width: 600px; margin: 0 auto; position: relative;">
            <form method="GET" action="" style="display: flex; background: white; padding: 5px;">
                <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Discover specific solutions..." style="flex: 1; border: none; padding: 1rem 1.5rem; font-size: 1rem; outline: none; font-family: var(--font-body);">
                <button type="submit" class="btn btn-primary" style="padding: 0 2rem; border: none; background: var(--color-primary);">SEARCH</button>
            </form>
        </div>
    </div>
</section>

<!-- Services Grid View (No Radius, High Contrast) -->
<section class="section">
    <div class="container">
        <div class="grid-3" style="gap: 0; border: 1px solid var(--color-gray-100);">
            <?php foreach ($BRAND_SERVICES as $service): ?>
            <div class="service-card card-360 animate-on-scroll" style="height: 100%; position: relative;">
                <div style="margin-bottom: 2rem;">
                    <i class="fas <?php echo $service['icon']; ?>" style="font-size: 2.5rem; color: var(--color-accent);"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-family: var(--font-heading); font-weight: 800; color: var(--color-primary); margin-bottom: 1rem;">
                    <?php echo $service['title']; ?>
                </h3>
                <p style="color: var(--color-gray-600); font-size: 1rem; line-height: 1.8; margin-bottom: 2rem;">
                    <?php echo $service['summary']; ?>
                </p>
                
                <div style="margin-top: auto;">
                    <p style="font-size: 0.9rem; color: var(--color-gray-500); line-height: 1.6; margin-bottom: 1.5rem;">
                        <?php echo truncateText($service['description'], 150); ?>
                    </p>
                    <a href="<?php echo SITE_URL; ?>/service-details.php?id=<?php echo $service['id']; ?>" style="display: inline-flex; align-items: center; gap: 10px; font-weight: 700; color: var(--color-primary); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                        Explore specialized solution <i class="fas fa-arrow-right" style="color: var(--color-accent);"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($services)): ?>
            <div style="text-align:center; padding: 4rem 0;">
                <h3 style="color:var(--color-primary); font-family:var(--font-heading); margin-bottom:1rem;">NO MATCHING SOLUTIONS IDENTIFIED</h3>
                <p style="color:var(--color-gray-500);">Your search for "<?php echo sanitize($search); ?>" yielded 0 results. Please redefine your search telemetry.</p>
                <a href="services.php" style="color:var(--color-accent); font-weight:700; text-transform:uppercase; font-size:0.8rem; margin-top:2rem; display:inline-block;">Reset Search Registry</a>
            </div>
        <?php endif; ?>

        <!-- Portfolio Pagination -->
        <div style="margin-top: 4rem;">
            <?php 
                $base_url = SITE_URL . '/services';
                if (!empty($search)) $base_url .= '?s=' . urlencode($search);
                echo renderPagination($page, $total_pages, $base_url); 
            ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background-color: var(--color-gray-50); text-align: center; border-top: 1px solid var(--color-gray-100);">
    <div class="container animate-on-scroll">
        <h2 class="title-bold" style="margin-bottom: 2rem; font-size: 3rem;">Ready to modernize your infrastructure?</h2>
        <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary" style="padding: 1.2rem 3rem;">Schedule a technical consultation</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
