<?php
/**
 * Digital Practice - About Page (Corporate Redesign)
 */
$page_title = 'Who We Are';
$page_description = 'Learn about Digital Practice, a pan-African technology powerhouse.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Search & Pagination Orchestration: Team Registry
$search = getSearchQuery();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$params = [1]; // is_active = 1
$where_clause = "WHERE is_active = ?";
if (!empty($search)) {
    $where_clause .= " AND (name LIKE ? OR position LIKE ? OR bio LIKE ? OR category LIKE ?)";
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

// Fetch team members (Paginated)
$stmt = $pdo->prepare("SELECT * FROM team_members $where_clause ORDER BY category, sort_order ASC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$team_members = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- Premium Page Header -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 5rem) 0 4rem;">
    <div class="container text-center animate-on-scroll">
        <h1 style="color: var(--color-white); font-size: 3.5rem; margin-bottom: 1rem;">Who We Are.</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; font-size: 1.15rem;">A pan-African technology powerhouse engineering digital solutions for the complex challenges of the modern enterprise.</p>
    </div>
</section>

<!-- Company Overview (Sharp, Architectural) -->
<section class="section" style="padding: 6rem 0;">
    <div class="container">
        <div class="grid-2" style="align-items: start;">
            <div class="animate-on-scroll">
                <span class="section-label" style="margin-bottom: 1rem; color: var(--color-accent); font-weight: 700; letter-spacing: 2px;">OUR MANDATE</span>
                <h2 class="title-bold" style="font-size: 2.7rem; color: var(--color-primary); margin-bottom: 2rem;">Digital Products. <br>Strategic Consulting.</h2>
                <div style="font-size: 1.1rem; line-height: 1.9; color: var(--color-gray-600); display: flex; flex-direction: column; gap: 1.5rem;">
                    <p>Digital Practice is engineered to elevate the standard of African enterprise systems. We bridge the gap between complex infrastructure challenges and scalable, elegant solutions through proprietary digital products and expert consulting.</p>
                    <p>Our focus is dual-tracked: We build bespoke, high-performance software products that solve specific industry pain points while providing the high-level technical consulting required to guide businesses through total digital transformation.</p>
                    <p>Our teams operate across key African tech hubs, ensuring that our solutions are not only globally standard but locally relevant and accessible to the organizations that need them most.</p>
                </div>
                
                <div style="margin-top: 3rem; display: flex; gap: 3rem; border-top: 1px solid var(--color-gray-200); padding-top: 2rem;">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 900; color: var(--color-accent);">15+</div>
                        <div style="text-transform: uppercase; font-size: 0.8rem; font-weight: 700; color: var(--color-gray-500); letter-spacing: 1px;">Years Operation</div>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 900; color: var(--color-accent);">12</div>
                        <div style="text-transform: uppercase; font-size: 0.8rem; font-weight: 700; color: var(--color-gray-500); letter-spacing: 1px;">African Markets</div>
                    </div>
                </div>
            </div>
            
            <div class="animate-on-scroll" style="position: relative; height: 100%;">
                <div style="background-color: var(--color-gray-100); height: 100%; min-height: 500px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                    <!-- Official CEO Portrait -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/team/ceo.png" alt="CEO - Digital Practice" style="position: absolute; width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.2);"></div>
                </div>
                <!-- Accent Block (Sharp) -->
                <div style="position: absolute; bottom: 0; left: -2rem; background: var(--color-accent); color: white; padding: 2.5rem; max-width: 300px;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Our Vision</h3>
                    <p style="font-size: 0.95rem; opacity: 0.9; line-height: 1.6;">To be the definitive technology partner for Africa's most ambitious organizations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section (Data-driven, precise) -->
<section class="section bg-light" style="background-color: var(--color-gray-50); border-top: 1px solid var(--color-gray-200); border-bottom: 1px solid var(--color-gray-200);">
    <div class="container">
        <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
            <h2 class="title-bold" style="font-size: 2.5rem;">Core Principles</h2>
        </div>
        
        <div class="grid-3" style="gap: 0; border: 1px solid var(--color-gray-200);">
            <div style="padding: 3rem; background: white; border-right: 1px solid var(--color-gray-200); border-bottom: 1px solid var(--color-gray-200);">
                <i class="fas fa-bullseye" style="font-size: 2rem; color: var(--color-accent); margin-bottom: 1.5rem;"></i>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Precision</h3>
                <p style="color: var(--color-gray-600); line-height: 1.7;">We execute with exacting standards, ensuring every solution is architected for maximum reliability and scale.</p>
            </div>
            <div style="padding: 3rem; background: white; border-right: 1px solid var(--color-gray-200); border-bottom: 1px solid var(--color-gray-200);">
                <i class="fas fa-chart-line" style="font-size: 2rem; color: var(--color-accent); margin-bottom: 1.5rem;"></i>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Performance</h3>
                <p style="color: var(--color-gray-600); line-height: 1.7;">Our metrics for success are directly tied to the measurable business outcomes we generate for our partners.</p>
            </div>
            <div style="padding: 3rem; background: white; border-bottom: 1px solid var(--color-gray-200);">
                <i class="fas fa-shield-alt" style="font-size: 2rem; color: var(--color-accent); margin-bottom: 1.5rem;"></i>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Integrity</h3>
                <p style="color: var(--color-gray-600); line-height: 1.7;">Enterprise trust is built on transparency and security. We safeguard your data as strictly as our own.</p>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team -->
<section id="team" class="section">
    <div class="container text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section-label" style="color: var(--color-accent);">LEADERSHIP</span>
        <h2 class="title-bold" style="font-size: 2.5rem; margin-bottom: 2rem;">Executive Team</h2>
        
        <!-- Personnel Search Interface -->
        <div style="max-width: 500px; margin: 0 auto; position: relative;">
            <form method="GET" action="about.php#team" style="display: flex; background: white; border: 1px solid var(--color-gray-100); padding: 5px;">
                <input type="text" name="s" value="<?php echo sanitize($search); ?>" placeholder="Search personnel..." style="flex: 1; border: none; padding: 0.8rem 1.2rem; font-size: 0.9rem; outline: none; font-family: var(--font-body);">
                <button type="submit" class="btn btn-primary" style="padding: 0 1.5rem; border: none; background: var(--color-primary);">SEARCH</button>
            </form>
        </div>
    </div>

    <?php if (empty($team_members) && !empty($search)): ?>
        <div class="container text-center" style="padding: 4rem 0;">
            <p style="color:var(--color-gray-500);">No mission personnel matched your search for "<?php echo sanitize($search); ?>".</p>
            <a href="about.php#team" style="color:var(--color-accent); font-weight:700; text-transform:uppercase; font-size:0.8rem; margin-top:1rem; display:inline-block;">Reset Registry</a>
        </div>
    <?php endif; ?>

    <!-- Team Grid (Strict Corporate Styling) -->
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2.5rem;">
        <?php foreach ($team_members as $member): ?>
        <div class="animate-on-scroll">
            <div style="background-color: var(--color-gray-100); height: 350px; margin-bottom: 1.5rem; position: relative;">
                <?php if ($member['photo']): ?>
                    <img src="<?php echo SITE_URL; ?>/assets/images/team/<?php echo sanitize($member['photo']); ?>" alt="<?php echo sanitize($member['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); transition: all 0.3s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; background: var(--color-primary); opacity: 0.1;"></div>
                <?php endif; ?>
            </div>
            <h3 style="font-size: 1.3rem; font-family: var(--font-heading); color: var(--color-primary); margin-bottom: 0.3rem;"><?php echo sanitize($member['name']); ?></h3>
            <div style="color: var(--color-accent); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem;"><?php echo sanitize($member['position']); ?></div>
            <p style="color: var(--color-gray-600); font-size: 0.95rem; line-height: 1.6;"><?php echo sanitize($member['bio']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Personnel Pagination -->
    <div class="container" style="margin-top: 4rem;">
        <?php 
            $base_url = SITE_URL . '/about.php#team';
            if (!empty($search)) $base_url .= '?s=' . urlencode($search);
            echo renderPagination($page, $total_pages, $base_url); 
        ?>
    </div>
</section>

<!-- Partners Section -->
<?php
$partners = [];
if (isset($pdo)) {
    try {
        $partners = $pdo->query('SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
    } catch(Exception $e) {}
}
?>
<section id="partners" class="section bg-light" style="background-color: var(--color-gray-50); border-top: 1px solid var(--color-gray-200);">
    <div class="container text-center animate-on-scroll" style="margin-bottom: 3rem;">
        <span class="section-label" style="color: var(--color-accent);">NETWORK</span>
        <h2 class="title-bold" style="font-size: 2.5rem;">Partners & Affiliates</h2>
        <p style="color: var(--color-gray-600); margin-top: 1rem; max-width: 600px; margin-left: auto; margin-right: auto;">We integrate seamlessly with leading global technology providers to deliver unparalleled enterprise solutions.</p>
    </div>

    <div class="container animate-on-scroll">
        <?php if(empty($partners)): ?>
            <div class="grid-4" style="align-items: center; justify-items: center; opacity: 0.5; filter: grayscale(100%);">
                <i class="fab fa-aws" style="font-size: 4rem; color: #232F3E;"></i>
                <i class="fab fa-microsoft" style="font-size: 4rem; color: #00A4EF;"></i>
                <i class="fab fa-google" style="font-size: 4rem; color: #4285F4;"></i>
                <i class="fab fa-salesforce" style="font-size: 4rem; color: #00A1E0;"></i>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 3rem; align-items: center; justify-items: center;">
                <?php foreach($partners as $p): ?>
                    <?php if(!empty($p['logo'])): ?>
                        <img src="<?php echo SITE_URL; ?>/assets/images/partners/<?php echo sanitize($p['logo']); ?>" alt="<?php echo sanitize($p['name']); ?>" style="max-height: 60px; max-width: 100%; filter: grayscale(100%); transition: filter 0.3s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                    <?php else: ?>
                        <div style="font-weight: 700; font-size: 1.2rem; color: var(--color-gray-500);"><?php echo sanitize($p['name']); ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
