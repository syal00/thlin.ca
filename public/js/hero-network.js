(function () {
    function initHeroNetwork() {
        const hero = document.querySelector('.is-home-page .home-hero');

        if (!hero) {
            return;
        }

        const canvas = hero.querySelector('[data-hero-network]');
        const video = hero.querySelector('.hero-video-bg video');

        if (!canvas) {
            return;
        }

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isMobile = window.matchMedia('(max-width: 640px)').matches;

        let animationId = null;
        let running = false;

        const ctx = canvas.getContext('2d');
        const particles = [];
        const particleCount = isMobile ? 48 : 72;
        const linkDistance = isMobile ? 120 : 155;

        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            const ratio = window.devicePixelRatio || 1;

            canvas.width = Math.max(1, Math.floor(rect.width * ratio));
            canvas.height = Math.max(1, Math.floor(rect.height * ratio));
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        }

        function createParticles() {
            particles.length = 0;

            const width = canvas.clientWidth;
            const height = canvas.clientHeight;

            for (let index = 0; index < particleCount; index += 1) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.35,
                    vy: (Math.random() - 0.5) * 0.35,
                    radius: Math.random() * 1.8 + 1.1,
                });
            }
        }

        function drawFrame() {
            const width = canvas.clientWidth;
            const height = canvas.clientHeight;

            ctx.clearRect(0, 0, width, height);

            for (let index = 0; index < particles.length; index += 1) {
                const particle = particles[index];

                particle.x += particle.vx;
                particle.y += particle.vy;

                if (particle.x <= 0 || particle.x >= width) {
                    particle.vx *= -1;
                }

                if (particle.y <= 0 || particle.y >= height) {
                    particle.vy *= -1;
                }

                for (let inner = index + 1; inner < particles.length; inner += 1) {
                    const other = particles[inner];
                    const dx = particle.x - other.x;
                    const dy = particle.y - other.y;
                    const distance = Math.hypot(dx, dy);

                    if (distance > linkDistance) {
                        continue;
                    }

                    const alpha = (1 - distance / linkDistance) * 0.34;

                    ctx.beginPath();
                    ctx.strokeStyle = 'rgba(186, 220, 255, ' + alpha + ')';
                    ctx.lineWidth = 1;
                    ctx.moveTo(particle.x, particle.y);
                    ctx.lineTo(other.x, other.y);
                    ctx.stroke();
                }
            }

            for (const particle of particles) {
                ctx.beginPath();
                ctx.fillStyle = 'rgba(220, 240, 255, 0.85)';
                ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
                ctx.fill();
            }

            if (running) {
                animationId = window.requestAnimationFrame(drawFrame);
            }
        }

        function startCanvas() {
            if (running || prefersReducedMotion) {
                canvas.classList.add('is-active');
                return;
            }

            running = true;
            canvas.classList.add('is-active');
            resizeCanvas();
            createParticles();
            drawFrame();
        }

        function stopCanvas() {
            running = false;
            canvas.classList.remove('is-active');

            if (animationId) {
                window.cancelAnimationFrame(animationId);
                animationId = null;
            }

            ctx.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight);
        }

        function useVideoBackground() {
            if (video) {
                video.classList.add('is-active');
            }

            stopCanvas();
        }

        function useCanvasBackground() {
            if (video) {
                video.classList.remove('is-active');
            }

            startCanvas();
        }

        function evaluateBackground() {
            if (prefersReducedMotion) {
                stopCanvas();

                if (video) {
                    video.classList.remove('is-active');
                }

                return;
            }

            if (isMobile) {
                useCanvasBackground();
                return;
            }

            if (!video) {
                useCanvasBackground();
                return;
            }

            const videoReady = video.readyState >= 2 && !video.error;

            if (videoReady && !video.paused) {
                useVideoBackground();
                return;
            }

            useCanvasBackground();
        }

        window.addEventListener('resize', () => {
            if (!running) {
                return;
            }

            resizeCanvas();
            createParticles();
        });

        if (video && !prefersReducedMotion && !isMobile) {
            video.addEventListener('loadeddata', evaluateBackground);
            video.addEventListener('playing', useVideoBackground);
            video.addEventListener('error', useCanvasBackground);
            video.addEventListener('stalled', useCanvasBackground);

            window.setTimeout(() => {
                if (video.error || video.readyState < 2) {
                    useCanvasBackground();
                }
            }, 2500);

            video.play().catch(useCanvasBackground);
        } else {
            evaluateBackground();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroNetwork);
    } else {
        initHeroNetwork();
    }
})();
