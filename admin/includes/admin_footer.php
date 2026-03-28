    </main>
</div>

<script src="<?php echo JS_PATH; ?>/main.js?v=<?php echo ASSET_VERSION; ?>"></script>
<script>
/**
 * Digital Practice - Elite Administrative UX Handler
 * Robust Mobile Navigation & UI Interactivity
 */
(function() {
    const toggle = document.getElementById('admin-sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    
    if (toggle && sidebar) {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            toggle.classList.toggle('active');
        });

        // Click Outside Dismissal
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 900 && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
                toggle.classList.remove('active');
            }
        });
    }
})();
</script>
<!-- Global Command Orchestration: Scroll to Top -->
<div id="scroll-to-top" class="scroll-to-top-btn" title="Return to Summit">
    <i class="fas fa-chevron-up"></i>
</div>

<!-- Innovation Overlay: Tactical Intelligence HUD -->
<div id="tactical-hud" class="tactical-hud">
    <div class="hud-item">
        <div class="hud-pulse"></div>
        ADMIN: OPTIMIZED
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

<script src="<?php echo JS_PATH; ?>/hud.js?v=<?php echo ASSET_VERSION; ?>"></script>
</body>
</html>
