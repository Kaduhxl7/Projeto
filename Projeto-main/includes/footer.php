    <footer>
        <div>
            <strong>Sobre</strong><br>
            "DressCode" é um projeto independente com o objetivo de divulgar a moda consciente e sustentável...
        </div>
        <div>
            <strong>Redes sociais</strong><br>
            @DressCodeInstagram<br>
            @DressCodeTikTok
        </div>
        <div>
            <strong>Ajuda</strong><br>
            FAQ<br>
            Como funciona<br>
            Suporte<br>
            Termos de uso
        </div>
        <div>
            <strong>Contato</strong><br>
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
    
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>