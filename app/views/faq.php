<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="faq-container">
    <div class="faq-header">
        <h1><?php echo __('faq.title'); ?></h1>
        <p><?php echo __('faq.subtitle'); ?></p>
        
        <!-- Barra de busca -->
        <div class="faq-search">
            <input type="text" id="faqSearch" placeholder="<?php echo __('faq.search_placeholder'); ?>" aria-label="<?php echo __('faq.search_label'); ?>">
            <button type="button" onclick="clearSearch()" id="clearBtn" style="display: none;"><?php echo __('faq.clear_search'); ?></button>
        </div>
    </div>

    <div class="faq-content">
        <!-- Seção: Conta e Login -->
        <section class="faq-section" data-category="account">
            <h2 class="faq-category-title"><?php echo __('faq.categories.account'); ?></h2>
            <?php foreach ($faqs['account'] as $index => $faq): ?>
                <div class="faq-item" data-question="<?php echo strtolower($faq['question']); ?>">
                    <h3 class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-icon">+</span>
                        <?php echo $faq['question']; ?>
                    </h3>
                    <div class="faq-answer">
                        <p><?php echo $faq['answer']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Seção: Produtos -->
        <section class="faq-section" data-category="products">
            <h2 class="faq-category-title"><?php echo __('faq.categories.products'); ?></h2>
            <?php foreach ($faqs['products'] as $index => $faq): ?>
                <div class="faq-item" data-question="<?php echo strtolower($faq['question']); ?>">
                    <h3 class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-icon">+</span>
                        <?php echo $faq['question']; ?>
                    </h3>
                    <div class="faq-answer">
                        <p><?php echo $faq['answer']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Seção: Plataforma -->
        <section class="faq-section" data-category="platform">
            <h2 class="faq-category-title"><?php echo __('faq.categories.platform'); ?></h2>
            <?php foreach ($faqs['platform'] as $index => $faq): ?>
                <div class="faq-item" data-question="<?php echo strtolower($faq['question']); ?>">
                    <h3 class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-icon">+</span>
                        <?php echo $faq['question']; ?>
                    </h3>
                    <div class="faq-answer">
                        <p><?php echo $faq['answer']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <!-- Seção de contato -->
    <div class="faq-contact">
        <h3><?php echo __('faq.contact.title'); ?></h3>
        <p><?php echo __('faq.contact.description'); ?></p>
    </div>
</main>

<style>
.faq-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Inter', sans-serif;
}

.faq-header {
    text-align: center;
    margin-bottom: 40px;
}

.faq-header h1 {
    color: #5e2b2b;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.faq-header p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.faq-search {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.faq-search input {
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    width: 300px;
    max-width: 100%;
}

.faq-search input:focus {
    outline: none;
    border-color: #5e2b2b;
}

.faq-search button {
    padding: 12px 16px;
    background: #5e2b2b;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

.faq-section {
    margin-bottom: 40px;
}

.faq-category-title {
    color: #5e2b2b;
    font-size: 1.5rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #5e2b2b;
}

.faq-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.faq-question {
    background: #f8f9fa;
    padding: 20px;
    margin: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1.1rem;
    font-weight: 500;
    color: #333;
    transition: background-color 0.3s ease;
}

.faq-question:hover {
    background: #e9ecef;
}

.faq-icon {
    font-size: 1.2rem;
    font-weight: bold;
    color: #5e2b2b;
    transition: transform 0.3s ease;
    min-width: 20px;
    text-align: center;
}

.faq-answer {
    padding: 0 20px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.faq-answer.active {
    padding: 20px;
    max-height: 200px;
}

.faq-answer p {
    margin: 0;
    line-height: 1.6;
    color: #555;
}

.faq-contact {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    margin-top: 40px;
}

.faq-contact h3 {
    color: #5e2b2b;
    margin-bottom: 10px;
}

.faq-contact p {
    color: #666;
    margin: 0;
}

.faq-item.hidden {
    display: none;
}

@media (max-width: 768px) {
    .faq-container {
        padding: 15px;
    }
    
    .faq-header h1 {
        font-size: 2rem;
    }
    
    .faq-search {
        flex-direction: column;
    }
    
    .faq-search input {
        width: 100%;
    }
    
    .faq-question {
        padding: 15px;
        font-size: 1rem;
    }
    
    .faq-answer.active {
        padding: 15px;
    }
}
</style>

<script>
function toggleFaq(element) {
    const answer = element.nextElementSibling;
    const icon = element.querySelector('.faq-icon');
    
    // Fechar outras respostas abertas
    document.querySelectorAll('.faq-answer.active').forEach(activeAnswer => {
        if (activeAnswer !== answer) {
            activeAnswer.classList.remove('active');
            activeAnswer.previousElementSibling.querySelector('.faq-icon').textContent = '+';
        }
    });
    
    // Toggle da resposta atual
    if (answer.classList.contains('active')) {
        answer.classList.remove('active');
        icon.textContent = '+';
    } else {
        answer.classList.add('active');
        icon.textContent = '−';
    }
}

// Funcionalidade de busca
document.getElementById('faqSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    const clearBtn = document.getElementById('clearBtn');
    
    if (searchTerm.length > 0) {
        clearBtn.style.display = 'block';
    } else {
        clearBtn.style.display = 'none';
    }
    
    faqItems.forEach(item => {
        const question = item.dataset.question;
        const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
    
    // Mostrar/ocultar seções vazias
    document.querySelectorAll('.faq-section').forEach(section => {
        const visibleItems = section.querySelectorAll('.faq-item:not(.hidden)');
        if (visibleItems.length === 0) {
            section.style.display = 'none';
        } else {
            section.style.display = 'block';
        }
    });
});

function clearSearch() {
    document.getElementById('faqSearch').value = '';
    document.getElementById('clearBtn').style.display = 'none';
    
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('hidden');
    });
    
    document.querySelectorAll('.faq-section').forEach(section => {
        section.style.display = 'block';
    });
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>