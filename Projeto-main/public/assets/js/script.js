// DressCode - JavaScript Principal
// Configurações globais
const DressCode = {
    config: {
        swiperDelay: 4000,
        searchMinLength: 2,
        animationDuration: 300
    },
    
    // Inicialização
    init() {
        this.initSwiper();
        this.initSearch();
        this.initAnimations();
        this.initAccessibility();
    },
    
    // Inicializar Swiper
    initSwiper() {
        if (document.querySelector('.mySwiper')) {
            new Swiper('.mySwiper', {
                loop: true,
                autoplay: {
                    delay: this.config.swiperDelay,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true
                },
                effect: 'slide',
                speed: 800,
                grabCursor: true,
                keyboard: {
                    enabled: true,
                    onlyInViewport: true
                },
                a11y: {
                    prevSlideMessage: 'Slide anterior',
                    nextSlideMessage: 'Próximo slide'
                }
            });
        }
    },
    
    // Inicializar busca
    initSearch() {
        const searchInput = document.querySelector('#searchInput');
        const searchIcon = document.querySelector('.search-icon');
        
        if (searchInput) {
            // Busca ao pressionar Enter
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.buscarProdutos();
                }
            });
            
            // Busca em tempo real (debounced)
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length >= this.config.searchMinLength) {
                    searchTimeout = setTimeout(() => {
                        this.sugerirBusca(query);
                    }, 500);
                }
            });
        }
        
        if (searchIcon) {
            searchIcon.addEventListener('click', () => this.buscarProdutos());
        }
    },
    
    // Inicializar animações
    initAnimations() {
        // Animação de entrada para cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observar cards e artigos
        document.querySelectorAll('.card, .artigo, .produto-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = `opacity ${this.config.animationDuration}ms ease, transform ${this.config.animationDuration}ms ease`;
            observer.observe(el);
        });
    },
    
    // Melhorar acessibilidade
    initAccessibility() {
        // Navegação por teclado para cards
        document.querySelectorAll('.card[onclick]').forEach(card => {
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'button');
            
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    card.click();
                }
            });
        });
    },
    
    // Função de busca
    buscarProdutos() {
        const searchInput = document.querySelector('#searchInput');
        const query = searchInput?.value.trim();
        
        if (!query) {
            this.mostrarAlerta('Digite algo para buscar', 'info');
            return;
        }
        
        if (query.length < this.config.searchMinLength) {
            this.mostrarAlerta(`Digite pelo menos ${this.config.searchMinLength} caracteres`, 'warning');
            return;
        }
        
        // Simular busca (futuramente será uma requisição real)
        this.mostrarAlerta('Funcionalidade de busca em desenvolvimento! 🔍', 'info');
    },
    
    // Sugestões de busca
    sugerirBusca(query) {
        // Futuramente implementar sugestões automáticas
        console.log(`Buscando sugestões para: ${query}`);
    },
    
    // Mostrar alertas personalizados
    mostrarAlerta(texto, tipo = 'info', titulo = null) {
        const titulos = {
            info: '💡 Informação',
            warning: '⚠️ Atenção',
            error: '❌ Erro',
            success: '✅ Sucesso'
        };
        
        Swal.fire({
            title: titulo || titulos[tipo],
            text: texto,
            icon: tipo,
            confirmButtonText: 'Entendi',
            confirmButtonColor: '#5e2b2b',
            background: '#fffdfc',
            color: '#5e2b2b',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }
};

// Funções globais para compatibilidade
function mostrarManutencao() {
    DressCode.mostrarAlerta('Esta funcionalidade estará disponível em breve! 🚀', 'info', '🔧 Em Desenvolvimento');
}

function buscarProdutos() {
    DressCode.buscarProdutos();
}

function mostrarDetalhes(produtoId) {
    DressCode.mostrarAlerta(`Carregando detalhes do produto #${produtoId}...`, 'info', '👗 Detalhes do Produto');
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    DressCode.init();
});

// Performance: Lazy loading para imagens
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        imageObserver.observe(img);
    });
}