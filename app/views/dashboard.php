<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="dashboard">
    <div class="dashboard-header">
        <h1><?php echo __('dashboard.title'); ?></h1>
        <p><?php echo __('dashboard.welcome', ['nome' => $data['usuario']['nome']]); ?></p>
        
        <div class="dashboard-filters">
            <select onchange="window.location.href='dashboard.php?periodo='+this.value">
                <option value=""><?php echo __('dashboard.all_time'); ?></option>
                <option value="7dias" <?php echo $data['periodo_selecionado'] == '7dias' ? 'selected' : ''; ?>><?php echo __('dashboard.last_7_days'); ?></option>
                <option value="30dias" <?php echo $data['periodo_selecionado'] == '30dias' ? 'selected' : ''; ?>><?php echo __('dashboard.last_30_days'); ?></option>
                <option value="90dias" <?php echo $data['periodo_selecionado'] == '90dias' ? 'selected' : ''; ?>><?php echo __('dashboard.last_90_days'); ?></option>
            </select>
            <a href="dashboard.php?action=export" class="btn-export"><?php echo __('dashboard.export_data'); ?></a>
            <button onclick="openNotificationModal()" class="btn-notify" title="<?php echo __('notifications.notify_customers'); ?>">
                <span class="btn-icon">📢</span>
                <?php echo __('notifications.notify_customers'); ?>
            </button>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="dashboard-cards">
        <div class="card">
            <div class="card-icon">💰</div>
            <div class="card-content">
                <h3>R$ <?php echo number_format($data['resumo']['total_faturamento'] ?? 0, 2, ',', '.'); ?></h3>
                <p><?php echo __('dashboard.total_sales'); ?></p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-icon">📈</div>
            <div class="card-content">
                <h3>R$ <?php echo number_format($data['resumo']['total_lucro'] ?? 0, 2, ',', '.'); ?></h3>
                <p><?php echo __('dashboard.total_profit'); ?></p>
                <?php if ($data['crescimento'] != 0): ?>
                    <span class="growth <?php echo $data['crescimento'] > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $data['crescimento'] > 0 ? '+' : ''; ?><?php echo $data['crescimento']; ?>%
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-icon">🛍️</div>
            <div class="card-content">
                <h3><?php echo $data['resumo']['total_vendas'] ?? 0; ?></h3>
                <p><?php echo __('dashboard.products_sold'); ?></p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-icon">📦</div>
            <div class="card-content">
                <h3><?php echo $data['produtos_ativos']; ?></h3>
                <p><?php echo __('dashboard.active_products'); ?></p>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="dashboard-charts">
        <div class="chart-container">
            <h3><?php echo __('dashboard.sales_by_month'); ?></h3>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
        
        <div class="chart-container">
            <h3><?php echo __('dashboard.sales_by_category'); ?></h3>
            <canvas id="categoryChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Tabela de Vendas Recentes -->
    <div class="recent-sales">
        <h3><?php echo __('dashboard.recent_sales'); ?></h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><?php echo __('dashboard.product'); ?></th>
                        <th><?php echo __('dashboard.buyer'); ?></th>
                        <th><?php echo __('dashboard.sale_date'); ?></th>
                        <th><?php echo __('dashboard.sale_value'); ?></th>
                        <th><?php echo __('dashboard.profit'); ?></th>
                        <th><?php echo __('dashboard.status'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['vendas_recentes'] as $venda): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <span><?php echo htmlspecialchars($venda['produto_nome']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($venda['comprador_nome'] ?? 'Anonymous'); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($venda['data_venda'])); ?></td>
                            <td>R$ <?php echo number_format($venda['valor'] ?? 0, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($venda['valor'] ?? 0, 2, ',', '.'); ?></td>
                            <td>
                                <span class="status status-<?php echo strtolower($venda['status']); ?>">
                                    <?php echo ucfirst($venda['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
.dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Inter', sans-serif;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #5e2b2b;
    margin-bottom: 10px;
}

.dashboard-filters {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-top: 20px;
}

.dashboard-filters select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.btn-export {
    background: #5e2b2b;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease;
}

.btn-export:hover {
    background: #4a2222;
}

.btn-notify {
    background: #17a2b8;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-notify:hover {
    background: #138496;
}

.btn-icon {
    font-size: 1rem;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
}

.card-icon {
    font-size: 2.5rem;
}

.card-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 5px 0;
}

.card-content p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

.growth {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
    margin-top: 5px;
    display: inline-block;
}

.growth.positive {
    background: #d4edda;
    color: #155724;
}

.growth.negative {
    background: #f8d7da;
    color: #721c24;
}

.dashboard-charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.chart-container {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-container h3 {
    color: #5e2b2b;
    margin-bottom: 20px;
}

.recent-sales {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.recent-sales h3 {
    color: #5e2b2b;
    margin-bottom: 20px;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
}

.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-confirmada {
    background: #d4edda;
    color: #155724;
}

.status-pendente {
    background: #fff3cd;
    color: #856404;
}

.status-cancelada {
    background: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .dashboard {
        padding: 15px;
    }
    
    .dashboard-filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
    
    .dashboard-charts {
        grid-template-columns: 1fr;
    }
    
    .card {
        padding: 20px;
    }
    
    .chart-container {
        padding: 20px;
    }
}
</style>

<script>
// Dados para os gráficos
const vendasPorMes = <?php echo json_encode($data['vendas_por_mes']); ?>;
const vendasPorCategoria = <?php echo json_encode($data['vendas_por_categoria']); ?>;

// Gráfico de vendas por mês (simples com Canvas)
function drawSalesChart() {
    const canvas = document.getElementById('salesChart');
    const ctx = canvas.getContext('2d');
    
    if (vendasPorMes.length === 0) {
        ctx.fillStyle = '#666';
        ctx.font = '16px Inter';
        ctx.textAlign = 'center';
        ctx.fillText('<?php echo __("dashboard.no_data"); ?>', canvas.width/2, canvas.height/2);
        return;
    }
    
    const maxValue = Math.max(...vendasPorMes.map(v => parseFloat(v.valor_total)));
    const barWidth = canvas.width / vendasPorMes.length - 20;
    const barMaxHeight = canvas.height - 60;
    
    vendasPorMes.forEach((venda, index) => {
        const barHeight = (parseFloat(venda.valor_total) / maxValue) * barMaxHeight;
        const x = index * (barWidth + 20) + 10;
        const y = canvas.height - barHeight - 30;
        
        // Desenhar barra
        ctx.fillStyle = '#5e2b2b';
        ctx.fillRect(x, y, barWidth, barHeight);
        
        // Desenhar rótulo do mês
        ctx.fillStyle = '#333';
        ctx.font = '12px Inter';
        ctx.textAlign = 'center';
        ctx.fillText(venda.mes, x + barWidth/2, canvas.height - 10);
        
        // Desenhar valor
        ctx.fillStyle = '#666';
        ctx.font = '10px Inter';
        ctx.fillText('R$ ' + parseFloat(venda.valor_total).toFixed(0), x + barWidth/2, y - 5);
    });
}

// Gráfico de pizza simples para categorias
function drawCategoryChart() {
    const canvas = document.getElementById('categoryChart');
    const ctx = canvas.getContext('2d');
    
    if (vendasPorCategoria.length === 0) {
        ctx.fillStyle = '#666';
        ctx.font = '16px Inter';
        ctx.textAlign = 'center';
        ctx.fillText('<?php echo __("dashboard.no_data"); ?>', canvas.width/2, canvas.height/2);
        return;
    }
    
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = Math.min(centerX, centerY) - 40;
    
    const total = vendasPorCategoria.reduce((sum, cat) => sum + parseInt(cat.quantidade), 0);
    const colors = ['#5e2b2b', '#8b4513', '#a0522d', '#cd853f', '#daa520'];
    
    let currentAngle = 0;
    
    vendasPorCategoria.forEach((categoria, index) => {
        const sliceAngle = (parseInt(categoria.quantidade) / total) * 2 * Math.PI;
        
        // Desenhar fatia
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
        ctx.closePath();
        ctx.fillStyle = colors[index % colors.length];
        ctx.fill();
        
        // Desenhar rótulo
        const labelAngle = currentAngle + sliceAngle / 2;
        const labelX = centerX + Math.cos(labelAngle) * (radius + 20);
        const labelY = centerY + Math.sin(labelAngle) * (radius + 20);
        
        ctx.fillStyle = '#333';
        ctx.font = '12px Inter';
        ctx.textAlign = 'center';
        ctx.fillText(categoria.categoria, labelX, labelY);
        
        currentAngle += sliceAngle;
    });
}

// Inicializar gráficos quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    drawSalesChart();
    drawCategoryChart();
});

// Modal de notificações
function openNotificationModal() {
    window.open('notifications.php', '_blank');
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>