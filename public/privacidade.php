<?php
session_start();
require_once '../app/controllers/SecurityController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$security = new SecurityController();
$security->logAction($_SESSION['user_id'], 'ACESSO_PAGINA_PRIVACIDADE');

$page_title = "Privacidade e Dados - DressCode";
$page_description = "Gerencie seus dados pessoais e configurações de privacidade";
require_once '../includes/header.php';
?>

<main style="padding: 2rem 1rem; min-height: 70vh;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="color: #5e2b2b; margin-bottom: 2rem; text-align: center;">🔒 Privacidade e Dados</h1>
        
        <!-- Consentimentos LGPD -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h2 style="color: #5e2b2b; margin-bottom: 1.5rem;">📋 Consentimentos LGPD</h2>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-coleta" checked disabled>
                    <strong>Coleta de Dados Básicos</strong> (Obrigatório)
                </label>
                <p>Coletamos dados básicos como nome e email para funcionamento da plataforma.</p>
            </div>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-marketing" onchange="updateConsent('marketing', this.checked)">
                    <strong>Marketing e Comunicações</strong>
                </label>
                <p>Receber ofertas, promoções e novidades por email.</p>
            </div>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-cookies" onchange="updateConsent('cookies', this.checked)">
                    <strong>Cookies de Personalização</strong>
                </label>
                <p>Usar cookies para personalizar sua experiência no site.</p>
            </div>
            
            <div class="consent-item">
                <label>
                    <input type="checkbox" id="consent-compartilhamento" onchange="updateConsent('compartilhamento', this.checked)">
                    <strong>Compartilhamento com Parceiros</strong>
                </label>
                <p>Compartilhar dados anonimizados com parceiros para melhorar serviços.</p>
            </div>
        </div>
        
        <!-- Seus Direitos -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h2 style="color: #5e2b2b; margin-bottom: 1.5rem;">⚖️ Seus Direitos</h2>
            
            <div class="rights-grid">
                <div class="right-item">
                    <h3>📥 Acessar Dados</h3>
                    <p>Baixe uma cópia de todos os seus dados.</p>
                    <button onclick="solicitarDados('acesso')" class="btn-action">Solicitar Dados</button>
                </div>
                
                <div class="right-item">
                    <h3>✏️ Corrigir Dados</h3>
                    <p>Solicite correção de dados incorretos.</p>
                    <button onclick="solicitarDados('correcao')" class="btn-action">Solicitar Correção</button>
                </div>
                
                <div class="right-item">
                    <h3>📤 Portabilidade</h3>
                    <p>Exporte seus dados em formato legível.</p>
                    <button onclick="exportarDados()" class="btn-action">Exportar Dados</button>
                </div>
                
                <div class="right-item">
                    <h3>🗑️ Excluir Conta</h3>
                    <p>Exclua permanentemente sua conta e dados.</p>
                    <button onclick="excluirConta()" class="btn-danger">Excluir Conta</button>
                </div>
            </div>
        </div>
        
        <!-- Informações de Segurança -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="color: #5e2b2b; margin-bottom: 1.5rem;">🛡️ Segurança</h2>
            
            <div class="security-info">
                <div class="info-item">
                    <strong>🔐 Criptografia:</strong> Seus dados são criptografados com AES-256
                </div>
                <div class="info-item">
                    <strong>📊 Logs:</strong> Mantemos logs de acesso por 90 dias
                </div>
                <div class="info-item">
                    <strong>🌐 IP:</strong> Seu IP atual: <code id="user-ip"></code>
                </div>
                <div class="info-item">
                    <strong>⏰ Último acesso:</strong> <span id="last-access"></span>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                <h4>📞 Contato do Encarregado de Dados (DPO)</h4>
                <p>Para questões sobre privacidade: <strong>dpo@dresscode.com</strong></p>
                <p>Resposta em até 72 horas conforme LGPD</p>
            </div>
        </div>
    </div>
</main>

<style>
.consent-item {
    margin-bottom: 1.5rem;
    padding: 1rem;
    border: 1px solid #e1d8d8;
    border-radius: 8px;
}

.consent-item label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    cursor: pointer;
    margin-bottom: 0.5rem;
}

.consent-item input[type="checkbox"] {
    margin-top: 0.25rem;
}

.consent-item p {
    color: #666;
    font-size: 0.9rem;
    margin-left: 1.5rem;
}

.rights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.right-item {
    text-align: center;
    padding: 1.5rem;
    border: 1px solid #e1d8d8;
    border-radius: 8px;
}

.right-item h3 {
    color: #5e2b2b;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.right-item p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.btn-action, .btn-danger {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-action {
    background: #5e2b2b;
    color: white;
}

.btn-action:hover {
    background: #4a2323;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.security-info {
    display: grid;
    gap: 1rem;
}

.info-item {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .rights-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Mostrar IP do usuário
fetch('https://api.ipify.org?format=json')
    .then(response => response.json())
    .then(data => {
        document.getElementById('user-ip').textContent = data.ip;
    })
    .catch(() => {
        document.getElementById('user-ip').textContent = 'Não disponível';
    });

// Mostrar último acesso
document.getElementById('last-access').textContent = new Date().toLocaleString('pt-BR');

function updateConsent(tipo, consentimento) {
    fetch('update-consent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            tipo: tipo,
            consentimento: consentimento
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Atualizado!', 'Consentimento atualizado com sucesso.', 'success');
        }
    });
}

function solicitarDados(tipo) {
    Swal.fire({
        title: 'Confirmar Solicitação',
        text: `Deseja solicitar ${tipo} dos seus dados?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('data-request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    tipo: tipo
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('Solicitado!', data.message, 'success');
            });
        }
    });
}

function exportarDados() {
    Swal.fire('Processando...', 'Preparando seus dados para download.', 'info');
    
    fetch('export-data.php', {
        method: 'POST'
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'meus-dados-dresscode.json';
        a.click();
        window.URL.revokeObjectURL(url);
        
        Swal.fire('Sucesso!', 'Seus dados foram baixados.', 'success');
    });
}

function excluirConta() {
    Swal.fire({
        title: '⚠️ ATENÇÃO',
        text: 'Esta ação é IRREVERSÍVEL. Todos os seus dados serão excluídos permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir tudo',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Digite "EXCLUIR" para confirmar',
                input: 'text',
                inputPlaceholder: 'Digite EXCLUIR',
                showCancelButton: true,
                confirmButtonText: 'Confirmar Exclusão',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                preConfirm: (value) => {
                    if (value !== 'EXCLUIR') {
                        Swal.showValidationMessage('Digite exatamente "EXCLUIR"');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete-account.php', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Conta Excluída', 'Seus dados foram excluídos permanentemente.', 'success').then(() => {
                                window.location.href = 'index.php';
                            });
                        }
                    });
                }
            });
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>