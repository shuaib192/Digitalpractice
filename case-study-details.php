<?php
/**
 * Digital Practice - Case Study Detail View
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header("Location: " . SITE_URL . "/case-studies");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM case_studies WHERE slug = ? AND is_published = 1 LIMIT 1");
$stmt->execute([$slug]);
$case = $stmt->fetch();

if (!$case) {
    header("HTTP/1.0 404 Not Found");
    die("<div style='text-align:center; padding: 100px; background:var(--color-primary); color:white; min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; font-family:var(--font-heading);'>
            <h1 style='font-size:5rem; font-weight:900;'>404</h1>
            <p style='font-size:1.5rem; opacity:0.7; margin-bottom:2rem;'>The requested strategic triumph asset could not be located.</p>
            <a href='" . SITE_URL . "/case-studies' style='color:var(--color-accent); text-decoration:none; font-weight:800; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid var(--color-accent);'>Return to Proven Results</a>
        </div>");
}

$page_title = sanitize($case['title']);
include __DIR__ . '/includes/header.php';
?>

<!-- Premium Header (Architectural Dark) -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 6rem) 0 4rem; position: relative; overflow: hidden;">
    <?php if ($case['image']): ?>
        <div style="position: absolute; inset: 0; z-index: 1;">
            <img src="<?php echo SITE_URL; ?>/assets/images/cases/<?php echo $case['image']; ?>" style="width:100%; height:100%; object-fit:cover; opacity: 0.15;">
        </div>
    <?php endif; ?>

    <div class="container animate-on-scroll" style="position: relative; z-index: 2;">
        <span style="color: var(--color-accent); font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 2px; margin-bottom: 1.5rem; display: block;">
            PROVEN RESULTS: <?php echo sanitize($case['client_name']); ?>
        </span>
        <h1 style="color: var(--color-white); font-size: 4rem; max-width: 900px; line-height: 1.1; margin-bottom: 2rem;"><?php echo sanitize($case['title']); ?></h1>
        
        <div style="display: inline-flex; align-items: center; gap: 2rem; background: var(--color-accent); padding: 2rem; color: white;">
            <div>
                <div style="font-size: 2.2rem; font-weight: 900; line-height: 1;"><?php echo sanitize($case['stat_value']); ?></div>
                <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-top: 5px; opacity: 0.8;">
                    <?php echo sanitize($case['stat_label']); ?>
                </div>
            </div>
            <div style="width: 1px; height: 40px; background: rgba(255,255,255,0.2);"></div>
            <div style="font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Project Impact</div>
        </div>
    </div>
</section>

<!-- Narrative Flow -->
<section class="section">
    <div class="container">
        <div class="grid-2" style="align-items: start; gap: 5rem;">
            <!-- Left Narrative -->
            <div class="animate-on-scroll">
                <div style="margin-bottom: 4rem;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; position: relative; padding-left: 1.5rem; border-left: 4px solid var(--color-accent);">The Challenge</h3>
                    <p style="font-size: 1.15rem; line-height: 1.9; color: var(--color-gray-600);"><?php echo nl2br(sanitize($case['problem'])); ?></p>
                </div>
                
                <div style="margin-bottom: 4rem;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; position: relative; padding-left: 1.5rem; border-left: 4px solid var(--color-accent);">The Solution</h3>
                    <p style="font-size: 1.15rem; line-height: 1.9; color: var(--color-gray-600);"><?php echo nl2br(sanitize($case['solution'])); ?></p>
                </div>

                <div style="margin-bottom: 4rem;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; position: relative; padding-left: 1.5rem; border-left: 4px solid var(--color-accent);">The Results</h3>
                    <p style="font-size: 1.15rem; line-height: 1.9; color: var(--color-gray-600);"><?php echo nl2br(sanitize($case['results'])); ?></p>
                </div>
            </div>

            <!-- Right Sidebar / Info -->
            <div class="animate-on-scroll" style="position: sticky; top: 120px;">
                <div style="background: var(--color-gray-50); padding: 3rem; border: 1px solid var(--color-gray-200);">
                    <h4 style="font-size: 1.1rem; margin-bottom: 1.5rem; color: var(--color-primary);">Transformation Summary</h4>
                    <p style="color: var(--color-gray-600); font-size: 0.95rem; line-height: 1.8; margin-bottom: 2rem;">
                        This case study represents how Digital Practice leverages architectural precision to solve complex enterprise problems.
                    </p>
                    
                    <ul style="list-style:none; padding:0; margin:0;">
                        <li style="display:flex; justify-content:space-between; padding: 1rem 0; border-bottom: 1px solid var(--color-gray-200);">
                            <span style="font-weight:700; color:var(--color-primary); font-size: 0.85rem; text-transform:uppercase;">Sector</span>
                            <span style="color:var(--color-gray-600); font-size:0.95rem;">Enterprise Technology</span>
                        </li>
                        <li style="display:flex; justify-content:space-between; padding: 1rem 0; border-bottom: 1px solid var(--color-gray-200);">
                            <span style="font-weight:700; color:var(--color-primary); font-size: 0.85rem; text-transform:uppercase;">Timeline</span>
                            <span style="color:var(--color-gray-600); font-size:0.95rem;">Complete Transformation</span>
                        </li>
                    </ul>

                    <div style="margin-top: 3rem;">
                        <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary" style="width: 100%; border-radius: 0;">Inquire About Similar Solutions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation -->
<section style="padding: 4rem 0; border-top: 1px solid var(--color-gray-100);">
    <div class="container text-center">
        <a href="<?php echo SITE_URL; ?>/case-studies" style="font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--color-accent); text-decoration: none;">&larr; Return to Case Studies</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
