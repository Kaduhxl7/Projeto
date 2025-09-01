<?php
$page_title = htmlspecialchars($data['produto']['nome']) . " - DressCode";
$page_description = "Compre " . htmlspecialchars($data['produto']['nome']) . " por R$ " . number_format($data['produto']['preco'], 2, ',', '.') . ". Moda sustentável no DressCode.";
$additional_css = ['assets/css/pages.css'];
ob_start();
?>

<div class="product-detail">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="index.php">Início</a> > 
            <a href="categoria.php?cat=<?php echo htmlspecialchars($data['produto']['categoria_slug']); ?>">
                <?php echo htmlspecialchars($data['produto']['categoria_nome']); ?>
            </a> > 
            <span><?php echo htmlspecialchars($data['produto']['nome']); ?></span>
        </nav>

        <div class="product-content">
            <!-- Imagem do produto -->
            <div class="product-image">
                <img src="assets/images/<?php echo htmlspecialchars($data['produto']['imagem']); ?>" 
                     alt="<?php echo htmlspecialchars($data['produto']['nome']); ?>">
            </div>

            <!-- Informações do produto -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($data['produto']['nome']); ?></h1>
                
                <div class="price">
                    <span class="current-price">R$ <?php echo number_format($data['produto']['preco'], 2, ',', '.'); ?></span>
                </div>

                <div class="product-details">
                    <div class="detail-item">
                        <strong>Tamanho:</strong> <?php echo htmlspecialchars($data['produto']['tamanho']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Cor:</strong> <?php echo htmlspecialchars($data['produto']['cor']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Marca:</strong> <?php echo htmlspecialchars($data['produto']['marca']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Condição:</strong> 
                        <span class="condition <?php echo strtolower($data['produto']['condicao']); ?>">
                            <?php echo htmlspecialchars($data['produto']['condicao']); ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong>Categoria:</strong> <?php echo htmlspecialchars($data['produto']['categoria_nome']); ?>
                    </div>
                </div>

                <?php if (!empty($data['produto']['descricao'])): ?>
                <div class="description">
                    <h3>Descrição</h3>
                    <p><?php echo nl2br(htmlspecialchars($data['produto']['descricao'])); ?></p>
                </div>
                <?php endif; ?>

                <div class="actions">
                    <button class="btn-primary" onclick="mostrarInteresse()">
                        💬 Demonstrar Interesse
                    </button>
                    <button class="btn-secondary" onclick="adicionarFavoritos(<?php echo $data['produto']['id']; ?>)">
                        ❤️ Adicionar aos Favoritos
                    </button>
                    <button class="btn-secondary" onclick="abrirModalAvaliacao(<?php echo $data['produto']['id']; ?>)">
                        ⭐ Avaliar Produto
                    </button>
                    <button class="btn-secondary" onclick="compartilhar()">
                        📤 Compartilhar
                    </button>
                </div>

                <div class="product-stats">
                    <small>👁️ <?php echo $data['produto']['visualizacoes']; ?> visualizações</small>
                    <small>📅 Publicado em <?php echo date('d/m/Y', strtotime($data['produto']['created_at'])); ?></small>
                </div>
            </div>
        </div>

        <!-- Produtos relacionados -->
        <?php if (!empty($data['produtos_relacionados'])): ?>
        <section class="related-products">
            <h2>Produtos Relacionados</h2>
            <div class="products-grid">
                <?php foreach ($data['produtos_relacionados'] as $produto): ?>
                    <div class="produto-card" onclick="window.location.href='produto.php?id=<?php echo $produto['id']; ?>'">
                        <img src="assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>" loading="lazy">
                        
                        <div class="produto-info">
                            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                            <div class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                            <div class="produto-detalhes">
                                <span><?php echo htmlspecialchars($produto['tamanho']); ?></span>
                                <span><?php echo htmlspecialchars($produto['cor']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Avaliações -->
        <section class="avaliacoes-section">
            <h2>⭐ Avaliações</h2>
            <div id="avaliacoes-container">
                <!-- Avaliações serão carregadas via JavaScript -->
            </div>
        </section>
    </div>
</div>

<!-- Modal de Avaliação -->
<div id="modalAvaliacao" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem; color: #5e2b2b;">⭐ Avaliar Produto</h3>
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nota:</label>
            <div class="rating-stars">
                <span class="star" data-rating="1">★</span>
                <span class="star" data-rating="2">★</span>
                <span class="star" data-rating="3">★</span>
                <span class="star" data-rating="4">★</span>
                <span class="star" data-rating="5">★</span>
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Comentário:</label>
            <textarea id="comentarioAvaliacao" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e1d8d8; border-radius: 8px; resize: vertical;" placeholder="Conte sua experiência com este produto..."></textarea>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button onclick="enviarAvaliacao()" style="flex: 1; background: #5e2b2b; color: white; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Enviar Avaliação
            </button>
            <button onclick="fecharModalAvaliacao()" style="flex: 1; background: #f5f5f5; color: #5e2b2b; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Cancelar
            </button>
        </div>
    </div>
</div>

<style>
.product-detail { padding: 2rem 0; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
.breadcrumb { margin-bottom: 2rem; color: #666; }
.breadcrumb a { color: #5e2b2b; text-decoration: none; }
.breadcrumb a:hover { text-decoration: underline; }
.product-content { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem; }
.product-image img { width: 100%; height: 500px; object-fit: cover; border-radius: 12px; }
.product-info h1 { color: #5e2b2b; font-size: 2rem; margin-bottom: 1rem; }
.price { margin-bottom: 2rem; }
.current-price { font-size: 2rem; font-weight: bold; color: #5e2b2b; }
.product-details { margin-bottom: 2rem; }
.detail-item { margin-bottom: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #eee; }
.condition.novo { color: #28a745; }
.condition.seminovo { color: #ffc107; }
.condition.usado { color: #dc3545; }
.description { margin-bottom: 2rem; }
.description h3 { color: #5e2b2b; margin-bottom: 1rem; }
.actions { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
.btn-primary, .btn-secondary { padding: 1rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
.btn-primary { background: #5e2b2b; color: white; }
.btn-primary:hover { background: #4a2323; }
.btn-secondary { background: #f5f5f5; color: #5e2b2b; }
.btn-secondary:hover { background: #e5e5e5; }
.product-stats { color: #666; font-size: 0.9rem; }
.product-stats small { display: block; margin-bottom: 0.25rem; }
.related-products { margin-top: 3rem; }
.related-products h2 { color: #5e2b2b; margin-bottom: 2rem; text-align: center; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; }
.avaliacoes-section { margin-top: 3rem; }
.avaliacoes-section h2 { color: #5e2b2b; margin-bottom: 2rem; }
.avaliacao-item { background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; }
.avaliacao-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.avaliacao-usuario { font-weight: 600; color: #5e2b2b; }
.avaliacao-data { color: #666; font-size: 0.9rem; }
.avaliacao-nota { color: #ffc107; margin-bottom: 0.5rem; }
.avaliacao-comentario { line-height: 1.6; }
.rating-stars { display: flex; gap: 0.25rem; margin-bottom: 0.5rem; }
.star { font-size: 2rem; color: #ddd; cursor: pointer; transition: color 0.2s; }
.star:hover, .star.active { color: #ffc107; }
.estatisticas-avaliacoes { background: #f5f5f5; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
.media-nota { font-size: 2rem; font-weight: bold; color: #5e2b2b; text-align: center; }
.total-avaliacoes { text-align: center; color: #666; margin-bottom: 1rem; }
@media (max-width: 768px) {
    .product-content { grid-template-columns: 1fr; gap: 2rem; }
    .product-image img { height: 400px; }
    .actions { flex-direction: column; }
}
</style>

<script>
function mostrarInteresse() {
    Swal.fire({
        title: '💬 Demonstrar Interesse',
        text: 'Funcionalidade em desenvolvimento! Em breve você poderá entrar em contato diretamente com o vendedor.',
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#5e2b2b'
    });
}

function adicionarFavoritos(produtoId) {
    <?php if (isset($_SESSION['user_id'])): ?>
        fetch('toggle-favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                produto_id: produtoId,
                action: 'toggle'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Resposta:', data);
            Swal.fire({
                title: data.status === 'success' ? '❤️ Sucesso!' : '❌ Erro',
                text: data.message,
                icon: data.status,
                confirmButtonColor: '#5e2b2b'
            });
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                title: '❌ Erro',
                text: 'Erro ao processar solicitação: ' + error.message,
                icon: 'error',
                confirmButtonColor: '#5e2b2b'
            });
        });
    <?php else: ?>
        Swal.fire({
            title: '🔐 Login Necessário',
            text: 'Você precisa estar logado para adicionar favoritos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Fazer Login',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#5e2b2b'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php';
            }
        });
    <?php endif; ?>
}

function compartilhar() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo htmlspecialchars($data['produto']['nome']); ?>',
            text: 'Confira este produto no DressCode!',
            url: window.location.href
        });
    } else {
        // Fallback para copiar URL
        navigator.clipboard.writeText(window.location.href).then(() => {
            Swal.fire({
                title: '📤 Link Copiado!',
                text: 'O link do produto foi copiado para a área de transferência.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
}

let notaSelecionada = 0;
let produtoIdAtual = 0;

function abrirModalAvaliacao(produtoId) {
    <?php if (isset($_SESSION['user_id'])): ?>
        produtoIdAtual = produtoId;
        document.getElementById('modalAvaliacao').style.display = 'flex';
        notaSelecionada = 0;
        document.getElementById('comentarioAvaliacao').value = '';
        atualizarEstrelas();
    <?php else: ?>
        Swal.fire({
            title: '🔐 Login Necessário',
            text: 'Você precisa estar logado para avaliar produtos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Fazer Login',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#5e2b2b'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php';
            }
        });
    <?php endif; ?>
}

function fecharModalAvaliacao() {
    document.getElementById('modalAvaliacao').style.display = 'none';
}

function enviarAvaliacao() {
    if (notaSelecionada === 0) {
        Swal.fire('Erro', 'Por favor, selecione uma nota', 'error');
        return;
    }
    
    const comentario = document.getElementById('comentarioAvaliacao').value;
    
    fetch('adicionar-avaliacao.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            produto_id: produtoIdAtual,
            nota: notaSelecionada,
            comentario: comentario
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Sucesso!', data.message, 'success').then(() => {
                fecharModalAvaliacao();
                carregarAvaliacoes(produtoIdAtual);
            });
        } else {
            Swal.fire('Erro', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Erro', 'Erro ao enviar avaliação', 'error');
    });
}

function atualizarEstrelas() {
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        star.classList.toggle('active', index < notaSelecionada);
        star.onclick = () => {
            notaSelecionada = index + 1;
            atualizarEstrelas();
        };
    });
}

function carregarAvaliacoes(produtoId) {
    fetch(`get-avaliacoes.php?produto_id=${produtoId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('avaliacoes-container');
            
            if (data.avaliacoes && data.avaliacoes.length > 0) {
                let html = '<div class="estatisticas-avaliacoes">';
                html += `<div class="media-nota">${parseFloat(data.estatisticas.media_nota).toFixed(1)} ★</div>`;
                html += `<div class="total-avaliacoes">${data.estatisticas.total_avaliacoes} avaliações</div>`;
                html += '</div>';
                
                data.avaliacoes.forEach(avaliacao => {
                    html += '<div class="avaliacao-item">';
                    html += '<div class="avaliacao-header">';
                    html += `<span class="avaliacao-usuario">${avaliacao.usuario_nome}</span>`;
                    html += `<span class="avaliacao-data">${new Date(avaliacao.created_at).toLocaleDateString('pt-BR')}</span>`;
                    html += '</div>';
                    html += `<div class="avaliacao-nota">${'★'.repeat(avaliacao.nota)}${'☆'.repeat(5-avaliacao.nota)}</div>`;
                    if (avaliacao.comentario) {
                        html += `<div class="avaliacao-comentario">${avaliacao.comentario}</div>`;
                    }
                    html += '</div>';
                });
                
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p style="text-align: center; color: #666;">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar avaliações:', error);
        });
}

// Carregar avaliações ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const produtoId = <?php echo $data['produto']['id']; ?>;
    carregarAvaliacoes(produtoId);
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>