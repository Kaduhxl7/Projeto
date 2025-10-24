<?php
// Componente de transição da marca
$transition_config = require_once __DIR__ . '/../app/config/transitions.php';
$brand_name = $transition_config['brand_name'];
?>
<div id="brandTransitionOverlay" class="brand-transition-overlay">
    <div class="brand-content">
        <img src="assets/images/Logo.png" alt="<?php echo htmlspecialchars($brand_name); ?>" class="brand-logo">
        <h1 class="brand-name"><?php echo htmlspecialchars($brand_name); ?></h1>
    </div>
</div>