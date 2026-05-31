/**
 * MonteroStudio - Optimized Canvas Particle System
 * Highly performant, responsive, battery-friendly, and interactive.
 */

(function () {
    'use strict';

    class ParticleSystem {
        constructor(canvasId) {
            this.canvas = document.getElementById(canvasId);
            if (!this.canvas) return;

            this.ctx = this.canvas.getContext('2d');
            this.particles = [];
            this.mouse = { x: null, y: null, radius: 130 };
            this.animationFrameId = null;
            this.isActive = true;

            // Base configurations
            this.maxDistance = 100; // max line connection distance
            this.particleDensity = 0.00007; // particles per square pixel

            this.init();
            this.bindEvents();
        }

        init() {
            this.resizeCanvas();
            this.createParticles();
            this.start();
        }

        resizeCanvas() {
            this.width = this.canvas.width = window.innerWidth;
            this.height = this.canvas.height = window.innerHeight;
        }

        createParticles() {
            this.particles = [];
            // Dynamically calculate particle quantity based on screen area to maintain consistent density
            const area = this.width * this.height;
            let maxParticles = Math.floor(area * this.particleDensity);

            // Bounds for mobile and ultra-wide performance protection
            if (this.width < 768) {
                maxParticles = Math.min(maxParticles, 45); // Limit mobile
            } else {
                maxParticles = Math.min(maxParticles, 125); // Limit desktop cap
            }

            for (let i = 0; i < maxParticles; i++) {
                const size = Math.random() * 2 + 1; // 1px to 3px
                this.particles.push({
                    x: Math.random() * this.width,
                    y: Math.random() * this.height,
                    vx: (Math.random() - 0.5) * 0.4, // Speed X
                    vy: (Math.random() - 0.5) * 0.4, // Speed Y
                    size: size,
                    // Mostly royal blue tones and a few white particles
                    color: Math.random() > 0.3 
                        ? `rgba(37, 99, 235, ${Math.random() * 0.4 + 0.2})`  // Royal Blue
                        : `rgba(255, 255, 255, ${Math.random() * 0.3 + 0.1})`  // White glow
                });
            }
        }

        bindEvents() {
            // Track mouse movement
            window.addEventListener('mousemove', (e) => {
                this.mouse.x = e.clientX;
                this.mouse.y = e.clientY;
            });

            // Clear mouse when it leaves the window
            window.addEventListener('mouseout', () => {
                this.mouse.x = null;
                this.mouse.y = null;
            });

            // Handle window resizing with a slight debounce protection
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.resizeCanvas();
                    this.createParticles();
                }, 200);
            });

            // Pause calculations if tab is not active (performance/battery saving)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.stop();
                } else {
                    this.start();
                }
            });

            // Throttle canvas render loop using intersection observer
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        this.isActive = entry.isIntersecting;
                        if (this.isActive) {
                            this.start();
                        } else {
                            this.stop();
                        }
                    });
                }, { threshold: 0.1 });
                observer.observe(this.canvas);
            }
        }

        update() {
            if (!this.isActive) return;

            const len = this.particles.length;
            for (let i = 0; i < len; i++) {
                const p = this.particles[i];

                // Base particle movement
                p.x += p.vx;
                p.y += p.vy;

                // Bounce at screen boundaries
                if (p.x < 0 || p.x > this.width) p.vx *= -1;
                if (p.y < 0 || p.y > this.height) p.vy *= -1;

                // Keep particles strictly within canvas bounds
                if (p.x < 0) p.x = 0;
                if (p.x > this.width) p.x = this.width;
                if (p.y < 0) p.y = 0;
                if (p.y > this.height) p.y = this.height;

                // Physics interaction: Mouse attraction / repulsion
                if (this.mouse.x !== null && this.mouse.y !== null) {
                    const dx = this.mouse.x - p.x;
                    const dy = this.mouse.y - p.y;
                    const distance = Math.hypot(dx, dy);

                    if (distance < this.mouse.radius) {
                        // Soft attraction force
                        const force = (this.mouse.radius - distance) / this.mouse.radius;
                        p.x -= dx * force * 0.02;
                        p.y -= dy * force * 0.02;
                    }
                }
            }
        }

        draw() {
            if (!this.isActive) return;

            this.ctx.clearRect(0, 0, this.width, this.height);
            const len = this.particles.length;

            // Draw link lines first (underneath nodes)
            for (let i = 0; i < len; i++) {
                const p1 = this.particles[i];
                for (let j = i + 1; j < len; j++) {
                    const p2 = this.particles[j];
                    const dx = p1.x - p2.x;
                    const dy = p1.y - p2.y;
                    const dist = Math.hypot(dx, dy);

                    // Connect nodes only if proximity allows
                    if (dist < this.maxDistance) {
                        const alpha = (1 - dist / this.maxDistance) * 0.12;
                        this.ctx.beginPath();
                        this.ctx.moveTo(p1.x, p1.y);
                        this.ctx.lineTo(p2.x, p2.y);
                        // Royal blue connecting lines
                        this.ctx.strokeStyle = `rgba(59, 130, 246, ${alpha})`;
                        this.ctx.lineWidth = 0.5;
                        this.ctx.stroke();
                    }
                }
            }

            // Draw individual particle nodes
            for (let i = 0; i < len; i++) {
                const p = this.particles[i];
                this.ctx.beginPath();
                this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                this.ctx.fillStyle = p.color;
                this.ctx.shadowBlur = p.size > 2 ? 8 : 0;
                this.ctx.shadowColor = 'rgba(59, 130, 246, 0.5)';
                this.ctx.fill();
            }
            
            // Clear shadow configuration for subsequent loops
            this.ctx.shadowBlur = 0;
        }

        tick() {
            if (!this.isActive) return;

            this.update();
            this.draw();
            this.animationFrameId = requestAnimationFrame(() => this.tick());
        }

        start() {
            if (this.animationFrameId) return;
            this.tick();
        }

        stop() {
            if (this.animationFrameId) {
                cancelAnimationFrame(this.animationFrameId);
                this.animationFrameId = null;
            }
        }
    }

    // Attach to window so header/footer can trigger
    window.initParticles = function (canvasId) {
        document.addEventListener('DOMContentLoaded', () => {
            new ParticleSystem(canvasId);
        });
    };

})();
