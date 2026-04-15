<?php
/**
 * Digital Practice - Service Details Template (Dynamic Brand Integration)
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$service_id = $_GET['id'] ?? '';
$service_slug = $_GET['slug'] ?? '';
$service = null;

// 1. Check Brand Registry (Source of Truth)
if (!empty($service_id)) {
    foreach ($BRAND_SERVICES as $bs) {
        if ($bs['id'] === $service_id) {
            $service = [
                'title' => $bs['title'],
                'icon' => $bs['icon'],
                'short_desc' => $bs['summary'],
                'full_desc' => $bs['description']
            ];
            break;
        }
    }
}

// 2. Tactical Fallback: Database
if (!$service && (!empty($service_id) || !empty($service_slug))) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE (id = ? OR slug = ?) AND is_active = 1 LIMIT 1");
    $stmt->execute([$service_id, $service_slug]);
    $service = $stmt->fetch();
}

if (!$service) {
    header("HTTP/1.0 404 Not Found");
    die("<div style='text-align:center; padding: 100px; font-family: var(--font-body);'><h1>404 - Solution Not Found</h1><p>The requested digital capability could not be identified in our registry.</p><a href='" . SITE_URL . "/services'>Return to Solutions</a></div>");
}

$page_title = $service['title'];
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header (Premium Solid Color) -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 6rem) 0 4rem;">
    <div class="container text-center animate-on-scroll">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: rgba(255,255,255,0.05); color: var(--color-accent); font-size: 2.5rem; margin-bottom: 2rem;">
             <i class="<?php echo sanitize($service['icon'] ?? 'fas fa-cog'); ?>"></i>
        </div>
        <h1 style="color: var(--color-white); font-size: 3.5rem; margin-bottom: 1rem;"><?php echo sanitize($service['title']); ?></h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 700px; margin: 0 auto; font-size: 1.15rem;"><?php echo sanitize($service['short_desc']); ?></p>
    </div>
</section>

<!-- Content Block -->
<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="font-size: 1.15rem; line-height: 1.9; color: var(--color-gray-600); margin-bottom: 3rem;">
                <?php 
                    // Using full_desc if available, else a placeholder indicating dynamic capability
                    $content = $service['full_desc'] ?? '';
                    if (empty($content)) {
                        echo "<p>At Digital Practice, we approach <strong>" . sanitize($service['title']) . "</strong> with a methodology that guarantees scalable, enterprise-grade results.</p>";
                        echo "<p>This is a dynamically generated page powered by our new routing architecture. Content for this precise service can be managed via the admin panel, instantly reflecting here without requiring a deployment.</p>";
                    } else {
                        echo nl2br(sanitize($content));
                    }
                ?>
            </div>
            
            <div class="grid-2" style="margin-top: 4rem;">
                <div style="background: var(--color-gray-50); padding: 3rem;">
                    <h3 style="margin-bottom: 1rem; font-size: 1.4rem;">Why Choose Us?</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.8rem; display: flex; align-items: flex-start; gap: 10px;"><i class="fas fa-check" style="color: var(--color-accent); margin-top: 6px;"></i> Deep domain expertise</li>
                        <li style="margin-bottom: 0.8rem; display: flex; align-items: flex-start; gap: 10px;"><i class="fas fa-check" style="color: var(--color-accent); margin-top: 6px;"></i> Predictable delivery timelines</li>
                        <li style="margin-bottom: 0.8rem; display: flex; align-items: flex-start; gap: 10px;"><i class="fas fa-check" style="color: var(--color-accent); margin-top: 6px;"></i> Scalable architectures</li>
                    </ul>
                </div>
                <div style="background: var(--color-primary); padding: 3rem; color: white;">
                    <h3 style="margin-bottom: 1rem; font-size: 1.4rem; color: white;">Ready to start?</h3>
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 2rem;">Our enterprise architects are ready to discuss your requirements and draft a tailored roadmap.</p>
                    <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary" style="background: var(--color-accent); color: white;">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
