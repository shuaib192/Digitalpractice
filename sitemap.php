<?php
/**
 * Digital Practice - Dynamic Sitemap Generator
 * Optimizing Google Indexing and Tactical SEO
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Set high-fidelity XML header
header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Base Core Assets -->
    <url>
        <loc><?php echo SITE_URL; ?>/</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc><?php echo SITE_URL; ?>/services</loc>
        <priority>0.9</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc><?php echo SITE_URL; ?>/products</loc>
        <priority>0.9</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc><?php echo SITE_URL; ?>/insights</loc>
        <priority>0.8</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc><?php echo SITE_URL; ?>/about</loc>
        <priority>0.7</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc><?php echo SITE_URL; ?>/contact</loc>
        <priority>0.7</priority>
        <changefreq>monthly</changefreq>
    </url>

    <?php
    // Tactical Discovery: Services
    try {
        $services = $pdo->query("SELECT slug FROM services WHERE is_active = 1")->fetchAll();
        foreach ($services as $service) {
            echo '<url>';
            echo '<loc>' . SITE_URL . '/service-details.php?slug=' . $service['slug'] . '</loc>';
            echo '<priority>0.8</priority>';
            echo '<changefreq>monthly</changefreq>';
            echo '</url>';
        }
    } catch (Exception $e) {}

    // Tactical Discovery: Insights (Blogs)
    try {
        $insights = $pdo->query("SELECT slug, updated_at FROM insights WHERE status = 'published' ORDER BY created_at DESC")->fetchAll();
        foreach ($insights as $insight) {
            echo '<url>';
            echo '<loc>' . SITE_URL . '/insight-details.php?slug=' . $insight['slug'] . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($insight['updated_at'])) . '</lastmod>';
            echo '<priority>0.7</priority>';
            echo '<changefreq>monthly</changefreq>';
            echo '</url>';
        }
    } catch (Exception $e) {}
    ?>
</urlset>
