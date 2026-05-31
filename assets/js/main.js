/**
 * MonteroStudio - Main Application Controller
 * Manages SPA Routing, Magnetic Elements, Custom Cursor, Spotlight Effects, Cinema Mode, and Contact AJAX.
 */

(function () {
    'use strict';

    // Application state
    const App = {
        currentHash: '',
        cursor: { x: 0, y: 0, targetX: 0, targetY: 0, lerp: 0.12 },
        glow: { x: 0, y: 0, targetX: 0, targetY: 0, lerp: 0.05 },
        cinemaProjects: [],
        cinemaIndex: 0,
        cinemaTimer: null
    };

    // Initialize application on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        initSpaRouter();
        initMagneticElements();
        initSpotlightCards();
        initPortfolioFilters();
        initCinemaMode();
        initContactForm();
        setupObserverAnimations();
    });

    /**
     * SPA Hash Router
     */
    function initSpaRouter() {
        const sections = document.querySelectorAll('.spa-section');
        const navLinks = document.querySelectorAll('.nav-link');

        function handleRoute() {
            let hash = window.location.hash || '#home';
            App.currentHash = hash;

            let targetSection = document.querySelector(hash);
            if (!targetSection) {
                hash = '#home';
                targetSection = document.querySelector('#home');
            }

            // Close Cinema Mode if open when switching tabs
            if (document.body.classList.contains('cinema-active')) {
                closeCinema();
            }

            // Smoothly swap sections
            sections.forEach(sec => {
                sec.classList.remove('active');
                sec.style.opacity = '0';
                sec.style.transform = 'translateY(15px)';
            });

            setTimeout(() => {
                sections.forEach(sec => {
                    if ('#' + sec.id === hash) {
                        sec.classList.add('active');
                        // Small frame delay to trigger transition smoothly
                        requestAnimationFrame(() => {
                            sec.style.opacity = '1';
                            sec.style.transform = 'translateY(0)';
                        });
                    }
                });
            }, 100);

            // Update navigation active states
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === hash) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Scroll to top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        window.addEventListener('hashchange', handleRoute);
        // Execute route on load
        handleRoute();
    }

    /**
     * Magnetic Hover Effect
     */
    function initMagneticElements() {
        const magnetics = document.querySelectorAll('.magnetic-button');

        magnetics.forEach(el => {
            el.addEventListener('mousemove', function (e) {
                const bound = el.getBoundingClientRect();
                const x = e.clientX - bound.left - (bound.width / 2);
                const y = e.clientY - bound.top - (bound.height / 2);
                
                // Pull content sutilmente
                el.style.transform = `translate(${x * 0.35}px, ${y * 0.35}px)`;
                if (el.querySelector('.magnetic-inner')) {
                    el.querySelector('.magnetic-inner').style.transform = `translate(${x * 0.15}px, ${y * 0.15}px)`;
                }
            });

            el.addEventListener('mouseleave', function () {
                el.style.transform = 'translate(0px, 0px)';
                if (el.querySelector('.magnetic-inner')) {
                    el.querySelector('.magnetic-inner').style.transform = 'translate(0px, 0px)';
                }
            });
        });
    }

    /**
     * Card Border Spotlight (Glow Cards)
     */
    function initSpotlightCards() {
        const cards = document.querySelectorAll('.spotlight-card');

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                // Update coordinates directly inside elements styles
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    }

    /**
     * Filterable Portfolio Masonry
     */
    function initPortfolioFilters() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.portfolio-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update buttons styling
                filterBtns.forEach(b => {
                    b.classList.remove('bg-blue-600', 'text-white');
                    b.classList.add('bg-slate-900/50', 'text-slate-400');
                });
                btn.classList.add('bg-blue-600', 'text-white');
                btn.classList.remove('bg-slate-900/50', 'text-slate-400');

                const filterValue = btn.getAttribute('data-filter');

                items.forEach(item => {
                    item.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                        // Tiny delay to allow display setting to register before animating opacity
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 400);
                    }
                });
            });
        });
    }

    /**
     * Immersive Cinema Mode Presentation
     */
    function initCinemaMode() {
        // Collect cinematic metadata from hardcoded attributes in the document
        const slides = document.querySelectorAll('.cinema-data-source');
        slides.forEach(slide => {
            App.cinemaProjects.push({
                title: slide.getAttribute('data-title'),
                subtitle: slide.getAttribute('data-subtitle'),
                description: slide.getAttribute('data-description'),
                tags: slide.getAttribute('data-tags').split(','),
                image: slide.getAttribute('data-image'),
                link: slide.getAttribute('data-link')
            });
        });

        // Hook cinema activation to trigger buttons
        const triggerBtns = document.querySelectorAll('.open-cinema-btn');
        triggerBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const index = parseInt(btn.getAttribute('data-project-index') || '0', 10);
                openCinema(index);
            });
        });

        // Close buttons and backdrop click
        const closeBtn = document.getElementById('close-cinema');
        const overlay = document.getElementById('cinema-overlay');
        if (closeBtn) closeBtn.addEventListener('click', closeCinema);
        
        // Navigation buttons inside cinema
        const prevBtn = document.getElementById('cinema-prev');
        const nextBtn = document.getElementById('cinema-next');
        if (prevBtn) prevBtn.addEventListener('click', () => navigateCinema(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => navigateCinema(1));

        // Keyboard accessibility
        window.addEventListener('keydown', (e) => {
            if (!document.body.classList.contains('cinema-active')) return;
            if (e.key === 'Escape') closeCinema();
            if (e.key === 'ArrowRight') navigateCinema(1);
            if (e.key === 'ArrowLeft') navigateCinema(-1);
        });
    }

    function openCinema(index) {
        const overlay = document.getElementById('cinema-overlay');
        if (!overlay || App.cinemaProjects.length === 0) return;

        App.cinemaIndex = index;
        document.body.classList.add('cinema-active');
        overlay.classList.add('active');

        renderCinemaProject();
        startCinemaAutoplay();
    }

    function closeCinema() {
        const overlay = document.getElementById('cinema-overlay');
        if (!overlay) return;

        document.body.classList.remove('cinema-active');
        overlay.classList.remove('active');
        stopCinemaAutoplay();
    }

    function navigateCinema(direction) {
        App.cinemaIndex = (App.cinemaIndex + direction + App.cinemaProjects.length) % App.cinemaProjects.length;
        renderCinemaProject();
        // Reset timer on manual navigation
        startCinemaAutoplay();
    }

    function renderCinemaProject() {
        const proj = App.cinemaProjects[App.cinemaIndex];
        const titleEl = document.getElementById('cinema-title');
        const descEl = document.getElementById('cinema-desc');
        const tagsEl = document.getElementById('cinema-tags');
        const imgEl = document.getElementById('cinema-image');
        const linkEl = document.getElementById('cinema-link');
        const subtitleEl = document.getElementById('cinema-subtitle');

        if (!proj) return;

        // Apply smooth transition opacity while swapping content
        const elementsToFade = [titleEl, descEl, tagsEl, imgEl, subtitleEl];
        elementsToFade.forEach(el => {
            if (el) el.style.opacity = '0';
        });

        setTimeout(() => {
            if (titleEl) titleEl.textContent = proj.title;
            if (subtitleEl) subtitleEl.textContent = proj.subtitle;
            if (descEl) descEl.textContent = proj.description;
            if (imgEl) {
                imgEl.src = proj.image;
                imgEl.alt = proj.title;
            }
            if (linkEl) {
                if (proj.link && proj.link !== '#') {
                    linkEl.href = proj.link;
                    linkEl.classList.remove('hidden');
                } else {
                    linkEl.classList.add('hidden');
                }
            }

            if (tagsEl) {
                tagsEl.replaceChildren(); // Safe empty technique (as requested in guidelines)
                proj.tags.forEach(tag => {
                    const span = document.createElement('span');
                    span.className = 'px-3 py-1 text-xs font-semibold tracking-wide uppercase bg-blue-900/40 text-blue-300 border border-blue-800/40 rounded-full backdrop-blur-sm';
                    span.textContent = tag.trim();
                    tagsEl.appendChild(span);
                });
            }

            elementsToFade.forEach(el => {
                if (el) el.style.opacity = '1';
            });
        }, 300);
    }

    function startCinemaAutoplay() {
        stopCinemaAutoplay();
        App.cinemaTimer = setInterval(() => {
            navigateCinema(1);
        }, 8000); // Swaps every 8s like movie sequences
    }

    function stopCinemaAutoplay() {
        if (App.cinemaTimer) {
            clearInterval(App.cinemaTimer);
            App.cinemaTimer = null;
        }
    }

    /**
     * Safe AJAX Contact Form Submission
     */
    function initContactForm() {
        const form = document.getElementById('contact-form');
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        const initialBtnText = submitBtn ? submitBtn.textContent : 'Enviar Mensaje';

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Check button state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Enviando...';
            }

            const formData = new FormData(form);

            // Fetch to main index.php handler endpoint asynchronously
            fetch(form.action || window.location.href, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Servidor respondió con código de error.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showCustomModal('¡Éxito!', data.message, 'success');
                    form.reset();
                    // Update CSRF token in form if sent in response
                    if (data.new_csrf) {
                        const csrfInput = form.querySelector('input[name="csrf_token"]');
                        if (csrfInput) csrfInput.value = data.new_csrf;
                    }
                } else {
                    showCustomModal('Error de Envío', data.message || 'Verifica los campos e intenta de nuevo.', 'error');
                }
            })
            .catch(error => {
                showCustomModal('Error de Conexión', 'No pudimos establecer comunicación con el servidor. Inténtalo de nuevo más tarde.', 'error');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = initialBtnText;
                }
            });
        });
    }

    /**
     * Custom Dialog Modals (No native alert() permitted per security rules)
     */
    function showCustomModal(title, text, type) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md z-[99999] opacity-0 transition-opacity duration-300';
        
        const card = document.createElement('div');
        card.className = 'glass-panel p-8 max-w-md w-full border border-slate-700/60 shadow-2xl transform scale-95 transition-transform duration-300';

        // Colored dot indicator based on feedback context
        const color = type === 'success' ? 'bg-emerald-500 shadow-emerald-500/50' : 'bg-rose-500 shadow-rose-500/50';

        card.innerHTML = `
            <div class="flex items-center space-x-3 mb-4">
                <span class="w-3 h-3 rounded-full ${color} animate-pulse"></span>
                <h3 class="text-2xl font-bold tracking-tight text-white">${title}</h3>
            </div>
            <p class="text-slate-300 mb-6 leading-relaxed">${text}</p>
            <button class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-lg shadow-lg shadow-blue-600/35 transition-all text-center uppercase tracking-wide text-xs cursor-pointer">
                Entendido
            </button>
        `;

        modal.appendChild(card);
        document.body.appendChild(modal);

        // Frame animations
        requestAnimationFrame(() => {
            modal.classList.add('opacity-100');
            card.classList.add('scale-100');
        });

        const closeBtn = card.querySelector('button');
        const closeModal = () => {
            modal.classList.remove('opacity-100');
            card.classList.remove('scale-100');
            setTimeout(() => {
                modal.remove();
            }, 300);
        };

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    /**
     * Scroll Animations Observer (fades in contents as page scrolls)
     */
    function setupObserverAnimations() {
        const animateOnScrollElements = document.querySelectorAll('.scroll-animate');
        if (animateOnScrollElements.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target); // Trigger only once
                }
            });
        }, { threshold: 0.15 });

        animateOnScrollElements.forEach(el => observer.observe(el));
    }

})();
