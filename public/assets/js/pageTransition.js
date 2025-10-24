/**
 * Sistema de Transições de Página - DressCode
 * Implementa transições suaves entre páginas com overlay da marca
 */

class PageTransition {
    constructor() {
        this.overlay = null;
        this.isTransitioning = false;
        this.transitionDuration = 800; // 800ms total
        this.init();
    }

    init() {
        // Aguardar DOM carregar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.createOverlay();
        this.bindNavigationLinks();
        this.setupPageContent();
    }

    createOverlay() {
        // Verificar se overlay já existe
        if (document.getElementById('brandTransitionOverlay')) {
            this.overlay = document.getElementById('brandTransitionOverlay');
            return;
        }

        // Criar overlay dinamicamente
        this.overlay = document.createElement('div');
        this.overlay.id = 'brandTransitionOverlay';
        this.overlay.className = 'brand-transition-overlay';
        
        this.overlay.innerHTML = `
            <div class="brand-content">
                <img src="assets/images/Logo.png" alt="DressCode" class="brand-logo">
                <h1 class="brand-name">DressCode</h1>
            </div>
        `;
        
        document.body.appendChild(this.overlay);
    }

    setupPageContent() {
        // Adicionar classe ao conteúdo principal
        const main = document.querySelector('main');
        if (main && !main.classList.contains('page-content')) {
            main.classList.add('page-content');
        }
    }

    bindNavigationLinks() {
        // Selecionar links de navegação
        const navLinks = document.querySelectorAll('nav a, .card[onclick], a[href*=".php"]');
        
        navLinks.forEach(link => {
            // Pular links externos, âncoras e alguns específicos
            if (this.shouldSkipLink(link)) return;
            
            link.addEventListener('click', (e) => this.handleLinkClick(e, link));
        });
    }

    shouldSkipLink(link) {
        const href = link.getAttribute('href') || link.getAttribute('onclick');
        if (!href) return true;
        
        // Pular links externos, âncoras, logout, e alguns específicos
        const skipPatterns = [
            'http://', 'https://', 'mailto:', 'tel:',
            '#', 'logout.php', 'javascript:',
            'toggle-favorito.php', 'change-language.php'
        ];
        
        return skipPatterns.some(pattern => href.includes(pattern));
    }

    handleLinkClick(event, link) {
        // Evitar múltiplas transições simultâneas
        if (this.isTransitioning) {
            event.preventDefault();
            return;
        }

        // Obter URL de destino
        let targetUrl = this.getTargetUrl(link);
        if (!targetUrl) return;

        // Verificar se é a mesma página
        if (this.isSamePage(targetUrl)) return;

        // Interceptar clique
        event.preventDefault();
        this.startTransition(targetUrl);
    }

    getTargetUrl(link) {
        // Para links normais
        if (link.href) {
            return link.href;
        }
        
        // Para elementos com onclick (como cards)
        const onclick = link.getAttribute('onclick');
        if (onclick && onclick.includes('window.location.href')) {
            const match = onclick.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
            return match ? match[1] : null;
        }
        
        return null;
    }

    isSamePage(targetUrl) {
        const currentPath = window.location.pathname;
        const targetPath = new URL(targetUrl, window.location.origin).pathname;
        return currentPath === targetPath;
    }

    startTransition(targetUrl) {
        this.isTransitioning = true;
        
        // Animar saída do conteúdo atual
        const pageContent = document.querySelector('.page-content');
        if (pageContent) {
            pageContent.classList.add('transitioning');
        }
        
        // Mostrar overlay com delay
        setTimeout(() => {
            this.overlay.classList.add('active');
        }, 100);
        
        // Navegar após animação
        setTimeout(() => {
            window.location.href = targetUrl;
        }, this.transitionDuration);
    }

    // Método para transição de entrada (quando página carrega)
    static enterPage() {
        const overlay = document.getElementById('brandTransitionOverlay');
        const pageContent = document.querySelector('.page-content');
        
        if (overlay && overlay.classList.contains('active')) {
            // Animar saída do overlay
            setTimeout(() => {
                overlay.classList.add('fade-out');
            }, 200);
            
            setTimeout(() => {
                overlay.classList.remove('active', 'fade-out');
                if (pageContent) {
                    pageContent.classList.remove('transitioning');
                }
            }, 600);
        }
    }
}

// Inicializar sistema de transições
const pageTransition = new PageTransition();

// Transição de entrada quando página carrega
window.addEventListener('load', () => {
    PageTransition.enterPage();
});

// Fallback para DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        PageTransition.enterPage();
    }, 100);
});