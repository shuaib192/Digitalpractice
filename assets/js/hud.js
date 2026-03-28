/**
 * Tactical Intelligence HUD (Innovation Layer)
 * Real-time site telemetry and performance monitoring.
 */
document.addEventListener('DOMContentLoaded', () => {
    const hud = document.getElementById('tactical-hud');
    const scrollMetric = document.getElementById('hud-scroll-pct');
    const latencyMetric = document.getElementById('hud-latency');
    
    if (!hud) return;

    // Pulse Animation Initializer
    const pulse = document.querySelector('.hud-pulse');
    if (pulse) {
        pulse.style.animationPlayState = 'running';
    }

    // Scroll Telemetry
    const updateScrollTelemetry = () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        if (scrollMetric) {
            scrollMetric.innerText = Math.round(scrolled) + '%';
        }
    };

    // Performance Telemetry (Simulated logic for 'WOW' factor)
    const updatePerformanceTelemetry = () => {
        if (latencyMetric) {
            const perf = (window.performance.now() / 100000).toFixed(3);
            latencyMetric.innerText = perf + 's';
        }
    };

    window.addEventListener('scroll', updateScrollTelemetry, { passive: true });
    
    // Periodic refresh for the 'alive' feel
    setInterval(updatePerformanceTelemetry, 3000);
    
    updateScrollTelemetry();
    updatePerformanceTelemetry();

    console.log("Tactical Intelligence HUD: Online. System State: Optimized.");
});
