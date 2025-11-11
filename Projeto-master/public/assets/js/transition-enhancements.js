/**
 * Melhorias para o Sistema de Transições - DressCode
 * Efeitos adicionais inspirados na Renner
 */

class TransitionEnhancements {
    constructor() {
        this.init();
    }

    init() {
        this.addPreloadingEffect();
        this.addSmoothScrollReset();
        this.addFormTransitions();
        this.addTouchOptimizations();
    }

    // Efeito de pré-carregamento para links
    addPreloadingEffect() {
        const links = document.querySelectorAll('a[href*=".php"]');
        
        links.forEach(link => {
            if (this.shouldSkipLink(link)) return;
            
            link.addEventListener('mouseenter', () => {
                this.preloadPage(link.href);
            });
        });
    }

    preloadPage(url) {
        // Criar link de preload
        const preloadLink = document.createElement('link');
        preloadLink.rel = 'prefetch';
        preloadLink.href = url;
        document.head.appendChild(preloadLink);
        
        // Remover após 5 segundos para não sobrecarregar
        setTimeout(() => {
            if (preloadLink.parentNode) {
                preloadLink.parentNode.removeChild(preloadLink);
            }
        }, 5000);
    }

    // Reset suave do scroll
    addSmoothScrollReset() {
        window.addEventListener('beforeunload', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Transições para formulários
    addFormTransitions() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    submitBtn.style.transform = 'scale(0.95)';
                    submitBtn.style.opacity = '0.7';
                }
                
                // Adicionar classe de transição ao formulário
                form.classList.add('form-submitting');
            });
        });
    }

    // Otimizações para dispositivos touch
    addTouchOptimizations() {
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
            
            // Reduzir duração da animação em dispositivos móveis
            const style = document.createElement('style');
            style.textContent = `
                .touch-device .brand-content {
                    animation-duration: 0.8s !important;
                }
                .touch-device .brand-transition-overlay.fade-out .brand-content {
                    animation-duration: 0.4s !important;
                }
            `;
            document.head.appendChild(style);
        }
    }

    shouldSkipLink(link) {
        const href = link.getAttribute('href') || '';
        const skipPatterns = [
            'http://', 'https://', 'mailto:', 'tel:',
            '#', 'logout.php', 'javascript:',
            'toggle-favorito.php', 'change-language.php'
        ];
        
        return skipPatterns.some(pattern => href.includes(pattern));
    }
}

// Adicionar efeito de loading personalizado
class LoadingEnhancer {
    constructor() {
        this.addLoadingStates();
    }

    addLoadingStates() {
        // Mostrar indicador de carregamento para navegação lenta
        let loadingTimeout;
        
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href*=".php"]');
            if (!link || this.shouldSkipLink(link)) return;
            
            loadingTimeout = setTimeout(() => {
                this.showSlowLoadingIndicator();
            }, 2000);
        });
        
        window.addEventListener('beforeunload', () => {
            clearTimeout(loadingTimeout);
        });
    }

    showSlowLoadingIndicator() {
        const overlay = document.getElementById('brandTransitionOverlay');
        if (overlay && overlay.classList.contains('active')) {
            const content = overlay.querySelector('.brand-content');
            if (content) {
                const indicator = document.createElement('div');
                indicator.className = 'slow-loading-indicator';
                indicator.innerHTML = '<div class="loading-dots"><span></span><span></span><span></span></div>';
                indicator.style.cssText = `
                    position: absolute;
                    bottom: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    color: #5e2b2b;
                    font-size: 0.9rem;
                `;
                
                const style = document.createElement('style');
                style.textContent = `
                    .loading-dots span {
                        display: inline-block;
                        width: 8px;
                        height: 8px;
                        border-radius: 50%;
                        background: #5e2b2b;
                        margin: 0 2px;
                        animation: loadingBounce 1.4s ease-in-out infinite both;
                    }
                    .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
                    .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
                    @keyframes loadingBounce {
                        0%, 80%, 100% { transform: scale(0); }
                        40% { transform: scale(1); }
                    }
                    .tema-escuro .loading-dots span {
                        background: #ffffff;
                    }
                `;
                document.head.appendChild(style);
                
                content.appendChild(indicator);
            }
        }
    }

    shouldSkipLink(link) {
        const href = link.getAttribute('href') || '';
        const skipPatterns = [
            'http://', 'https://', 'mailto:', 'tel:',
            '#', 'logout.php', 'javascript:',
            'toggle-favorito.php', 'change-language.php'
        ];
        
        return skipPatterns.some(pattern => href.includes(pattern));
    }
}

// Inicializar melhorias quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new TransitionEnhancements();
    new LoadingEnhancer();
});

// Adicionar suporte a gestos em dispositivos móveis
if ('ontouchstart' in window) {
    let touchStartX = 0;
    let touchStartY = 0;
    
    document.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    });
    
    document.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;
        
        // Detectar swipe horizontal para navegação
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 100) {
            if (diffX > 0) {
                // Swipe left - próxima página (se aplicável)
                this.handleSwipeNavigation('next');
            } else {
                // Swipe right - página anterior
                this.handleSwipeNavigation('prev');
            }
        }
    });
}

// Função para navegação por swipe
function handleSwipeNavigation(direction) {
    // Implementar lógica de navegação por swipe se necessário
    // Por exemplo, voltar na história do navegador
    if (direction === 'prev' && window.history.length > 1) {
        window.history.back();
    }
}