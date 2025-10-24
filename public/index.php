<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
$page_title = __('site.title');
$page_description = __('site.description');
require_once '../includes/header.php';
?>

<main>
    <section class="destaques">
        <h2><?php echo __('home.highlights_title'); ?></h2>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="assets/images/destaque1.jpg" alt="<?php echo __('home.highlight1_alt'); ?>" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/destaque2.jpg" alt="<?php echo __('home.highlight2_alt'); ?>" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/destaque3.jpg" alt="<?php echo __('home.highlight3_alt'); ?>" loading="lazy">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="baseado-em-voce">
        <h2><?php echo __('home.categories_title'); ?></h2>
        <div class="cards">
            <div class="card" onclick="window.location.href='categoria.php?cat=feminino'">
                <img src="assets/images/personalizadas.jpg" alt="<?php echo __('home.women_fashion'); ?>" loading="lazy">
                <p><?php echo __('home.women_fashion'); ?></p>
            </div>
            <div class="card" onclick="window.location.href='categoria.php?cat=masculino'">
                <img src="assets/images/bolsas.jpg" alt="<?php echo __('home.men_fashion'); ?>" loading="lazy">
                <p><?php echo __('home.men_fashion'); ?></p>
            </div>
            <div class="card" onclick="window.location.href='categoria.php?cat=acessorios'">
                <img src="assets/images/streetwear.jpg" alt="<?php echo __('home.accessories'); ?>" loading="lazy">
                <p><?php echo __('home.accessories'); ?></p>
            </div>
        </div>
    </section>

    <section class="artigos">
        <h2><?php echo __('home.blog_title'); ?></h2>
        <div class="cards">
            <article class="artigo">
                <h3><?php echo __('home.article1_title'); ?></h3>
                <p><?php echo __('home.article1_text'); ?></p>
                <button onclick="mostrarManutencao()" aria-label="<?php echo __('home.article1_aria'); ?>"><?php echo __('home.read_more'); ?></button>
            </article>
            <article class="artigo">
                <h3><?php echo __('home.article2_title'); ?></h3>
                <p><?php echo __('home.article2_text'); ?></p>
                <button onclick="mostrarManutencao()" aria-label="<?php echo __('home.article2_aria'); ?>"><?php echo __('home.read_more'); ?></button>
            </article>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>