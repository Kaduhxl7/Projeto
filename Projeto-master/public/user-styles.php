<?php
session_start();
header('Content-Type: text/css');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

// Configurações padrão
$configuracoes = [
    'tema' => 'claro',
    'cor_primaria' => '#5e2b2b',
    'tamanho_fonte' => 'medio',
    'layout' => 'grid',
    'mostrar_precos' => true
];

// Se usuário logado, buscar configurações
if (isset($_SESSION['user_id'])) {
    try {
        require_once '../app/controllers/ConfiguracoesController.php';
        $configController = new ConfiguracoesController();
        $userConfig = $configController->getConfiguracoes($_SESSION['user_id']);
        if ($userConfig) {
            $configuracoes = array_merge($configuracoes, $userConfig);
        }
    } catch (Exception $e) {
        // Usar configurações padrão em caso de erro
    }
}
?>

/* Configurações personalizadas do usuário */
:root {
    --cor-primaria: <?= $configuracoes['cor_primaria'] ?>;
    --cor-primaria-hover: <?= $configuracoes['cor_primaria'] ?>dd;
}

/* Tema Escuro */
<?php if ($configuracoes['tema'] == 'escuro'): ?>
/* Background principal */
body,
html {
    background: #1a1a1a !important;
    color: #fff !important;
}

/* Header e navegação */
header {
    background: #2d2d2d !important;
    border-bottom-color: #444 !important;
}

.top-banner {
    background: linear-gradient(90deg, #444 0%, #333 100%) !important;
}

/* Seções principais */
.destaques,
.baseado-em-voce,
.artigos,
main,
section {
    background: #1a1a1a !important;
    color: #fff !important;
}

/* Cards e conteúdo */
.card, 
.produto-card, 
main > div,
main > div > div,
.container,
.avaliacao-item,
.estatisticas-avaliacoes,
.config-section,
.swiper,
.product-detail,
.product-detail .container,
.related-products,
.avaliacoes-section {
    background: #2d2d2d !important;
    color: #fff !important;
    border-color: #444 !important;
}

/* Swiper e carrossel */
.swiper,
.swiper-wrapper,
.swiper-slide {
    background: #2d2d2d !important;
}

.swiper-slide img {
    border: 2px solid #444 !important;
}

/* Inputs e formulários */
input, 
textarea, 
select,
.search-box {
    background: #444 !important;
    color: #fff !important;
    border-color: #666 !important;
}

input::placeholder,
textarea::placeholder {
    color: #ccc !important;
}

/* Footer */
footer {
    background: #2d2d2d !important;
    border-top-color: #444 !important;
    color: #fff !important;
}

/* Modais */
#modalAvaliacao,
#modalAvaliacao > div {
    background: rgba(0,0,0,0.8) !important;
}

#modalAvaliacao > div > div {
    background: #2d2d2d !important;
    color: #fff !important;
}

/* Breadcrumb */
.breadcrumb {
    color: #ccc !important;
}

