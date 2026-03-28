/**
 * Digital Practice - High-Impact JavaScript
 * Premium animation, custom cursor, and interaction system.
 */
document.addEventListener('DOMContentLoaded', () => {

    // =============================================
    // Mobile Navigation Toggle
    // =============================================
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        const dropdownToggles = document.querySelectorAll('.dropdown-container .nav-link');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                if (window.innerWidth <= 991) {
                    const dropdown = toggle.nextElementSibling;
                    if (dropdown && dropdown.classList.contains('mega-dropdown')) {
                        e.preventDefault();
                        toggle.parentElement.classList.toggle('open');
                    }
                }
            });
        });
    }

    // =============================================
    // Custom Cursor Logic
    // =============================================
    const cursor = document.getElementById('custom-cursor');
    const follower = document.getElementById('custom-cursor-follower');
    let mouseX = 0, mouseY = 0;
    let ballX = 0, ballY = 0;
    let followX = 0, followY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;

        // Activate cursor on first movement to prevent initial obstruction
        if (cursor && !cursor.classList.contains('active')) {
            cursor.classList.add('active');
            follower.classList.add('active');
        }
    });

    // Smooth Cursor Movement (Tick)
    function animateCursor() {
        // Linear interpolation for smooth lag
        ballX += (mouseX - ballX) * 0.15;
        ballY += (mouseY - ballY) * 0.15;
        followX += (mouseX - followX) * 0.4;
        followY += (mouseY - followY) * 0.4;

        if (cursor) {
            cursor.style.left = ballX + 'px';
            cursor.style.top = ballY + 'px';
        }
        if (follower) {
            follower.style.left = followX + 'px';
            follower.style.top = followY + 'px';
        }

        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Cursor Interactions
    const interactiveElements = document.querySelectorAll('a, button, .cursor-trigger');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.classList.add('cursor-hover');
        });
        el.addEventListener('mouseleave', () => {
            cursor.classList.remove('cursor-hover');
        });
    });

    // =============================================
    // Header Scroll Effect
    // =============================================
    const header = document.querySelector('.site-header');
    if (header) {
        const onScroll = () => {
            header.classList.toggle('scrolled', window.scrollY > 50);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // =============================================
    // Advanced Reveal Animations (Intersection Observer)
    // =============================================
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    if (animatedElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    
                    // Specific Entrance Animations
                    if (el.classList.contains('from-right')) {
                        el.style.animation = 'fadeInRight 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards';
                    } else if (el.classList.contains('scale-up')) {
                        el.style.animation = 'scaleIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards';
                    } else if (el.classList.contains('card-360')) {
                        el.style.animation = 'flipIn 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';
                    } else {
                        el.style.animation = 'reveal-text 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards';
                    }
                    
                    el.classList.add('visible');
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.15 });

        animatedElements.forEach(el => observer.observe(el));
    }

    // =============================================
    // Counter Animation for Stats
    // =============================================
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length > 0) {
        const animateCounter = (el) => {
            const target = parseInt(el.getAttribute('data-count'), 10);
            const suffix = el.getAttribute('data-suffix') || '';
            const duration = 2500;
            const start = performance.now();

            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                // Ultra-smooth exponential out
                const eased = 1 - Math.pow(2, -10 * progress);
                el.textContent = Math.floor(target * eased) + suffix;
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => counterObserver.observe(c));
    }

    // =============================================
    // Subtle Parallax on Hero Decorative Letters
    // =============================================
    const heroLetters = document.querySelector('.hero-letters');
    if (heroLetters) {
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            heroLetters.style.transform = `translateY(${scrolled * 0.4}px) rotate(${scrolled * 0.02}deg)`;
        }, { passive: true });
    }

    // =============================================
    // Global Command: Scroll to Top Orchestration
    // =============================================
    const scrollToTopBtn = document.getElementById('scroll-to-top');
    if (scrollToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        }, { passive: true });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

});
