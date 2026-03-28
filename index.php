<?php
/**
 * Digital Practice - Homepage (Heirs-Inspired Overhaul)
 */
require_once __DIR__ . '/includes/db.php';

// Fetch services
$services = $pdo->query('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6')->fetchAll();

// Page settings
$page_title = "Powering Africa's Digital Future";
include __DIR__ . '/includes/header.php';
?>

<!-- High-Impact Hero Section -->
<section class="hero" id="hero">
    <!-- Large Background Decorative Letters (Premium Depth) -->
    <div class="hero-letters">CREATE</div>
    
    <div class="hero-overlay"></div>
    
    <!-- Background Image placeholder or high-quality Unsplash -->
    <div style="position: absolute; inset: 0; z-index: 1;">
        <img src="https://images.unsplash.com/photo-1573161158301-ba085d0d871b?auto=format&fit=crop&q=80&w=2000" 
             alt="Tech Office" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.2;">
    </div>
    
    <div class="container hero-content">
        <h1 class="hero-title animate-on-scroll">Architecting the <br>Digital Future of <br>African Enterprise.</h1>
        <p class="hero-subtitle animate-on-scroll" style="transition-delay: 150ms; margin-bottom: 3rem;">We deliver world-class product engineering, strategic consulting, and robust managed services to accelerate your digital maturity.</p>
        
        <!-- Global Discovery Interface -->
        <div class="animate-on-scroll" style="transition-delay: 200ms; max-width: 650px; margin-bottom: 4rem;">
            <form method="GET" action="blog.php" style="display: flex; background: white; padding: 5px; box-shadow: 0 30px 60px rgba(0,0,0,0.3);">
                <input type="text" name="s" placeholder="Search Global Intelligence, Services, or Success Stories..." style="flex: 1; border: none; padding: 1.2rem 2rem; font-size: 1.1rem; outline: none; font-family: var(--font-body);">
                <button type="submit" class="btn btn-primary" style="padding: 0 2.5rem; border: none; background: var(--color-primary); font-weight: 800; letter-spacing: 1px;">SEARCH</button>
            </form>
        </div>

        <a href="<?php echo SITE_URL; ?>/services" class="learn-more-link cursor-trigger animate-on-scroll" style="transition-delay: 350ms;">
            Discover Solutions 
            <div class="arrow-circle">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
</section>

<!-- Our Divisions Section (Clean Grid-Based - Sharp Edges) -->
<section class="section">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <span class="section-label">Our Divisions</span>
            <h2 class="title-bold">Specialized Solutions <br>for Modern Enterprise.</h2>
        </div>

        <div class="divisions-grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card card-360 animate-on-scroll cursor-trigger">
                <div class="service-icon">
                    <i class="<?php echo sanitize($service['icon']); ?>"></i>
                </div>
                <h3><?php echo sanitize($service['title']); ?></h3>
                <p><?php echo sanitize($service['short_desc']); ?></p>
                <div style="margin-top: 2rem; border-bottom: 3px solid var(--color-accent); width: 40px;"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Company Highlight (White Space & Focus) -->
<section class="section bg-light">
    <div class="container">
        <div class="grid-2" style="gap: var(--space-lg);">
            <div class="animate-on-scroll">
                <span class="section-label">Our Core Focus</span>
                <h2 class="title-bold" style="margin-bottom: 2rem; font-size: 3.2rem;">Digital Products & <br>Strategic Consulting.</h2>
                <p style="font-size: 1.15rem; line-height: 1.8; color: var(--color-gray-600); margin-bottom: 2.5rem;">
                    We design, build, and scale enterprise-grade digital products tailored to your needs, while delivering the strategic consulting required to modernize your digital infrastructure and accelerate business performance.
                </p>
                <a href="<?php echo SITE_URL; ?>/about" class="btn btn-primary">Discover Our Approach</a>
            </div>
            <div class="animate-on-scroll" style="position: relative;">
                <div style="width: 100%; height: 500px; background-color: var(--color-primary); position: relative; overflow: hidden;">
                    <!-- Highlight Image -->
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=1000" 
                         alt="Collaboration" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6;">
                    
                    <div style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.4);"></div>
                </div>
                
                <!-- Info Square with NO Radius -->
                <div style="position: absolute; bottom: -30px; left: -30px; padding: 2.5rem; background-color: var(--color-accent); color: white; width: 250px;">
                    <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;" data-count="15" data-suffix="+">0</div>
                    <div style="font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px;">Years Experience</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vertical Statistics (Focus & Clarity) -->
<section class="section" style="background-color: #000; color: white; padding: var(--space-lg) 0;">
    <div class="container">
        <div class="grid-4" style="text-align: left;">
            <div class="animate-on-scroll">
                <div style="font-size: 3.5rem; font-weight: 900; color: var(--color-accent);" data-count="120" data-suffix="+">0</div>
                <div style="font-weight: 700; opacity: 0.6; text-transform: uppercase; font-size: 0.75rem; margin-top: 1rem; letter-spacing: 2px;">Delivered Projects</div>
            </div>
            <div class="animate-on-scroll" style="transition-delay: 100ms;">
                <div style="font-size: 3.5rem; font-weight: 900; color: var(--color-accent);" data-count="50" data-suffix="+">0</div>
                <div style="font-weight: 700; opacity: 0.6; text-transform: uppercase; font-size: 0.75rem; margin-top: 1rem; letter-spacing: 2px;">Corporate Clients</div>
            </div>
            <div class="animate-on-scroll" style="transition-delay: 200ms;">
                <div style="font-size: 3.5rem; font-weight: 900; color: var(--color-accent);" data-count="12" data-suffix="">0</div>
                <div style="font-weight: 700; opacity: 0.6; text-transform: uppercase; font-size: 0.75rem; margin-top: 1rem; letter-spacing: 2px;">African Regions</div>
            </div>
            <div class="animate-on-scroll" style="transition-delay: 300ms;">
                <div style="font-size: 3.5rem; font-weight: 900; color: var(--color-accent);" data-count="500" data-suffix="+">0</div>
                <div style="font-weight: 700; opacity: 0.6; text-transform: uppercase; font-size: 0.75rem; margin-top: 1rem; letter-spacing: 2px;">Hours Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action (Bold & Direct) -->
<section class="section" style="border-top: 1px solid var(--color-gray-100);">
    <div class="container animate-on-scroll text-center" style="max-width: 800px;">
        <span class="section-label">Ready to Begin?</span>
        <h2 class="title-bold" style="margin-bottom: 3rem; font-size: 3.5rem;">Let's build the digital future together.</h2>
        <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary btn-lg">Partner With Us</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
