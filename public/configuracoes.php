<?php
session_start();
require_once '../app/controllers/ConfiguracoesController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$configController = new ConfiguracoesController();
$configuracoes = $configController->getConfiguracoes($_SESSION['user_id']);

// Valores padrão para evitar warnings
$configuracoes = array_merge([
    'tema' => 'claro',
    'cor_primaria' => '#5e2b2b',
    'tamanho_fonte' => 'medio',
    'layout' => 'grid',
    'produtos_por_pagina' => 12,
    'mostrar_precos' => true,
    'notificacoes' => true
], $configuracoes ?: []);

$page_title = "Configurações - DressCode";
$page_description = "Personalize sua experiência no DressCode";
require_once '../includes/header.php';
?>

<main style="padding: 2rem 1rem; min-height: 70vh;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="color: #5e2b2b; margin-bottom: 2rem; text-align: center;">⚙️ Configurações</h1>
        
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <form id="configForm">
                <!-- Tema -->
                <div class="config-section">
                    <h3>🎨 Aparência</h3>
                    
                    <div class="config-item">
                        <label>Tema:</label>
                        <select name="tema" id="tema">
                            <option value="claro" <?= $configuracoes['tema'] == 'claro' ? 'selected' : '' ?>>Claro</option>
                            <option value="escuro" <?= $configuracoes['tema'] == 'escuro' ? 'selected' : '' ?>>Escuro</option>
                        </select>
                    </div>
                    
                    <div class="config-item">
                        <label>Cor Principal:</label>
                        <input type="color" name="cor_primaria" value="<?= $configuracoes['cor_primaria'] ?>">
                    </div>
                    
                    <div class="config-item">
                        <label>Tamanho da Fonte:</label>
                        <select name="tamanho_fonte">
                            <option value="pequeno" <?= $configuracoes['tamanho_fonte'] == 'pequeno' ? 'selected' : '' ?>>Pequeno</option>
                            <option value="medio" <?= $configuracoes['tamanho_fonte'] == 'medio' ? 'selected' : '' ?>>Médio</option>
                            <option value="grande" <?= $configuracoes['tamanho_fonte'] == 'grande' ? 'selected' : '' ?>>Grande</option>
                        </select>
                    </div>
                </div>
                
                <!-- Layout -->
                <div class="config-section">
                    <h3>📱 Layout</h3>
                    
                    <div class="config-item">
                        <label>Visualização de Produtos:</label>
                        <select name="layout">
                            <option value="grid" <?= $configuracoes['layout'] == 'grid' ? 'selected' : '' ?>>Grade</option>
                            <option value="lista" <?= $configuracoes['layout'] == 'lista' ? 'selected' : '' ?>>Lista</option>
                        </select>
                    </div>
                    
                    <div class="config-item">
                        <label>Produtos por Página:</label>
                        <select name="produtos_por_pagina">
                            <option value="6" <?= $configuracoes['produtos_por_pagina'] == 6 ? 'selected' : '' ?>>6</option>
                            <option value="12" <?= $configuracoes['produtos_por_pagina'] == 12 ? 'selected' : '' ?>>12</option>
                            <option value="24" <?= $configuracoes['produtos_por_pagina'] == 24 ? 'selected' : '' ?>>24</option>
                        </select>
                    </div>
                </div>
                
                <!-- Preferências -->
                <div class="config-section">
                    <h3>🔔 Preferências</h3>
                    
                    <div class="config-item">
                        <label>
                            <input type="checkbox" name="mostrar_precos" <?= $configuracoes['mostrar_precos'] ? 'checked' : '' ?>>
                            Mostrar preços nos produtos
                        </label>
                    </div>
                    
                    <div class="config-item">
                        <label>
                            <input type="checkbox" name="notificacoes" <?= $configuracoes['notificacoes'] ? 'checked' : '' ?>>
                            Receber notificações
                        </label>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" style="background: #5e2b2b; color: white; border: none; padding: 1rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; margin-right: 1rem;">
                        💾 Salvar Configurações
                    </button>
                    <button type="button" onclick="resetarConfiguracoes()" style="background: #f5f5f5; color: #5e2b2b; border: none; padding: 1rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        🔄 Restaurar Padrão
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Preview -->
        <div id="preview" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem;">👁️ Visualização</h3>
            <div class="preview-content">
                <div class="produto-card-preview">
                    <div class="produto-imagem-preview" style="background: #f0f0f0; height: 150px; border-radius: 8px; margin-bottom: 1rem;"></div>
                    <h4>Produto de Exemplo</h4>
                    <div class="produto-preco-preview">R$ 45,00</div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.config-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #eee;
}

.config-section h3 {
    color: #5e2b2b;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.config-item {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.config-item label {
    min-width: 200px;
    font-weight: 500;
}

.config-item select, .config-item input[type="color"] {
    padding: 0.5rem;
    border: 2px solid #e1d8d8;
    border-radius: 6px;
    min-width: 150px;
}

.config-item input[type="checkbox"] {
    margin-right: 0.5rem;
}

.produto-card-preview {
    max-width: 200px;
    text-align: center;
    transition: all 0.3s ease;
}

.produto-preco-preview {
    font-weight: bold;
    margin-top: 0.5rem;
}

/* Temas */
body.tema-escuro {
    background: #1a1a1a;
    color: #fff;
}

body.tema-escuro main > div > div {
    background: #2d2d2d !important;
}

/* Tamanhos de fonte */
body.fonte-pequeno { font-size: 14px; }
body.fonte-medio { font-size: 16px; }
body.fonte-grande { font-size: 18px; }

@media (max-width: 768px) {
    .config-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .config-item label {
        min-width: auto;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('configForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const configuracoes = {
        tema: formData.get('tema'),
        cor_primaria: formData.get('cor_primaria'),
        tamanho_fonte: formData.get('tamanho_fonte'),
        layout: formData.get('layout'),
        produtos_por_pagina: parseInt(formData.get('produtos_por_pagina')),
        mostrar_precos: formData.get('mostrar_precos') === 'on',
        notificacoes: formData.get('notificacoes') === 'on'
    };
    
    fetch('salvar-configuracoes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(configuracoes)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Sucesso!', data.message, 'success').then(() => {
                aplicarConfiguracoes(configuracoes);
            });
        } else {
            Swal.fire('Erro', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Erro', 'Erro ao salvar configurações', 'error');
    });
});

function aplicarConfiguracoes(config) {
    // Aplicar tema
    document.body.className = '';
    document.body.classList.add(`tema-${config.tema}`);
    document.body.classList.add(`fonte-${config.tamanho_fonte}`);
    
    // Aplicar cor primária
    document.documentElement.style.setProperty('--cor-primaria', config.cor_primaria);
    
    // Atualizar preview
    atualizarPreview(config);
}

function atualizarPreview(config) {
    const preview = document.querySelector('.produto-card-preview');
    const preco = document.querySelector('.produto-preco-preview');
    
    if (!config.mostrar_precos) {
        preco.style.display = 'none';
    } else {
        preco.style.display = 'block';
    }
    
    preview.style.color = config.cor_primaria;
}

function resetarConfiguracoes() {
    Swal.fire({
        title: 'Restaurar Configurações?',
        text: 'Isso irá restaurar todas as configurações para o padrão.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}

// Aplicar configurações ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const config = <?= json_encode($configuracoes) ?>;
    aplicarConfiguracoes(config);
});
</script>

<?php require_once '../includes/footer.php'; ?>