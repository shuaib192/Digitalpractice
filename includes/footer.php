<?php
/**
 * Digital Practice - Footer Component (Enterprise Update)
 */
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            
            <!-- Brand -->
            <div class="footer-brand">
                <a href="<?php echo SITE_URL; ?>" class="logo">
                    <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Digital Practice">
                </a>
                <p class="brand-desc">
                    <?php echo SITE_TAGLINE; ?>. We deliver world-class digital products, strategic IT consulting, and comprehensive managed services for enterprises across Africa.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-links">
                <h4>Company</h4>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/company">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services">Our Solutions</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/case-studies">Case Studies</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/insights">Insights & Blog</a></li>
                </ul>
            </div>

            <!-- Top Services -->
            <div class="footer-links">
                <h4>Expertise</h4>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/services/digital-product-development">Product Engineering</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services/ai-data-solutions">Data & AI</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services/cloud-solutions">Enterprise Cloud</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services/cybersecurity">Cybersecurity</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-contact">
                <h4>Headquarters</h4>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo SITE_ADDRESS; ?></span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo SITE_EMAIL; ?></span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo SITE_PHONE; ?></span>
                </div>
            </div>
            
        </div>

        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</span>
        </div>
    </div>
</footer>


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
