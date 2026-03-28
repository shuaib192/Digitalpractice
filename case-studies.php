<?php
/**
 * Digital Practice - Case Studies Storefront
 */
$page_title = 'Proven Results';
$page_description = 'Explore how Digital Practice has transformed enterprise operations across Africa through engineering and strategic consulting.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Search & Pagination Orchestration
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$cases = [];
if (isset($pdo)) {
    try {
        $params = [1]; // is_published = 1
        $where_clause = "WHERE is_published = ?";
        if (!empty($search)) {
            $where_clause .= " AND (title LIKE ? OR client_name LIKE ? OR problem LIKE ? OR solution LIKE ? OR results LIKE ?)";
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

        // Fetch paginated cases
        $stmt = $pdo->prepare("SELECT * FROM case_studies $where_clause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
        $stmt->execute($params);
        $cases = $stmt->fetchAll();
    } catch(Exception $e) {}
}

include __DIR__ . '/includes/header.php';
?>

<!-- High-Impact Header -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 6rem) 0 4rem;">
    <div class="container text-center animate-on-scroll">
        <h1 style="color: var(--color-white); font-size: 4rem; margin-bottom: 1rem;">Proven Results.</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; font-size: 1.25rem; margin-bottom: 3rem;">Measurable business outcomes delivered through architectural excellence and strategic precision.</p>
        
        <!-- Strategy Search Interface -->
        <div style="max-width: 600px; margin: 0 auto; position: relative;">
            <form method="GET" action="" style="display: flex; background: white; padding: 5px;">
                <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search success stories..." style="flex: 1; border: none; padding: 1rem 1.5rem; font-size: 1rem; outline: none; font-family: var(--font-body);">
                <button type="submit" class="btn btn-primary" style="padding: 0 2rem; border: none; background: var(--color-primary);">SEARCH</button>
            </form>
        </div>
    </div>
</section>

<!-- Case Studies Grid -->
<section class="section">
    <div class="container">
        <?php if (empty($cases)): ?>
            <div style="text-align: center; padding: 6rem; background: var(--color-gray-50); border: 2px dashed var(--color-gray-200);">
                <p style="color: var(--color-gray-400); font-size: 1.1rem;">Success stories are currently being documented. Please check back shortly for our latest transformation reports.</p>
            </div>
        <?php else: ?>
            <div class="grid-2" style="gap: 1.5rem;">
                <?php foreach ($cases as $case): ?>
                <div class="case-study-card card-360 animate-on-scroll" style="background: var(--color-white); border: 1px solid var(--color-gray-200); display: flex; flex-direction: column;">
                    
                    <div style="height: 300px; background-color: var(--color-primary); position: relative; overflow: hidden;">
                        <?php if ($case['image']): ?>
                            <img src="<?php echo SITE_URL; ?>/assets/images/cases/<?php echo sanitize($case['image']); ?>" alt="<?php echo sanitize($case['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;">
                        <?php else: ?>
                            <div style="position: absolute; inset: 0; background: var(--color-primary); opacity: 0.1;"></div>
                            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: var(--color-accent); font-size: 4rem;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Premium Stat Badge (Floating, 0-Radius) -->
                        <div style="position: absolute; top: 0; right: 0; background: var(--color-accent); color: white; padding: 1.5rem; text-align: center; min-width: 140px;">
                            <div style="font-size: 1.8rem; font-weight: 900; line-height: 1;"><?php echo sanitize($case['stat_value']); ?></div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; margin-top: 5px; opacity: 0.9;"><?php echo sanitize($case['stat_label']); ?></div>
                        </div>
                    </div>

                    <div style="padding: 3.5rem; flex: 1; display: flex; flex-direction: column;">
                        <span style="color: var(--color-accent); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px; margin-bottom: 1rem; display: block;">
                            CLIENT: <?php echo sanitize($case['client_name']); ?>
                        </span>
                        <h2 style="font-size: 2.2rem; line-height: 1.1; color: var(--color-primary); margin-bottom: 2rem;"><?php echo sanitize($case['title']); ?></h2>
                        
                        <div style="border-top: 1px solid var(--color-gray-100); padding-top: 2rem; margin-top: auto;">
                            <!-- Problem Summary -->
                            <div style="margin-bottom: 2rem;">
                                <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-primary); margin-bottom: 0.8rem;">The Challenge</h4>
                                <p style="color: var(--color-gray-600); line-height: 1.7; font-size: 1rem;"><?php echo sanitize(substr($case['problem'], 0, 160)) . '...'; ?></p>
                            </div>
                            
                            <a href="<?php echo SITE_URL; ?>/case-studies/<?php echo $case['slug']; ?>" class="btn btn-primary" style="width: 100%; justify-content: space-between; padding: 1.2rem 2rem;">
                                View Full Transformation Case <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($cases) && !empty($search)): ?>
                <div style="text-align:center; padding: 4rem 0;">
                    <h3 style="color:var(--color-primary); font-family:var(--font-heading); margin-bottom:1rem;">NO MATCHING TRIUMPHS DETECTED</h3>
                    <p style="color:var(--color-gray-500);">Your search for "<?php echo sanitize($search); ?>" yielded 0 results. Please redefine your search telemetry.</p>
                    <a href="case-studies.php" style="color:var(--color-accent); font-weight:700; text-transform:uppercase; font-size:0.8rem; margin-top:2rem; display:inline-block;">Reset Registry</a>
                </div>
            <?php endif; ?>

            <!-- Archival Pagination -->
            <div style="margin-top: 4rem;">
                <?php 
                    $base_url = SITE_URL . '/case-studies';
                    if (!empty($search)) $base_url .= '?s=' . urlencode($search);
                    echo renderPagination($page, $total_pages, $base_url); 
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Bottom CTA -->
<section class="section bg-light" style="background-color: var(--color-gray-50); border-top: 1px solid var(--color-gray-200);">
    <div class="container text-center animate-on-scroll">
        <h2 class="title-bold" style="font-size: 2.8rem; margin-bottom: 1.5rem;">Achieve measurable ROI <br>on your digital investments.</h2>
        <p style="color: var(--color-gray-600); margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">Our engineering teams focus on outcomes, not just outputs. Let's discuss your organization's modernization goals.</p>
        <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary" style="padding: 1.2rem 3.5rem;">Schedule a Strategy Session</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
