/* 
 * animations.js
 * Handles scroll reveal animations, FAQ toggles, and navbar effects
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- Scroll Reveal Animations ---
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // Optional: stop observing once revealed
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const revealElements = document.querySelectorAll('.reveal-up');
    revealElements.forEach(el => observer.observe(el));

    // --- Navbar Scroll Effect + Mobile Menu ---
    const navbar = document.getElementById('navbar');
    if (navbar) {
        const primaryLinks = navbar.querySelectorAll('[data-navbar-link="primary"]');
        const mutedLinks = navbar.querySelectorAll('[data-navbar-link="muted"]');
        const brandText = navbar.querySelector('[data-navbar-brand="text"]');
        const brandIcon = navbar.querySelector('[data-navbar-brand="icon"]');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuButton = document.getElementById('mobile-menu-btn');

        const setNavbarState = (isScrolled) => {
            if (isScrolled) {
                navbar.classList.replace('bg-neutral-900/80', 'bg-white/90');
                navbar.classList.add('border-neutral-200/60');
                navbar.classList.remove('border-white/10');

                primaryLinks.forEach((el) => el.classList.replace('text-white', 'text-neutral-900'));
                mutedLinks.forEach((el) => el.classList.replace('text-neutral-300', 'text-neutral-600'));
                brandText?.classList.replace('text-white', 'text-neutral-900');
                brandIcon?.classList.replace('text-white', 'text-neutral-900');
            } else {
                navbar.classList.replace('bg-white/90', 'bg-neutral-900/80');
                navbar.classList.add('border-white/10');
                navbar.classList.remove('border-neutral-200/60');

                primaryLinks.forEach((el) => el.classList.replace('text-neutral-900', 'text-white'));
                mutedLinks.forEach((el) => el.classList.replace('text-neutral-600', 'text-neutral-300'));
                brandText?.classList.replace('text-neutral-900', 'text-white');
                brandIcon?.classList.replace('text-neutral-900', 'text-white');
            }
        };

        setNavbarState(window.scrollY > 20);
        window.addEventListener('scroll', () => setNavbarState(window.scrollY > 20));

        if (mobileMenu && mobileMenuButton) {
            const closeMobileMenu = () => {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            };

            mobileMenuButton.addEventListener('click', () => {
                const isOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', String(!isOpen));
            });

            mobileMenu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', closeMobileMenu);
            });

            document.addEventListener('click', (event) => {
                if (!navbar.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                    closeMobileMenu();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeMobileMenu();
                }
            });
        }
    }

    // --- FAQ Accordion Toggle ---
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const btn = item.querySelector('.faq-toggle');
        const content = item.querySelector('.faq-content');
        const icon = item.querySelector('.faq-icon');

        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');

            // Close all other items (optional - if you want accordion style)
            faqItems.forEach(otherItem => {
                if(otherItem !== item) {
                    otherItem.classList.remove('open');
                    otherItem.querySelector('.faq-content').style.height = '0px';
                    otherItem.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current item
            if (isOpen) {
                item.classList.remove('open');
                content.style.height = '0px';
                icon.style.transform = 'rotate(0deg)';
            } else {
                item.classList.add('open');
                content.style.height = content.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });

    // --- Restart progress bar animations on load ---
    setTimeout(() => {
        const bars = document.querySelectorAll('.progress-animated');
        bars.forEach(bar => {
            const target = bar.style.getPropertyValue('--target');
            if(target) {
                bar.style.width = target;
            }
        });
    }, 500);
});
