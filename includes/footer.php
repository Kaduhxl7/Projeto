    <footer>
        <div>
            <strong><?php echo __('footer.about'); ?></strong><br>
            <?php echo __('footer.about_text'); ?>
        </div>
        <div>
            <strong><?php echo __('footer.social'); ?></strong><br>
            @DressCodeInstagram<br>
            @DressCodeTikTok
        </div>
        <div>
            <strong><?php echo __('footer.help'); ?></strong><br>
            <?php echo __('footer.faq'); ?><br>
            <?php echo __('footer.how_it_works'); ?><br>
            <?php echo __('footer.support'); ?><br>
            <?php echo __('footer.terms'); ?>
        </div>
        <div>
            <strong><?php echo __('footer.contact'); ?></strong><br>
            suportedresscode@dresscode.com<br>
            (11) 92348-9076
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    <script src="assets/js/pageTransition.js"></script>
    
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>