/* Artigos */
.artigo {
    background: linear-gradient(135deg, #555 0%, #444 100%) !important;
    color: #fff !important;
}

/* Botões secundários */
.btn-secondary {
    background: #444 !important;
    color: #fff !important;
}

.btn-secondary:hover {
    background: #555 !important;
}

/* Páginas específicas */
.product-content,
.product-image,
.product-info {
    background: #2d2d2d !important;
    color: #fff !important;
}

/* Filtros e busca */
.filters,
.search-results,
.category-header {
    background: #2d2d2d !important;
    color: #fff !important;
}

/* Textos e parágrafos */
p, span, div {
    color: inherit !important;
}

/* Links no tema escuro */
a {
    color: #66b3ff !important;
}

a:hover {
    color: #4da6ff !important;
}
<?php endif; ?>

/* Tamanho da fonte */
<?php if ($configuracoes['tamanho_fonte'] == 'pequeno'): ?>
body { font-size: 14px; }
<?php elseif ($configuracoes['tamanho_fonte'] == 'grande'): ?>
body { font-size: 18px; }
<?php endif; ?>

/* Layout Lista */
<?php if ($configuracoes['layout'] == 'lista'): ?>
.products-grid,
.cards {
    display: flex !important;
    flex-direction: column !important;
    gap: 1rem !important;
}

.produto-card,
.card {
    display: flex !important;
    flex-direction: row !important;
    max-width: 100% !important;
    align-items: center !important;
}

.produto-card img,
.card img {
    width: 200px !important;
    height: 150px !important;
    flex-shrink: 0 !important;
    margin-right: 1rem !important;
    margin-bottom: 0 !important;
}

.produto-info,
.card p {
    flex: 1 !important;
    padding-left: 1rem !important;
    margin: 0 !important;
}

/* Ajustes para cards de categoria */
.card {
    padding: 1rem !important;
    text-align: left !important;
}

.card p {
    padding: 0 !important;
    background: transparent !important;
    color: var(--cor-primaria) !important;
    font-size: 1.2rem !important;
    font-weight: 600 !important;
}

@media (max-width: 768px) {
    .produto-card,
    .card {
        flex-direction: column !important;
        text-align: center !important;
    }
    
    .produto-card img,
    .card img {
        width: 100% !important;
        margin-right: 0 !important;
        margin-bottom: 1rem !important;
    }
    
    .produto-info {
        padding-left: 0 !important;
    }
}
<?php endif; ?>

/* Preços */
<?php if (!$configuracoes['mostrar_precos']): ?>
.produto-preco,
.current-price,
.price {
    display: none !important;
}
<?php endif; ?>

/* Correções específicas para tema escuro */
<?php if ($configuracoes['tema'] == 'escuro'): ?>
/* Forçar background em todas as seções */
* {
    background-color: inherit;
}

body * {
    color: inherit;
}

/* Seções específicas que precisam de background escuro */
.destaques,
.baseado-em-voce,
.artigos {
    background: #1a1a1a !important;
}

/* Cards dentro das seções */
.destaques .cards,
.baseado-em-voce .cards,
.artigos .cards {
    background: transparent !important;
}

/* Swiper no tema escuro */
.mySwiper {
    background: #2d2d2d !important;
}

/* Garantir que imagens não tenham background */
img {
    background: transparent !important;
}

/* Títulos das seções */
.destaques h2,
.baseado-em-voce h2,
.artigos h2 {
    color: #fff !important;
    background: transparent !important;
}

/* CSS mais agressivo para garantir tema escuro */
body.tema-escuro,
body.tema-escuro *:not(img):not(svg) {
    background-color: #1a1a1a !important;
    color: #fff !important;
}

body.tema-escuro .card,
body.tema-escuro .produto-card,
body.tema-escuro header,
body.tema-escuro footer,
body.tema-escuro .swiper,
body.tema-escuro main > div,
body.tema-escuro section {
    background-color: #2d2d2d !important;
    color: #fff !important;
}

body.tema-escuro .destaques,
body.tema-escuro .baseado-em-voce,
body.tema-escuro .artigos {
    background-color: #1a1a1a !important;
}

/* Páginas de categoria específicas */
body.tema-escuro .category-page,
body.tema-escuro .category-page .container,
body.tema-escuro .page-header,
body.tema-escuro .content-wrapper,
body.tema-escuro .filters-sidebar,
body.tema-escuro .products-main,
body.tema-escuro .filter-group,
body.tema-escuro .sort-bar {
    background-color: #1a1a1a !important;
    color: #fff !important;
}

body.tema-escuro .filter-group details,
body.tema-escuro .filter-group ul,
body.tema-escuro .filter-group li {
    background-color: #2d2d2d !important;
    color: #fff !important;
}

body.tema-escuro .btn-apply,
body.tema-escuro .btn-clear {
    background-color: #444 !important;
    color: #fff !important;
}

body.tema-escuro .pagination .page-link {
    background-color: #2d2d2d !important;
    color: #fff !important;
    border-color: #444 !important;
}

body.tema-escuro .pagination .page-link.active {
    background-color: var(--cor-primaria) !important;
}

body.tema-escuro .no-products {
    background-color: #2d2d2d !important;
    color: #fff !important;
}

/* CSS mais específico para forçar tema escuro nas páginas de categoria */
body.tema-escuro .category-page * {
    background-color: inherit !important;
    color: inherit !important;
}

body.tema-escuro .category-page {
    background-color: #1a1a1a !important;
    color: #fff !important;
}

body.tema-escuro .category-page .filters-sidebar,
body.tema-escuro .category-page .produto-card,
body.tema-escuro .category-page .filter-group details,
body.tema-escuro .category-page .sort-bar select,
body.tema-escuro .category-page .price-range input {
    background-color: #2d2d2d !important;
    color: #fff !important;
    border-color: #444 !important;
}

body.tema-escuro .category-page h1,
body.tema-escuro .category-page h2,
body.tema-escuro .category-page h3,
body.tema-escuro .category-page .page-header h1,
body.tema-escuro .category-page .filters-sidebar h3 {
    color: #fff !important;
}

body.tema-escuro .category-page .filter-group summary {
    color: #fff !important;
    border-bottom-color: #444 !important;
}
<?php endif; ?>

/* Aplicar cor primária em todos os elementos */
header nav a[aria-current="page"]::after,
.btn-primary,
.btn:hover,
.card p,
.artigo,
.logo a,
.current-price,
.product-info h1,
.top-banner,
.container button[type="submit"],
input[type="submit"],
button.btn-primary,
.search-icon:hover,
.swiper-pagination-bullet-active {
    background-color: var(--cor-primaria) !important;
    color: white !important;
}

/* Elementos que devem ter apenas a cor, sem background */
nav a:hover,
.product-info h1,
h1, h2, h3,
.destaques h2,
.baseado-em-voce h2,
.artigos h2,
.breadcrumb a,
.produto-info h3,
.produto-preco,
.detail-item strong,
.avaliacao-usuario,
.footer h4,
footer strong,
label,
.config-section h3 {
    color: var(--cor-primaria) !important;
    background: transparent !important;
}

/* Hover states */
.btn-primary:hover,
button[type="submit"]:hover,
.container button[type="submit"]:hover {
    background-color: var(--cor-primaria-hover) !important;
}

/* Borders e outlines */
input:focus,
textarea:focus,
select:focus,
.search-box:focus-within {
    border-color: var(--cor-primaria) !important;
    outline-color: var(--cor-primaria) !important;
}

/* Links */
a {
    color: var(--cor-primaria) !important;
}

a:hover {
    color: var(--cor-primaria-hover) !important;
}

/* Cards e produtos */
.card:hover,
.produto-card:hover {
    border-color: var(--cor-primaria) !important;
}

/* Condições de produtos */
.condition.novo,
.produto-condicao.novo {
    background: #d1ecf1 !important;
    color: #0c5460 !important;
}

.condition.seminovo,
.produto-condicao.seminovo {
    background: #fff3cd !important;
    color: #856404 !important;
}

.condition.usado,
.produto-condicao.usado {
    background: #f8d7da !important;
    color: #721c24 !important;
}