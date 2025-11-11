class PageTransition {
    constructor() {
        this.overlay = null;
        this.isTransitioning = false;
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.createOverlay();
        this.bindLinks();
        this.setupPageContent();
    }

    createOverlay() {
        this.overlay = document.getElementById('brandTransitionOverlay');
        if (!this.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.id = 'brandTransitionOverlay';
            this.overlay.className = 'brand-transition-overlay';
            this.overlay.innerHTML = `
                <div class="brand-content">
                    <div class="brand-letter">D</div>
                    <div class="brand-subtitle">DressCode</div>
                </div>
            `;
            document.body.appendChild(this.overlay);
        }
    }

    setupPageContent() {
        const main = document.querySelector('main');
        if (main && !main.classList.contains('page-content')) {
            main.classList.add('page-content');
        }
    }

    bindLinks() {
        const links = document.querySelectorAll('a[href*=".php"], .card[onclick]');
        links.forEach(link => {
            if (this.shouldSkipLink(link)) return;
            link.addEventListener('click', (e) => this.handleClick(e, link));
        });
    }

    shouldSkipLink(link) {
        const href = link.getAttribute('href') || link.getAttribute('onclick') || '';
        return ['http', 'mailto', 'tel', '#', 'logout', 'toggle-favorito', 'change-language'].some(skip => href.includes(skip));
    }

    handleClick(event, link) {
        if (this.isTransitioning) {
            event.preventDefault();
            return;
        }

        let targetUrl = this.getTargetUrl(link);
        if (!targetUrl || this.isSamePage(targetUrl)) return;

        event.preventDefault();
        this.startTransition(targetUrl);
    }

    getTargetUrl(link) {
        if (link.href) return link.href;
        const onclick = link.getAttribute('onclick');
        if (onclick && onclick.includes('window.location.href')) {
            const match = onclick.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
            return match ? match[1] : null;
        }
        return null;
    }

    isSamePage(targetUrl) {
        return window.location.pathname === new URL(targetUrl, window.location.origin).pathname;
    }

    startTransition(targetUrl) {
        this.isTransitioning = true;
        
        const pageContent = document.querySelector('.page-content');
        if (pageContent) pageContent.classList.add('transitioning');
        
        this.overlay.classList.add('active');
        
        setTimeout(() => {
            window.location.href = targetUrl;
        }, 1000);
    }

    static enterPage() {
        const overlay = document.getElementById('brandTransitionOverlay');
        const pageContent = document.querySelector('.page-content');
        
        if (overlay && overlay.classList.contains('active')) {
            setTimeout(() => overlay.classList.add('fade-out'), 300);
            setTimeout(() => {
                overlay.classList.remove('active', 'fade-out');
                if (pageContent) pageContent.classList.remove('transitioning');
            }, 900);
        }
    }
}

const pageTransition = new PageTransition();

window.addEventListener('load', () => PageTransition.enterPage());
document.addEventListener('DOMContentLoaded', () => setTimeout(() => PageTransition.enterPage(), 100));