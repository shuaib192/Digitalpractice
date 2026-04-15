<?php
/**
 * Digital Practice - Homepage (Heirs-Inspired Overhaul)
 */
require_once __DIR__ . '/includes/db.php';

// Fetch services
$services = $pdo->query('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6')->fetchAll();

// Page settings
$page_title = "Powering Africa's Digital Future";
$body_class = 'home-page';
include __DIR__ . '/includes/header.php';
?>

<!-- High-Impact Hero Section (Total Brand Morph Engine) -->
<section class="hero" id="hero">
    <!-- Morphing Letter Grid — Handled by JavaScript Engine -->
    <div class="hero-letters-grid" id="morph-bg"></div>
    
    <div class="hero-overlay"></div>
    
    <!-- Background Image -->
    <div style="position: absolute; inset: 0; z-index: 1;">
        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&q=80&w=2000" 
             alt="Architectural Excellence" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.35;">
    </div>
    
    <div class="container hero-content">
        <h1 class="hero-title">
            Turning Digital<br>Potential into<br>
            <span class="hero-morph-word" id="morph-term">PERFORMANCE</span>.
        </h1>
        <p class="hero-subtitle">
            Resolving digital skilling, market access, and branding challenges through world-class, industry-standard services.
        </p>

        <a href="<?php echo SITE_URL; ?>/services" class="learn-more-link cursor-trigger">
            Learn More 
            <div class="arrow-circle">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
</section>

<script>
/**
 * Total Brand Morph Engine - High Performance Version
 */
const morphData = [
    { word: 'ALIGN',       term: 'ALIGNMENT' },
    { word: 'INNOVATE',    term: 'INNOVATION' },
    { word: 'ACQUIRE',     term: 'ACQUISITION' },
    { word: 'IMPACT',      term: 'IMPACT' },
    { word: 'SCALE',       term: 'SCALABILITY' },
    { word: 'PERFORM',     term: 'PERFORMANCE' }
];

let currentIndex = 0;
const bgContainer = document.getElementById('morph-bg');
const termContainer = document.getElementById('morph-term');

function morph() {
    const data = morphData[currentIndex];
    
    // 1. Morph Background Letters
    bgContainer.innerHTML = '';
    const letters = data.word.split('');
    
    letters.forEach((char, i) => {
        const span = document.createElement('span');
        span.className = 'hero-letter';
        span.textContent = char;
        
        // Asymmetrical Positioning Logic (Inspired by Heirs Technologies)
        const vSize = 25 + Math.random() * 15;
        const top = -10 + (i * 15) + (Math.random() * 10);
        const left = (i % 2 === 0) ? (5 + Math.random() * 15) : (50 + Math.random() * 25);
        
        span.style.fontSize = `${vSize}vw`;
        span.style.top = `${top}%`;
        span.style.left = `${left}%`;
        
        bgContainer.appendChild(span);
        
        // Trigger Entrance
        setTimeout(() => span.classList.add('active'), 50 * i);
    });
    
    // 2. Morph Headline Term
    termContainer.style.opacity = '0';
    termContainer.style.transform = 'translateY(10px)';
    
    setTimeout(() => {
        termContainer.textContent = data.term;
        termContainer.style.opacity = '1';
        termContainer.style.transform = 'translateY(0)';
    }, 500);

    // 3. Cycle logic
    currentIndex = (currentIndex + 1) % morphData.length;
}

// Initial Launch
morph();
setInterval(morph, 4000);
</script>

<!-- Our Capabilities Section (Corporate Border-Share Grid) -->
<section class="section">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <span class="section-label">Our Capabilities</span>
            <h2 class="title-bold">Specialized Solutions <br>for Modern Enterprise.</h2>
        </div>

        <div class="divisions-grid">
            <?php foreach ($BRAND_SERVICES as $service): ?>
            <div class="service-card animate-on-scroll">
                <div class="service-icon">
                    <i class="fas <?php echo $service['icon']; ?>"></i>
                </div>
                <h3><?php echo $service['title']; ?></h3>
                <p><?php echo $service['summary']; ?></p>
                <div style="margin-top: auto; padding-top: 2rem;">
                    <a href="<?php echo SITE_URL; ?>/service-details.php?id=<?php echo $service['id']; ?>" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: var(--color-accent); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">
                        Explore <i class="fas fa-arrow-right" style="font-size: 0.6rem;"></i>
                    </a>
                </div>
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
                <span class="section-label">Our Philosophy</span>
                <h2 class="title-bold" style="margin-bottom: 2rem; font-size: 3.2rem;">Impact Over <br>Activity.</h2>
                <p style="font-size: 1.15rem; line-height: 1.8; color: var(--color-gray-600); margin-bottom: 2.5rem;">
                    <?php echo BRAND_ABOUT_FULL; ?>
                </p>
                <a href="<?php echo SITE_URL; ?>/about" class="btn btn-primary">Discover ALIGN Values</a>
            </div>
            <div class="animate-on-scroll" style="position: relative;">
                <div style="width: 100%; height: 600px; background-color: var(--color-primary); position: relative; overflow: hidden;">
                    <!-- Highlight Image -->
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=1000" 
                         alt="Team Collaboration" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
                    
                    <div style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.4);"></div>
                </div>
                
                <!-- Info Square with NO Radius -->
                <div style="position: absolute; bottom: -30px; left: -30px; padding: 2.5rem; background-color: var(--color-accent); color: white; width: 280px;">
                    <div style="font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 2px; margin-bottom: 1rem;">Our Promise</div>
                    <div style="font-size: 1.1rem; font-weight: 500; line-height: 1.4;">Access fairly. Build skills. Deliver impact. Grow responsibly.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Global Subsidiaries (Premium Grid) -->
<section class="section" style="border-top: 1px solid var(--color-gray-100);">
    <div class="container">
        <div class="section-header animate-on-scroll text-center" style="margin-bottom: 4rem;">
            <span class="section-label">Strategic Ecosystem</span>
            <h2 class="title-bold">Our Subsidiaries</h2>
        </div>
        
        <div class="grid-3" style="gap: 2rem;">
            <?php foreach ($BRAND_SUBSIDIARIES as $sub): ?>
            <a href="<?php echo $sub['url']; ?>" target="_blank" class="animate-on-scroll" style="display: block; padding: 3rem; background: var(--color-white); border: 1px solid var(--color-gray-100); transition: all 0.3s ease-in-out; text-decoration: none;">
                <h3 style="font-size: 1.4rem; color: var(--color-primary); margin-bottom: 1rem;"><?php echo $sub['name']; ?></h3>
                <p style="color: var(--color-gray-500); font-size: 0.95rem; margin-bottom: 2rem;"><?php echo $sub['description']; ?></p>
                <span style="color: var(--color-accent); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Visit Platform <i class="fas fa-external-link-alt" style="margin-left: 5px; font-size: 0.6rem;"></i></span>
            </a>
            <?php endforeach; ?>
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
                <div style="font-size: 3.5rem; font-weight: 900; color: var(--color-accent);" data-count="24" data-suffix="/7">0</div>
                <div style="font-weight: 700; opacity: 0.6; text-transform: uppercase; font-size: 0.75rem; margin-top: 1rem; letter-spacing: 2px;">Elite Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action (Bold & Direct) -->
<section class="section" style="border-top: 1px solid var(--color-gray-100);">
    <div class="container animate-on-scroll text-center" style="max-width: 800px;">
        <span class="section-label">Strategic Partnership</span>
        <h2 class="title-bold" style="margin-bottom: 3rem; font-size: 3.5rem;">Ready to build the digital future?</h2>
        <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary btn-lg">Partner With Us</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
