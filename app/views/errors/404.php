<?php
$page_title = "Página não encontrada - DressCode";
$page_description = "A página que você procura não foi encontrada.";
ob_start();
?>

<div class="error-page">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#5e2b2b"/>
                </svg>
            </div>
            
            <h1>404 - Página não encontrada</h1>
            <p>Ops! A página que você está procurando não existe ou foi movida.</p>
            
            <div class="error-actions">
                <a href="index.php" class="btn-primary">🏠 Voltar ao Início</a>
                <a href="categoria.php?cat=feminino" class="btn-secondary">👗 Ver Produtos</a>
            </div>
            
            <div class="suggestions">
                <h3>Que tal explorar:</h3>
                <div class="suggestion-links">
                    <a href="categoria.php?cat=feminino">Moda Feminina</a>
                    <a href="categoria.php?cat=masculino">Moda Masculina</a>
                    <a href="categoria.php?cat=infantil">Moda Infantil</a>
                    <a href="categoria.php?cat=acessorios">Acessórios</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 0 1rem;
}

.error-content {
    text-align: center;
    background: white;
    padding: 3rem 2rem;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.error-icon {
    margin-bottom: 2rem;
}

.error-content h1 {
    color: #5e2b2b;
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-family: 'Martel Sans', sans-serif;
}

.error-content p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #5e2b2b;
    color: white;
}

.btn-primary:hover {
    background: #4a2323;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #f5f5f5;
    color: #5e2b2b;
    border: 2px solid #5e2b2b;
}

.btn-secondary:hover {
    background: #5e2b2b;
    color: white;
}

.suggestions {
    border-top: 1px solid #eee;
    padding-top: 2rem;
}

.suggestions h3 {
    color: #5e2b2b;
    margin-bottom: 1rem;
}

.suggestion-links {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.suggestion-links a {
    padding: 0.5rem 1rem;
    background: #f8f8f8;
    color: #5e2b2b;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.suggestion-links a:hover {
    background: #5e2b2b;
    color: white;
}

@media (max-width: 768px) {
    .error-content {
        padding: 2rem 1rem;
    }
    
    .error-content h1 {
        font-size: 2rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        max-width: 250px;
    }
    
    .suggestion-links {
        flex-direction: column;
        align-items: center;
    }
    
    .suggestion-links a {
        width: 100%;
        max-width: 200px;
        text-align: center;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>