<?php
/**
 * Digital Practice - Footer Component (Enterprise Update)
 */
?>
<footer class="site-footer" style="background-color: var(--color-primary); color: rgba(255, 255, 255, 0.7); padding: 6rem 0 2rem; border-top: 2px solid var(--color-accent);">
    <div class="container">
        <div class="footer-grid">
            
            <!-- Brand -->
            <div class="footer-brand">
                <a href="<?php echo SITE_URL; ?>" class="logo logo-light" style="margin-bottom: 2rem; display: inline-flex; align-items:center;">
                    <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Digital Practice" style="height: 50px; width: auto;">
                </a>
                <p style="font-size: 0.95rem; line-height: 1.8; margin-bottom: 2rem; max-width: 90%;">
                    <?php echo SITE_TAGLINE; ?>. We deliver world-class digital products, strategic IT consulting, and comprehensive managed services for enterprises across Africa.
                </p>
                <div class="footer-social" style="display: flex; gap: 1rem;">
                    <a href="#" aria-label="LinkedIn" style="width: 40px; height: 40px; border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; transition: all 0.3s;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="Twitter" style="width: 40px; height: 40px; border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; transition: all 0.3s;"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Facebook" style="width: 40px; height: 40px; border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; transition: all 0.3s;"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-links">
                <h4 style="color: var(--color-white); font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem;">Company</h4>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/company" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">About Us</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/services" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Our Solutions</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/case-studies" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Case Studies</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/insights" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Insights & Blog</a></li>
                </ul>
            </div>

            <!-- Top Services -->
            <div class="footer-links">
                <h4 style="color: var(--color-white); font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem;">Expertise</h4>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/services/digital-product-development" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Product Engineering</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/services/ai-data-solutions" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Data & AI</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/services/cloud-solutions" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Enterprise Cloud</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo SITE_URL; ?>/services/cybersecurity" style="color: rgba(255,255,255,0.6); transition: color 0.3s;">Cybersecurity</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-contact">
                <h4 style="color: var(--color-white); font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem;">Headquarters</h4>
                <div class="footer-contact-item" style="display: flex; gap: 15px; margin-bottom: 1.5rem; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-map-marker-alt" style="color: var(--color-accent); margin-top: 5px;"></i>
                    <span style="line-height: 1.6;"><?php echo SITE_ADDRESS; ?></span>
                </div>
                <div class="footer-contact-item" style="display: flex; gap: 15px; margin-bottom: 1.5rem; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-envelope" style="color: var(--color-accent); margin-top: 5px;"></i>
                    <span><?php echo SITE_EMAIL; ?></span>
                </div>
                <div class="footer-contact-item" style="display: flex; gap: 15px; margin-bottom: 1.5rem; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-phone" style="color: var(--color-accent); margin-top: 5px;"></i>
                    <span><?php echo SITE_PHONE; ?></span>
                </div>
            </div>
            
        </div>

        <div class="footer-bottom" style="text-align: center; padding-top: 2rem; font-size: 0.85rem; color: rgba(255,255,255,0.4);">
            <span>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</span>
        </div>
    </div>
</footer>

<!-- Add hover styles via embedded tag since it's component specific, or rely on index.css -->
<style>
.footer-social a:hover { background-color: var(--color-accent); border-color: var(--color-accent) !important; color: white; }
.footer-links a:hover { color: var(--color-accent) !important; padding-left: 5px; }
</style>

<!-- Global Command Orchestration: Scroll to Top -->
<div id="scroll-to-top" class="scroll-to-top-btn" title="Return to Summit">
    <i class="fas fa-chevron-up"></i>
</div>

<!-- Innovation Overlay: Tactical Intelligence HUD -->
<div id="tactical-hud" class="tactical-hud">
    <div class="hud-item">
        <div class="hud-pulse"></div>
        SYSTEM: OPTIMIZED
    </div>
    <div class="hud-item">
        <i class="fas fa-compass" style="color: var(--color-accent);"></i>
        SCROLL: <span id="hud-scroll-pct">0%</span>
    </div>
    <div class="hud-item">
        <i class="fas fa-microchip" style="color: var(--color-accent);"></i>
        LATENCY: <span id="hud-latency">0.000s</span>
    </div>
</div>

<!-- Scripts -->
<script src="<?php echo JS_PATH; ?>/main.js?v=<?php echo ASSET_VERSION; ?>"></script>
<script src="<?php echo JS_PATH; ?>/hud.js?v=<?php echo ASSET_VERSION; ?>"></script>
</body>
</html>
