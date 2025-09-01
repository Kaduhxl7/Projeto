<?php
session_start();
$page_title = "DressCode - Moda Sustentável";
$page_description = "Descubra peças únicas em brechós online. Moda consciente e sustentável.";
require_once '../includes/header.php';
?>

<main>
    <section class="destaques">
        <h2>Destaques da Semana</h2>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="assets/images/destaque1.jpg" alt="Coleção Vintage Primavera" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/destaque2.jpg" alt="Peças Exclusivas Selecionadas" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/destaque3.jpg" alt="Tendências Sustentáveis" loading="lazy">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="baseado-em-voce">
        <h2>Categorias Populares</h2>
        <div class="cards">
            <div class="card" onclick="window.location.href='categoria.php?cat=feminino'">
                <img src="assets/images/personalizadas.jpg" alt="Moda Feminina" loading="lazy">
                <p>Moda Feminina</p>
            </div>
            <div class="card" onclick="window.location.href='categoria.php?cat=masculino'">
                <img src="assets/images/bolsas.jpg" alt="Moda Masculina" loading="lazy">
                <p>Moda Masculina</p>
            </div>
            <div class="card" onclick="window.location.href='categoria.php?cat=acessorios'">
                <img src="assets/images/streetwear.jpg" alt="Acessórios" loading="lazy">
                <p>Acessórios</p>
            </div>
        </div>
    </section>

    <section class="artigos">
        <h2>Blog de Moda Sustentável</h2>
        <div class="cards">
            <article class="artigo">
                <h3>A Volta dos Anos 2000</h3>
                <p>Descubra como as tendências Y2K estão dominando os brechós e como incorporar esse estilo no seu guarda-roupa de forma sustentável.</p>
                <button onclick="mostrarManutencao()" aria-label="Ler artigo completo sobre moda anos 2000">Ler mais</button>
            </article>
            <article class="artigo">
                <h3>Moda Circular: O Futuro é Agora</h3>
                <p>Entenda como o conceito de economia circular está revolucionando a indústria da moda e como você pode fazer parte dessa mudança.</p>
                <button onclick="mostrarManutencao()" aria-label="Ler artigo sobre moda circular">Ler mais</button>
            </article>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>