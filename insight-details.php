<?php
/**
 * Digital Practice - Blog Post Details
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header("Location: " . SITE_URL . "/blog");
    exit;
}

$stmt = $pdo->prepare("SELECT b.*, a.full_name as author_name, a.avatar as author_avatar FROM blog_posts b LEFT JOIN admins a ON b.author_id = a.id WHERE b.slug = ? AND b.is_published = 1 LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header("HTTP/1.0 404 Not Found");
    die("<div style='text-align:center; padding: 100px; background:var(--color-primary); color:white; min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; font-family:var(--font-heading);'>
            <h1 style='font-size:5rem; font-weight:900;'>404</h1>
            <p style='font-size:1.5rem; opacity:0.7; margin-bottom:2rem;'>The requested intelligence asset could not be located.</p>
            <a href='" . SITE_URL . "/blog' style='color:var(--color-accent); text-decoration:none; font-weight:800; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid var(--color-accent);'>Return to Command Center</a>
        </div>");
}

$page_title = sanitize($post['title']);
include __DIR__ . '/includes/header.php';
?>

<!-- Premium Post Header -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 6rem) 0 4rem; position: relative; overflow: hidden;">
    <?php if ($post['featured_image']): ?>
        <div style="position: absolute; inset: 0; z-index: 1;">
            <img src="<?php echo SITE_URL; ?>/assets/images/blog/<?php echo $post['featured_image']; ?>" style="width:100%; height:100%; object-fit:cover; opacity: 0.2;">
        </div>
    <?php endif; ?>
    
    <div class="container animate-on-scroll" style="position: relative; z-index: 2;">
        <div style="margin-bottom: 2rem;">
            <span style="background: var(--color-accent); color: white; padding: 0.4rem 1rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; border-radius: 0;">
                <?php echo sanitize(str_replace('_', ' ', $post['category'])); ?>
            </span>
        </div>
        <h1 style="color: var(--color-white); font-size: 4rem; max-width: 900px; line-height: 1.1; margin-bottom: 2rem;"><?php echo sanitize($post['title']); ?></h1>
        
        <div style="display: flex; align-items: center; gap: 1.5rem; color: rgba(255,255,255,0.7); font-weight: 600;">
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <div style="width: 40px; height: 40px; border-radius: 0; background: var(--color-accent); display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <span><?php echo sanitize($post['author_name'] ?: 'DP Editorial'); ?></span>
            </div>
            <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.2);"></div>
            <span><?php echo date('F j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Excerpt/Lead -->
            <?php if ($post['excerpt']): ?>
                <p style="font-size: 1.5rem; line-height: 1.6; color: var(--color-primary); font-family: var(--font-heading); font-weight: 500; margin-bottom: 4rem; padding-left: 2rem; border-left: 5px solid var(--color-accent);">
                    <?php echo sanitize($post['excerpt']); ?>
                </p>
            <?php endif; ?>

            <!-- Main Content (Quill Render) -->
            <div class="blog-content" style="font-size: 1.15rem; line-height: 2; color: var(--color-gray-700);">
                <?php echo $post['content']; // HTML from Quill ?>
            </div>

            <!-- Tactical Intelligence Sharing -->
            <div style="margin-top: 6rem; padding-top: 3rem; border-top: 1px solid var(--color-gray-50); display: flex; flex-direction:column; gap: 1.5rem;">
                <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--color-gray-400);">Distribute Intelligence</span>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
                    <div style="display: flex; gap: 0.8rem;">
                        <?php 
                        $current_url = urlencode((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                        $share_title = urlencode($post['title']);
                        ?>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $current_url; ?>" target="_blank" title="Share on LinkedIn" style="width: 45px; height: 45px; border: 1px solid var(--color-gray-100); display: flex; align-items: center; justify-content: center; color: var(--color-primary); transition: all 0.3s;" onmouseover="this.style.backgroundColor='var(--color-accent)'; this.style.color='white'; this.style.borderColor='var(--color-accent)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-primary)'; this.style.borderColor='var(--color-gray-100)';">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $share_title; ?>" target="_blank" title="Share on X" style="width: 45px; height: 45px; border: 1px solid var(--color-gray-100); display: flex; align-items: center; justify-content: center; color: var(--color-primary); transition: all 0.3s;" onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='white'; this.style.borderColor='var(--color-primary)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-primary)'; this.style.borderColor='var(--color-gray-100)';">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" target="_blank" title="Share on Facebook" style="width: 45px; height: 45px; border: 1px solid var(--color-gray-100); display: flex; align-items: center; justify-content: center; color: var(--color-primary); transition: all 0.3s;" onmouseover="this.style.backgroundColor='#1877F2'; this.style.color='white'; this.style.borderColor='#1877F2';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-primary)'; this.style.borderColor='var(--color-gray-100)';">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $current_url; ?>" target="_blank" title="Share on WhatsApp" style="width: 45px; height: 45px; border: 1px solid var(--color-gray-100); display: flex; align-items: center; justify-content: center; color: var(--color-primary); transition: all 0.3s;" onmouseover="this.style.backgroundColor='#25D366'; this.style.color='white'; this.style.borderColor='#25D366';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-primary)'; this.style.borderColor='var(--color-gray-100)';">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    <a href="<?php echo SITE_URL; ?>/blog" style="font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--color-accent); text-decoration: none; font-size: 0.8rem; border-bottom: 2px solid transparent; transition: all 0.3s;" onmouseover="this.style.borderBottomColor='var(--color-accent)';" onmouseout="this.style.borderBottomColor='transparent';">&larr; Return to Intelligence Center</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
